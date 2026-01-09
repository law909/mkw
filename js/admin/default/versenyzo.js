$(document).ready(function () {
    var versenyzo = {
        container: '#mattkarb',
        viewUrl: '/admin/versenyzo/getkarb',
        newWindowUrl: '/admin/versenyzo/viewkarb',
        saveUrl: '/admin/versenyzo/save',
        beforeShow: function () {
            $('.js-toflyout').flyout();

            function setupCKFinder(buttonId, inputId) {
                $(buttonId).on('click', function (e) {
                    e.preventDefault();
                    let finder = new CKFinder(),
                        $kepurl = $(inputId),
                        path = $kepurl.val();
                    if (path) {
                        finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $kepurl.val(fileUrl);
                    };
                    finder.popup();
                });
            }

            function setupDelButton(buttonId, inputId) {
                $(buttonId).on('click', function (e) {
                    e.preventDefault();
                    $(inputId).val('');
                });
            }

            setupCKFinder('#KepBrowseButton', '#KepUrlEdit');
            setupDelButton('#KepDelButton', '#KepUrlEdit');

            setupCKFinder('#Kep1BrowseButton', '#KepUrl1Edit');
            setupDelButton('#Kep1DelButton', '#KepUrl1Edit');

            setupCKFinder('#Kep2BrowseButton', '#KepUrl2Edit');
            setupDelButton('#Kep2DelButton', '#KepUrl2Edit');

            setupCKFinder('#Kep3BrowseButton', '#KepUrl3Edit');
            setupDelButton('#Kep3DelButton', '#KepUrl3Edit');

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
            name: 'versenyzo',
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/versenyzo/getlistbody'
            },
            karb: versenyzo
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, versenyzo, {independent: true}));
        }
    }
});
