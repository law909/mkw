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
                tulaj = $('#tulajdonosEdit option:selected').data('email'),
                szerzok = [
                    $('#szerzo1emailEdit').val(),
                    $('#szerzo2emailEdit').val(),
                    $('#szerzo3emailEdit').val(),
                    $('#szerzo4emailEdit').val(),
                    $('#szerzo5emailEdit').val(),
                    $('#szerzo6emailEdit').val(),
                    $('#szerzo7emailEdit').val(),
                    $('#szerzo8emailEdit').val(),
                    $('#szerzo9emailEdit').val(),
                    $('#szerzo10emailEdit').val(),
                ],
                egyebszerzok = $('#egyebszerzokEdit').val(),
                opponens = $('#opponensemailEdit').val()
            ;

            if (b1) {
                b1 = b1.toLowerCase();
            }
            if (b2) {
                b2 = b2.toLowerCase();
            }
            if (b3) {
                b3 = b3.toLowerCase();
            }
            if ((b1 && tulaj === b1) || (b2 && tulaj === b2) || (b3 && tulaj === b3)) {
                dialogcenter.html('A bíráló nem lehet tulajdonos').dialog({
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
            if ((b1 && opponens === b1) || (b2 && opponens === b2) || (b3 && opponens === b3)) {
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
            if (egyebszerzok.toLowerCase().includes(b1) || egyebszerzok.toLowerCase().includes(b2) || egyebszerzok.toLowerCase().includes(b3)) {
                dialogcenter.html('A bíráló nem lehet egyéb szerző').dialog({
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
                    '#pluszbiralokellfilter',
                    '#elsobiralokellfilter',
                    '#masodikbiralokellfilter',
                    '#temakor1filter',
                    '#tipusfilter',
                    '#teremfilter',
                    '#temafilter',
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