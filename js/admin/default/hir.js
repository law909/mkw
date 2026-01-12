$(document).ready(function () {
    var hir = {
        container: '#mattkarb',
        viewUrl: '/admin/hir/getkarb',
        newWindowUrl: '/admin/hir/viewkarb',
        saveUrl: '/admin/hir/save',
        beforeShow: function () {
            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#SzovegEdit').ckeditor();
                $('#LeadEdit').ckeditor();
            }
            var dedit = $('#DatumEdit');
            dedit.datepicker($.datepicker.regional['hu']);
            dedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            dedit.datepicker('setDate', dedit.attr('data-datum'));
            dedit = $('#ElsoDatumEdit');
            dedit.datepicker($.datepicker.regional['hu']);
            dedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            dedit.datepicker('setDate', dedit.attr('data-datum'));
            dedit = $('#UtolsoDatumEdit');
            dedit.datepicker($.datepicker.regional['hu']);
            dedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            dedit.datepicker('setDate', dedit.attr('data-datum'));

            $('#KepBrowseButton').on('click', function (e) {
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
            $('#KepDelButton').on('click', function (e) {
                e.preventDefault();
                $('#KepUrlEdit').val('');
            });
            $('.js-kepbrowsebutton,.js-kepdelbutton').button();

        },
        beforeHide: function () {
            if (!$.browser.mobile) {
                editor = $('#SzovegEdit').ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
                editor = $('#LeadEdit').ckeditorGet();
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
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/hir/getlistbody'
            },
            karb: hir
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                url: '/admin/hir/setlathato',
                type: 'POST',
                data: {
                    id: $this.attr('data-id'),
                    kibe: !$this.is('.ui-state-hover')
                },
                success: function () {
                    $this.toggleClass('ui-state-hover');
                }
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, hir, {independent: true}));
        }
    }
});