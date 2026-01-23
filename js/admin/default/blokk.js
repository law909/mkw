$(document).ready(function () {
    var blokk = {
        container: '#mattkarb',
        viewUrl: '/admin/blokk/getkarb',
        newWindowUrl: '/admin/blokk/viewkarb',
        saveUrl: '/admin/blokk/save',
        beforeShow: function () {
            $('.js-toflyout').flyout();
            $('.js-blokkbrowsebutton').on('click', function (e) {
                e.preventDefault();
                let finder = new CKFinder(),
                    $kepurl = $('#' + $(this).attr('data-target')),
                    path = $kepurl.val();
                if (path) {
                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                }
                finder.selectActionFunction = function (fileUrl, data) {
                    $kepurl.val(fileUrl);
                };
                finder.popup();
            });
            $('.js-blokkdelbutton').on('click', function (e) {
                e.preventDefault();
                $('#' + $(this).attr('data-target')).val('');
            });
            $('.js-blokkbrowsebutton, .js-blokkdelbutton').button();

            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit, #Leiras2Edit').ckeditor();
            }
        },
        beforeHide: function () {
            if (!$.browser.mobile) {
                let editor = $('#LeirasEdit').ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
                editor = $('#Leiras2Edit').ckeditorGet();
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
            name: 'blokk',
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/blokk/getlistbody'
            },
            karb: blokk
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                url: '/admin/blokk/setflag',
                type: 'POST',
                data: {
                    id: $this.attr('data-id'),
                    flag: $this.attr('data-flag'),
                    kibe: !$this.is('.ui-state-hover')
                },
                success: function () {
                    $this.toggleClass('ui-state-hover');
                }
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, blokk, {independent: true}));
        }
    }
});
