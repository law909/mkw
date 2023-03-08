$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    function szimpoziumShow() {
        let sb = document.getElementById('tipusEdit'),
            so = sb.options[sb.selectedIndex];
        if (so.dataset['szimpozium']) {
            document.querySelectorAll('.onlyszimpozium').forEach((elem) => {
                elem.classList.remove('hidden');
            });
        } else {
            document.querySelectorAll('.onlyszimpozium').forEach((elem) => {
                elem.classList.add('hidden');
            });
        }
    }

    let mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/mptngyszakmaianyag/getkarb',
        newWindowUrl: '/admin/mptngyszakmaianyag/viewkarb',
        saveUrl: '/admin/mptngyszakmaianyag/save',
        beforeShow: function () {
            szimpoziumShow();
            let sb = document.getElementById('tipusEdit');
            sb.addEventListener('change', (event) => {
                szimpoziumShow();
            })
        },
        beforeSerialize: function (form, opt) {
            let b1 = $('#biralo1Edit option:selected').data('email'),
                b2 = $('#biralo2Edit option:selected').data('email'),
                b3 = $('#biralo3Edit option:selected').data('email'),
                szerzo1 = $('#szerzo1emailEdit').val(),
                szerzok = [
                    $('#szerzo2emailEdit').val(),
                    $('#szerzo3emailEdit').val(),
                    $('#szerzo4emailEdit').val()
                ],
                szerzo5 = $('#szerzo5emailEdit').val();

            if ((b1 && szerzo1 === b1) || (b2 && szerzo1 === b2) || (b3 && szerzo1 === b3)) {
                dialogcenter.html('A bíráló nem lehet első szerző').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Ok': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }
            if ((b1 && szerzo5 === b1) || (b2 && szerzo5 === b2) || (b3 && szerzo5 === b3)) {
                dialogcenter.html('A bíráló nem lehet opponens').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Ok': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }
            if (szerzok.find(el => (b1 && el === b1) || (b2 && el === b2) || (b3 && el === b3))) {
                dialogcenter.html('A bíráló nem lehet szerző').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Ok': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
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
    }

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#cimfilter',
                    '#tulajfilter',
                    '#bekuldvefilter',
                    '#biralatkeszfilter',
                    '#konferencianszerepelhetfilter',
                    '#elsoszerzofilter',
                    '#szerzofilter',
                    '#opponensfilter',
                    '#idfilter',
                ]
            },
            tablebody: {
                url: '/admin/mptngyszakmaianyag/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').attr('checked', $(this).attr('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mattkarbconfig, {independent: true}));
        }
    }
});