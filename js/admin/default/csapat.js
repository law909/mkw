$(document).ready(function () {
    var csapat = {
        container: '#mattkarb',
        viewUrl: '/admin/csapat/getkarb',
        newWindowUrl: '/admin/csapat/viewkarb',
        saveUrl: '/admin/csapat/save',
        beforeShow: function () {
            $('.js-toflyout').flyout();
            $('#LogoKepBrowseButton').on('click', function (e) {
                e.preventDefault();
                let finder = new CKFinder(),
                    $kepurl = $('#LogoUrlEdit'),
                    path = $kepurl.val();
                if (path) {
                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                }
                finder.selectActionFunction = function (fileUrl, data) {
                    $kepurl.val(fileUrl);
                };
                finder.popup();
            });
            $('#LogoKepDelButton').on('click', function (e) {
                e.preventDefault();
                $('#LogoUrlEdit').val('');
            });
            $('#FoKepBrowseButton').on('click', function (e) {
                e.preventDefault();
                let finder = new CKFinder(),
                    $kepurl = $('#KepUrlEdit'),
                    path = $kepurl.val();
                if (path) {
                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                }
                finder.selectActionFunction = function (fileUrl, data) {
                    $kepurl.val(fileUrl);
                };
                finder.popup();
            });
            $('#FoKepDelButton').on('click', function (e) {
                e.preventDefault();
                $('#KepUrlEdit').val('');
            });
            $('.js-kepbrowsebutton,.js-kepdelbutton').button();
            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').ckeditor();
            }
        },
        beforeHide: function () {
            if (!$.browser.mobile) {
                let editor = $('#LeirasEdit').ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
            }
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'csapat',
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/csapat/getlistbody'
            },
            karb: csapat
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, csapat, {independent: true}));
        }
    }
});
