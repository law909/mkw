/**
 * window.CKFinder shim – a CKFinder 2.3 API-kompatibilis pótlása a saját médiatárral.
 */
(function ($) {
    'use strict';

    var BROWSEURL = '/admin/mediatar/browse',
        UPLOADURL = '/admin/mediatar/quickupload',
        TYPES = ['Images', 'Videos', 'Files'],
        callbacks = {},
        nextcb = 1;

    /**
     * A hívók a tárolt kép-URL-ből ezt adják át:
     *      path.substring(path.indexOf('/', 1))
     * ami egy '/termek/foo.jpg' alakú érték – MAPPA ÉS FÁJLNÉV –, opcionális
     * 'Images:' prefixszel. A parsernek ezeket kell túlélnie:
     *
     *   'Images:/termek/foo.jpg'  → Images, /termek/, foo.jpg
     *   '/termek/foo.jpg'         → (resourceType), /termek/, foo.jpg
     *   'Images:/foo.jpg'         → Images, /, foo.jpg
     *   'foo.jpg'                 → Images, /, foo.jpg   (indexOf('/',1) === -1 esetén
     *                                a substring(-1) az egész sztringet adja vissza)
     *   'Images://cdn.x/y.jpg'    → Images, /, ''        (a tárolt érték abszolút URL volt)
     *   'Images:/termek/'         → Images, /termek/, ''
     */
    function parseStartupPath(startupPath, resourceType) {
        var type = ($.inArray(resourceType, TYPES) !== -1) ? resourceType : 'Images',
            raw = startupPath == null ? '' : String(startupPath),
            colon = raw.indexOf(':'),
            folder = '/',
            file = '';

        if (colon > 0 && $.inArray(raw.substring(0, colon), TYPES) !== -1) {
            type = raw.substring(0, colon);
            raw = raw.substring(colon + 1);
        }

        // Abszolút URL ('//cdn.x/y.jpg' vagy 'http://…') – nincs értelmezhető helyi útvonal.
        if (raw.indexOf('//') === 0 || /^[a-z][a-z0-9+.\-]*:\/\//i.test(raw)) {
            return {type: type, path: '/', file: ''};
        }

        if (raw !== '') {
            var slash = raw.lastIndexOf('/');
            if (slash === -1) {
                folder = '/';
                file = raw;
            } else {
                folder = raw.substring(0, slash + 1);
                file = raw.substring(slash + 1);
            }
            if (folder.charAt(0) !== '/') {
                folder = '/' + folder;
            }
        }

        return {type: type, path: folder, file: file};
    }

    function CKFinder() {
        this.startupPath = '';
        this.resourceType = '';
        this.selectActionFunction = null;
    }

    /**
     * Lapon belüli modal dialógus, benne az azonos eredetű választó iframe.
     *
     * Viselkedésváltozás a CKFinderhez képest: az eredeti valódi böngészőablakot
     * nyitott. Saját konténert használunk, nem a megosztott #dialogcenter-t –
     * a hívási helyek fele éppen azzal dolgozik.
     */
    CKFinder.prototype.popup = function () {
        var parsed = parseStartupPath(this.startupPath, this.resourceType),
            cb = nextcb++,
            self = this,
            url = BROWSEURL
                + '?type=' + encodeURIComponent(parsed.type)
                + '&path=' + encodeURIComponent(parsed.path)
                + '&cb=' + cb;

        if (parsed.file) {
            url += '&sel=' + encodeURIComponent(parsed.file);
        }

        callbacks[cb] = function (fileUrl, data) {
            if (typeof self.selectActionFunction === 'function') {
                self.selectActionFunction(fileUrl, data);
            }
        };

        var $c = $('<div class="js-mediatardialog"></div>')
            .css({padding: 0, overflow: 'hidden'})
            .appendTo('body');

        // Az iframe SRC NÉLKÜL jön létre. A .dialog() az elemet beemeli a frissen
        // létrehozott .ui-dialog burkolatba, és egy iframe DOM-mozgatása újratölti
        // a tartalmát – ha a src már be lenne állítva, az első kérés (canceled)
        // állapotban elhalna, és a szerver kétszer szolgálná ki a browse oldalt.
        var $frame = $('<iframe frameborder="0"></iframe>')
            .css({width: '100%', height: '100%', border: 0, display: 'block'})
            .appendTo($c);

        $c.dialog({
            title: 'Médiatár',
            modal: true,
            resizable: true,
            width: Math.min(1000, $(window).width() - 40),
            height: Math.min(700, $(window).height() - 60),
            close: function () {
                delete callbacks[cb];
                // Ugyanaz fordítva: a dialog('destroy') VISSZAHELYEZI az elemet az
                // eredeti DOM-pozíciójába (jQuery UI 1.11 _destroy), ami megint
                // újratöltené az iframe-et. Előbb bontsuk le.
                $frame.remove();
                $(this).dialog('destroy').remove();
            }
        });

        // Csak azután, hogy a dialógus a helyére mozgatta a konténert.
        $frame.attr('src', url);

        callbacks[cb].$dialog = $c;
    };

    /**
     * Az iframe-ből hívva: window.parent.CKFinder._select(cb, fileUrl, data).
     * Azonos eredet, tehát nincs szükség postMessage-re.
     */
    CKFinder._select = function (cb, fileUrl, data) {
        var fn = callbacks[cb];
        if (!fn) {
            return;
        }
        var $d = fn.$dialog;
        try {
            fn(fileUrl, data || {});
        } finally {
            delete callbacks[cb];
            if ($d) {
                $d.dialog('close');
            }
        }
    };

    /**
     * Az iframe-ből hívva, ha a felhasználó a Mégse gombot nyomta.
     */
    CKFinder._cancel = function (cb) {
        var fn = callbacks[cb];
        if (fn && fn.$dialog) {
            fn.$dialog.dialog('close');
        }
        delete callbacks[cb];
    };

    CKFinder.setupCKEditor = function (editor, basePath, imageType, flashType) {
        if (typeof CKEDITOR === 'undefined') {
            return;
        }
        if (editor === null || editor === undefined) {
            for (var k in CKEDITOR.instances) {
                if (CKEDITOR.instances.hasOwnProperty(k)) {
                    CKFinder.setupCKEditor(CKEDITOR.instances[k], basePath, imageType, flashType);
                }
            }
            if (!CKFinder._hooked) {
                CKFinder._hooked = true;
                CKEDITOR.on('instanceCreated', function (ev) {
                    CKFinder.setupCKEditor(ev.editor, basePath, imageType, flashType);
                });
            }
            return;
        }

        var b = BROWSEURL + '?ck=1',
            u = UPLOADURL;

        editor.config.filebrowserBrowseUrl = b + '&type=Files';
        editor.config.filebrowserImageBrowseUrl = b + '&type=' + (imageType || 'Images');
        editor.config.filebrowserUploadUrl = u + '?type=Files';
        editor.config.filebrowserImageUploadUrl = u + '?type=' + (imageType || 'Images');
        editor.config.filebrowserWindowWidth = 1000;
        editor.config.filebrowserWindowHeight = 700;

        editor.config.filebrowserFlashBrowseUrl = '';
        editor.config.filebrowserFlashUploadUrl = '';
    };

    CKFinder._hooked = false;
    CKFinder._parseStartupPath = parseStartupPath;

    window.CKFinder = CKFinder;
})(jQuery);
