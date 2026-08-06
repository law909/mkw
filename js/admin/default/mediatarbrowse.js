/**
 * Médiatár – választó UI. Ez a script az iframe (CKFinder-shim) vagy a popup ablak
 * (CKEditor 3.6.1) belsejében fut, és a /admin/mediatar/* végpontokkal beszél.
 */
(function ($) {
    'use strict';

    var PAGE = 200;

    var $root = $('#mediatar'),
        state = {
            type: $root.data('type') || 'Images',
            path: $root.data('path') || '/',
            sel: String($root.data('sel') || ''),
            cb: parseInt($root.data('cb'), 10) || 0,
            funcnum: parseInt($root.data('funcnum'), 10) || 0,
            maxsize: parseInt($root.data('maxsize'), 10) || 0,
            writable: String($root.data('writable')) === '1',
            files: [],
            folders: [],
            shown: 0,
            filter: '',
            selected: null
        };

    var $grid = $('#mtGrid'),
        $crumbs = $('#mtCrumbs'),
        $empty = $('#mtEmpty'),
        $more = $('#mtMore'),
        $queue = $('#mtQueue'),
        $drop = $('#mtDrop'),
        $info = $('#mtInfo');

    // ------------------------------------------------------------------
    // Segédek
    // ------------------------------------------------------------------

    function esc(s) {
        return $('<div/>').text(s == null ? '' : s).html();
    }

    function fmtSize(b) {
        b = b || 0;
        if (b >= 1048576) {
            return (Math.round(b / 104857.6) / 10) + ' MB';
        }
        if (b >= 1024) {
            return Math.round(b / 1024) + ' KB';
        }
        return b + ' B';
    }

    function message(text) {
        $('.mt-msg').remove();
        if (!text) {
            return;
        }
        $('<div class="mt-msg"></div>').text(text).insertBefore($('.mt-head'));
    }

    function textToHtml(s) {
        return esc(s).replace(/\n/g, '<br/>');
    }

    /**
     * Szövegbekérés. A visszahívás CSAK elfogadás esetén fut le, kitöltött értékkel.
     *
     * A `warning` a kiemelt (borostyán) doboz – ez a „N helyen használatban van"
     * típusú figyelmeztetésnek van fenntartva. A semleges kérdés a `label`.
     *
     * @param opts {title, warning, label, value, okText}
     */
    function askText(opts, fn) {
        var $body = $('<div class="mt-ask"></div>'),
            $input = $('<input type="text" class="mt-askinput"/>').val(opts.value || '');

        if (opts.warning) {
            $body.append($('<div class="mt-askmsg"></div>').html(textToHtml(opts.warning)));
        }
        if (opts.label) {
            $body.append($('<label class="mt-asklabel"></label>').text(opts.label));
        }
        $body.append($input);

        function accept() {
            var v = $.trim($input.val());
            if (!v) {
                $input.focus();
                return;
            }
            $body.dialog('close');
            fn(v);
        }

        $input.on('keydown', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                accept();
            }
        });

        $body.dialog({
            title: opts.title || 'Médiatár',
            modal: true,
            resizable: false,
            width: Math.min(420, $(window).width() - 30),
            buttons: [
                {text: opts.okText || 'OK', click: accept},
                {
                    text: 'Mégse', click: function () {
                        $(this).dialog('close');
                    }
                }
            ],
            open: function () {
                $input.focus().select();
            },
            close: function () {
                $(this).dialog('destroy').remove();
            }
        });
    }

    /**
     * Megerősítés. A visszahívás CSAK igenlő válasz esetén fut le.
     *
     * @param opts {title, warning, message, okText}
     */
    function askConfirm(opts, fn) {
        var $body = $('<div class="mt-ask"></div>');

        if (opts.warning) {
            $body.append($('<div class="mt-askmsg"></div>').html(textToHtml(opts.warning)));
        }
        $body.append($('<div class="mt-asktext"></div>').html(textToHtml(opts.message)));

        $body.dialog({
            title: opts.title || 'Megerősítés',
            modal: true,
            resizable: false,
            width: Math.min(420, $(window).width() - 30),
            buttons: [
                {
                    text: opts.okText || 'Igen',
                    click: function () {
                        $(this).dialog('close');
                        fn();
                    }
                },
                {
                    text: 'Mégse', click: function () {
                        $(this).dialog('close');
                    }
                }
            ],
            close: function () {
                $(this).dialog('destroy').remove();
            }
        });
    }

    /**
     * Egyetlen kilépési pont. A CKEditor a valódi popup ablakot az openerén keresztül
     * éri el, a shim iframe-je a parentjén – lásd a fájl fejlécét.
     */
    function finish(url, name) {
        if (state.funcnum) {
            try {
                window.opener.CKEDITOR.tools.callFunction(state.funcnum, url);
            } catch (e) {
                message('Nem sikerült visszaadni a kiválasztott fájlt a szerkesztőnek.');
                return;
            }
            window.close();
            return;
        }
        if (state.cb) {
            try {
                window.parent.CKFinder._select(state.cb, url, {fileUrl: url, fileName: name});
            } catch (e) {
                message('Nem sikerült visszaadni a kiválasztott fájlt.');
            }
        }
    }

    function cancel() {
        if (state.funcnum) {
            window.close();
            return;
        }
        if (state.cb) {
            try {
                window.parent.CKFinder._cancel(state.cb);
            } catch (e) {
                /* a dialógust a felhasználó úgyis be tudja zárni */
            }
        }
    }

    // ------------------------------------------------------------------
    // Listázás és rajzolás
    // ------------------------------------------------------------------

    function load(path) {
        state.path = path || '/';
        state.selected = null;
        state.shown = 0;
        $grid.html('<div class="mt-empty">Betöltés…</div>');
        $.ajax({
            url: '/admin/mediatar/list',
            type: 'GET',
            dataType: 'json',
            data: {type: state.type, path: state.path},
            success: function (d) {
                if (!d || !d.ok) {
                    message((d && d.error) || 'Nem sikerült betölteni a mappát.');
                    $grid.html('<div class="mt-empty">–</div>');
                    return;
                }
                message('');
                state.path = d.path;
                state.folders = d.folders || [];
                state.files = d.files || [];
                renderCrumbs();
                render();
                selectStartupFile();
            },
            error: function () {
                message('A szerver nem válaszolt. Lehet, hogy lejárt a bejelentkezés – frissítsd az admin felületet.');
                $grid.html('<div class="mt-empty">–</div>');
            }
        });
    }

    function renderCrumbs() {
        var parts = state.path.replace(/^\/|\/$/g, ''),
            html = '<a data-path="/">' + esc(state.type) + '</a>',
            acc = '/';
        if (parts !== '') {
            var segs = parts.split('/');
            for (var i = 0; i < segs.length; i++) {
                acc += segs[i] + '/';
                html += '<span class="sep">&rsaquo;</span>';
                if (i === segs.length - 1) {
                    html += '<span class="cur">' + esc(segs[i]) + '</span>';
                } else {
                    html += '<a data-path="' + esc(acc) + '">' + esc(segs[i]) + '</a>';
                }
            }
        }
        $crumbs.html(html);
    }

    function filtered() {
        if (!state.filter) {
            return state.files;
        }
        var f = state.filter.toLowerCase();
        return $.grep(state.files, function (x) {
            return x.name.toLowerCase().indexOf(f) !== -1;
        });
    }

    function render() {
        var files = filtered(),
            html = [];

        state.selected = null;

        if (!state.filter) {
            if (state.path !== '/') {
                html.push('<div class="mt-tile folder up" data-path="' + esc(parentPath(state.path)) + '">'
                    + '<div class="box"><span class="icon">&#8598;</span></div>'
                    + '<div class="name">..</div></div>');
            }
            $.each(state.folders, function (i, f) {
                html.push('<div class="mt-tile folder" data-path="' + esc(f.path) + '" data-name="' + esc(f.name) + '">'
                    + (state.writable ? '<span class="fdel" title="Mappa törlése">&#10005;</span>' : '')
                    + '<div class="box"><span class="icon">&#128193;</span></div>'
                    + '<div class="name">' + esc(f.name) + '</div></div>');
            });
        }

        state.shown = Math.min(files.length, PAGE);
        for (var i = 0; i < state.shown; i++) {
            html.push(tileHtml(files[i]));
        }

        $grid.html(html.join(''));
        if (!html.length) {
            $grid.html('<div class="mt-empty">A mappa üres.</div>');
        }
        $more.toggle(files.length > state.shown);
        updateFoot();
    }

    function renderMore() {
        var files = filtered(),
            html = [],
            to = Math.min(files.length, state.shown + PAGE);
        for (var i = state.shown; i < to; i++) {
            html.push(tileHtml(files[i]));
        }
        state.shown = to;
        $grid.append(html.join(''));
        $more.toggle(files.length > state.shown);
    }

    /**
     * Nem-kép fájlnál a kiterjesztés a csempe „ikonja". Az Images típus élesben
     * dokumentumokat is tartalmaz (pdf, doc, xls, …), azoknál egy lejátszás-háromszög
     * félrevezető volna; a videóknál marad a ▶.
     */
    function tileIcon(name) {
        var dot = name.lastIndexOf('.'),
            ext = dot === -1 ? '' : name.substring(dot + 1).toLowerCase();
        if (ext === 'mp4' || ext === 'webm') {
            return '<span class="icon">&#9654;</span>';
        }
        return '<span class="ext">' + esc(ext || '?') + '</span>';
    }

    function tileHtml(f) {
        var inner;
        if (f.thumb) {
            inner = '<img src="' + esc(f.thumb) + '" alt="" loading="lazy"/>';
        } else {
            inner = tileIcon(f.name);
        }
        return '<div class="mt-tile file" data-name="' + esc(f.name) + '" data-url="' + esc(f.url) + '"'
            + ' data-size="' + (f.size || 0) + '" title="' + esc(f.name) + '">'
            + '<div class="box">' + inner + '</div>'
            + '<div class="name">' + esc(f.name) + '</div></div>';
    }

    function parentPath(path) {
        var p = path.replace(/^\/|\/$/g, '');
        if (p === '') {
            return '/';
        }
        var segs = p.split('/');
        segs.pop();
        return segs.length ? '/' + segs.join('/') + '/' : '/';
    }

    /**
     * A shim &sel=-ként átadja az aktuális kép nevét – görgessünk oda és emeljük ki.
     * Kis UX-nyereség a CKFinderhez képest, ami csak a mappáig jutott.
     */
    function selectStartupFile() {
        if (!state.sel) {
            return;
        }
        var want = String(state.sel),
            $t = $grid.find('.mt-tile.file').filter(function () {
                return String($(this).data('name')) === want;
            }).first();
        state.sel = '';
        if (!$t.length) {
            return;
        }
        pick($t);
        var delta = $t.offset().top - $grid.offset().top;
        if (delta < 0 || delta > $grid.height() - 40) {
            $grid.scrollTop($grid.scrollTop() + delta - 20);
        }
    }

    function pick($tile) {
        $grid.find('.mt-tile.sel').removeClass('sel');
        if ($tile && $tile.length) {
            $tile.addClass('sel');
            state.selected = {
                name: String($tile.data('name')),
                url: String($tile.data('url')),
                size: parseInt($tile.data('size'), 10) || 0
            };
        } else {
            state.selected = null;
        }
        updateFoot();
    }

    function updateFoot() {
        var has = !!state.selected;
        // .button('enable'/'disable') és nem .prop('disabled'): a jQuery UI widget a
        // .prop()-ról nem értesül, tehát a gomb tiltva lenne, de engedélyezettnek látszana.
        $('#mtSelect, #mtRename, #mtDelete').button(has ? 'enable' : 'disable');
        if (has) {
            $info.text(state.selected.name + ' — ' + fmtSize(state.selected.size));
        } else {
            $info.text($info.data('default') || '');
        }
    }

    // ------------------------------------------------------------------
    // Feltöltés – kérésenként egy fájl, sorosan
    // ------------------------------------------------------------------

    var queue = [], uploading = false;

    function enqueue(files) {
        if (!state.writable) {
            return;
        }
        for (var i = 0; i < files.length; i++) {
            queue.push(files[i]);
        }
        $queue.addClass('on');
        pump();
    }

    function pump() {
        if (uploading) {
            return;
        }
        var file = queue.shift();
        if (!file) {
            uploading = false;
            load(state.path);
            return;
        }
        uploading = true;

        var $item = $('<div class="mt-qitem"><span class="t"></span><div class="bar"><i></i></div></div>');
        $item.find('.t').text(file.name + ' — ' + fmtSize(file.size));
        $queue.append($item);
        $queue.scrollTop($queue[0].scrollHeight);

        if (state.maxsize && file.size > state.maxsize) {
            $item.addClass('err').find('.t').text(file.name + ' — túl nagy (max. ' + fmtSize(state.maxsize) + ')');
            uploading = false;
            pump();
            return;
        }

        var fd = new FormData();
        fd.append('type', state.type);
        fd.append('path', state.path);
        fd.append('file', file);

        $.ajax({
            url: '/admin/mediatar/upload',
            type: 'POST',
            data: fd,
            dataType: 'json',
            processData: false,
            contentType: false,
            // jQuery 1.11-nek nincs beépített progress eventje – egyedi xhr factory kell.
            xhr: function () {
                var x = $.ajaxSettings.xhr();
                if (x.upload) {
                    x.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            $item.find('.bar i').css('width', Math.round(e.loaded / e.total * 100) + '%');
                        }
                    }, false);
                }
                return x;
            },
            success: function (d) {
                if (d && d.ok) {
                    $item.addClass('ok').find('.t').text(d.name + ' — feltöltve');
                    $item.find('.bar i').css('width', '100%');
                } else {
                    $item.addClass('err').find('.t').text(file.name + ' — ' + ((d && d.error) || 'hiba'));
                }
            },
            error: function () {
                $item.addClass('err').find('.t').text(file.name + ' — a szerver nem válaszolt');
            },
            complete: function () {
                uploading = false;
                pump();
            }
        });
    }

    // ------------------------------------------------------------------
    // Események
    // ------------------------------------------------------------------

    $info.data('default', $info.text());

    // A gombok ugyanúgy néznek ki, mint a program többi része: az admin mindenhol
    // jQuery UI .button()-t hív (termek.js:244, setupform.js:120, …), és a téma
    // ugyanaz a /themes/ui/<uitheme>/jquery-ui.css, amit ez az oldal is betölt.
    // Fontos, hogy ez a load() ELŐTT fusson: az updateFoot() a widget enable/disable
    // metódusát hívja, ami inicializálatlan elemen kivételt dobna.
    $('#mtNewFolder, #mtUploadBtn, #mtMoreBtn, #mtRename, #mtDelete, #mtSelect, #mtCancel').button();
    // A Kiválaszt kiemelve; a Mégse SZÁNDÉKOSAN nem kap ui-priority-secondary-t –
    // annak 0.7-es átlátszósága ugyanúgy nézne ki, mint a tiltott gombok mellette.
    $('#mtSelect').addClass('ui-priority-primary');

    $crumbs.on('click', 'a', function () {
        load(String($(this).data('path')));
    });

    $grid.on('click', '.mt-tile.folder', function (e) {
        if ($(e.target).hasClass('fdel')) {
            return;
        }
        load(String($(this).data('path')));
    });

    $grid.on('click', '.fdel', function (e) {
        e.stopPropagation();
        var name = String($(this).closest('.mt-tile').data('name'));
        askConfirm({
            title: 'Mappa törlése',
            okText: 'Törlés',
            message: 'Biztosan törli a(z) „' + name + '” mappát?\nCsak üres mappa törölhető.'
        }, function () {
            $.ajax({
                url: '/admin/mediatar/deletefolder',
                type: 'POST',
                dataType: 'json',
                data: {type: state.type, path: state.path, name: name},
                success: function (d) {
                    if (d && d.ok) {
                        load(state.path);
                    } else {
                        message((d && d.error) || 'A mappa törlése nem sikerült.');
                    }
                }
            });
        });
    });

    $grid.on('click', '.mt-tile.file', function () {
        pick($(this));
    });

    $grid.on('dblclick', '.mt-tile.file', function () {
        pick($(this));
        finish(state.selected.url, state.selected.name);
    });

    $grid.on('keydown', function (e) {
        if (e.which === 13 && state.selected) {
            finish(state.selected.url, state.selected.name);
        }
    });

    $('#mtMoreBtn').on('click', renderMore);

    $('#mtSelect').on('click', function () {
        if (state.selected) {
            finish(state.selected.url, state.selected.name);
        }
    });

    $('#mtCancel').on('click', cancel);

    $('#mtSearch').on('input keyup', function () {
        state.filter = $(this).val();
        render();
    });

    $('#mtNewFolder').on('click', function () {
        askText({
            title: 'Új mappa',
            label: 'Az új mappa neve:',
            okText: 'Létrehoz'
        }, function (name) {
            $.ajax({
                url: '/admin/mediatar/createfolder',
                type: 'POST',
                dataType: 'json',
                data: {type: state.type, path: state.path, name: name},
                success: function (d) {
                    if (d && d.ok) {
                        load(d.path);
                    } else {
                        message((d && d.error) || 'A mappa létrehozása nem sikerült.');
                    }
                }
            });
        });
    });

    $('#mtUploadBtn').on('click', function () {
        $('#mtFile').val('').trigger('click');
    });

    $('#mtFile').on('change', function () {
        if (this.files && this.files.length) {
            enqueue(this.files);
        }
    });

    $('#mtRename').on('click', function () {
        if (!state.selected) {
            return;
        }
        var cur = state.selected.name;
        withUsage(state.selected.url, function (usage) {
            askText({
                title: 'Átnevezés',
                okText: 'Átnevez',
                label: 'Az új név:',
                value: cur,
                warning: usage.count
                    ? 'Ez a fájl ' + usage.count + ' helyen használatban van ('
                    + usage.where.join(', ') + ').\nAz átnevezés után azok a hivatkozások eltörnek.'
                    : ''
            }, function (name) {
                if (name === cur) {
                    return;
                }
                $.ajax({
                    url: '/admin/mediatar/rename',
                    type: 'POST',
                    dataType: 'json',
                    data: {type: state.type, path: state.path, name: cur, newname: name},
                    success: function (d) {
                        if (d && d.ok) {
                            state.sel = d.name;
                            load(state.path);
                        } else {
                            message((d && d.error) || 'Az átnevezés nem sikerült.');
                        }
                    }
                });
            });
        });
    });

    $('#mtDelete').on('click', function () {
        if (!state.selected) {
            return;
        }
        var name = state.selected.name;
        withUsage(state.selected.url, function (usage) {
            askConfirm({
                title: 'Törlés',
                okText: 'Törlés',
                warning: usage.count
                    ? 'Ez a fájl ' + usage.count + ' helyen használatban van ('
                    + usage.where.join(', ') + ').'
                    : '',
                message: 'Biztosan törli a(z) „' + name + '” fájlt a származékaival együtt?'
            }, function () {
                $.ajax({
                    url: '/admin/mediatar/delete',
                    type: 'POST',
                    dataType: 'json',
                    data: {type: state.type, path: state.path, names: [name]},
                    success: function (d) {
                        if (d && d.ok) {
                            load(state.path);
                        } else {
                            message((d && d.error) || 'A törlés nem sikerült.');
                        }
                    }
                });
            });
        });
    });

    /**
     * "Hol használják?" – a destruktív művelet megerősítése elé. Ha a lekérdezés
     * elbukik, attól még engedjük a műveletet, csak figyelmeztetés nélkül.
     */
    function withUsage(url, fn) {
        $.ajax({
            url: '/admin/mediatar/usage',
            type: 'GET',
            dataType: 'json',
            data: {url: url},
            success: function (d) {
                fn(d && d.count ? d : {count: 0, where: []});
            },
            error: function () {
                fn({count: 0, where: []});
            }
        });
    }

    // --- drag & drop ---

    if (state.writable) {
        var dragdepth = 0;
        $(document)
            .on('dragenter', function (e) {
                e.preventDefault();
                dragdepth++;
                $drop.addClass('on');
            })
            .on('dragover', function (e) {
                e.preventDefault();
            })
            .on('dragleave', function (e) {
                e.preventDefault();
                dragdepth--;
                if (dragdepth <= 0) {
                    dragdepth = 0;
                    $drop.removeClass('on');
                }
            })
            .on('drop', function (e) {
                e.preventDefault();
                dragdepth = 0;
                $drop.removeClass('on');
                var dt = e.originalEvent.dataTransfer;
                if (dt && dt.files && dt.files.length) {
                    enqueue(dt.files);
                }
            });
    }

    // --- indulás ---

    load(state.path);

})(jQuery);
