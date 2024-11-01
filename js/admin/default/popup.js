$(document).ready(function () {

    const showPopupTeszt = () => {
        const popupElement = document.getElementById(`popupteszt`);

        popupElement.style.display = 'block';

        const closeButton = popupElement.querySelector('.shopmodal-close-button');
        closeButton.addEventListener('click', () => {
            popupElement.style.display = 'none';
            $('#popuptesztcontainer').html('');
        });
    };

    var dialogcenter = $('#dialogcenter');

    var popup = {
        container: '#mattkarb',
        viewUrl: '/admin/popup/getkarb',
        newWindowUrl: '/admin/popup/viewkarb',
        saveUrl: '/admin/popup/save',
        beforeShow: function () {
            var alttab = $('#AltalanosTab');
            $('#FoKepDelButton,#FoKepBrowseButton').button();
            if (!$.browser.mobile) {
                $('.js-toflyout').flyout();
            }

            alttab.on('click', '#FoKepDelButton', function (e) {
                e.preventDefault();
                dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
                            $('#KepUrlEdit').val('');
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
                .on('click', '#FoKepBrowseButton', function (e) {
                    e.preventDefault();
                    var finder = new CKFinder(),
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

            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#bodytextEdit').ckeditor();
            }
        },
        beforeHide: function () {
            var editor;
            if (!$.browser.mobile) {
                editor = $('#bodytextEdit').ckeditorGet();
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
            //setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'popup',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
                $('.js-regenerateid, .js-showpopupteszt').button();
            },
            tablebody: {
                url: '/admin/popup/getlistbody',
                onStyle: function () {
                    if (!$.browser.mobile) {
                        $('.js-toflyout').flyout();
                    }
                    $('.js-regenerateid, .js-showpopupteszt').button();
                },
            },
            karb: popup
        });


        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            function doit(succ) {
                var id = $this.attr('data-id'),
                    flag = $this.attr('data-flag'),
                    kibe = !$this.is('.ui-state-hover');
                if (succ) {
                    succ();
                }
                $.ajax({
                    url: '/admin/popup/setflag',
                    type: 'POST',
                    data: {
                        id: id,
                        flag: flag,
                        kibe: kibe
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                    }
                });
            }

            var $this = $(this);
            e.preventDefault();
            doit();
        })
            .on('click', '.js-regenerateid', function (e) {
                e.preventDefault();
                var $this = $(this),
                    id = $this.attr('data-popupid');
                $.ajax({
                    url: '/admin/popup/regenerateid',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function () {
                        $('.mattable-tablerefresh').click();
                    }
                })
            })
            .on('click', '.js-showpopupteszt', function (e) {
                e.preventDefault();
                let $this = $(this),
                    id = $this.attr('data-popupid');
                $.ajax({
                    url: '/admin/popup/getpopupteszt',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        $('#popuptesztcontainer').html(data);
                        showPopupTeszt();
                    }
                })
            });


    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, popup, {independent: true}));
        }
    }
});