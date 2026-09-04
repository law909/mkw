$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopont',
        beforeShow: function () {
            // a vég a kezdetet követi, amíg a felhasználó nem nyúl hozzá
            $('#KezdetEdit').on('change', function () {
                const $veg = $('#VegEdit');
                if (!$veg.val()) {
                    $veg.val($(this).val());
                }
            });

            // az egyszeri és az ismétlődő megadás kizárja egymást. A vég nem kötelező: az átvett
            // rendezvényeknek csak kezdő időpontjuk volt
            function toggleIsmetlodo() {
                const ismetlodo = $('#IsmetlodoCheck').is(':checked');
                $('.js-egyszeriblokk').toggle(!ismetlodo);
                $('.js-ismetlodoblokk').toggle(ismetlodo);
                $('#KezdetEdit').prop('required', !ismetlodo);
                $('#NapEdit, #KezdetidoEdit').prop('required', ismetlodo);
            }

            $('#IsmetlodoCheck').on('change', toggleIsmetlodo);
            toggleIsmetlodo();

            mkwcomp.datumEdit.init('#EarlybirdvegeEdit');
            new ClipboardJS('.js-uidcopy');

            const szerkeszto = mkwcomp.kerdoivSzerkeszto.init($('#KerdoivTab'));
            // témaváltáskor a téma kérdőíve az időpont kérdőíve lesz; ha már van kérdés, rákérdez –
            // meglévő időponton is, de ott csak a mentéssel véglegesül
            $('#IdoponttemaEdit').on('change', function () {
                const temaid = $(this).val();
                if (!temaid) {
                    return;
                }
                $.ajax({
                    url: '/admin/idoponttema/getkerdoiv',
                    type: 'GET',
                    data: {id: temaid},
                    success: function (data) {
                        const adat = JSON.parse(data);
                        if (!adat.kerdesek || !adat.kerdesek.length) {
                            return;
                        }
                        if (!szerkeszto.vanKerdes()) {
                            szerkeszto.betolt(adat);
                            return;
                        }
                        dialogcenter.html('Betöltsem a téma kérdőívét? A mostani kérdéseket lecseréli (csak a mentéssel véglegesül).').dialog({
                            resizable: false,
                            height: 160,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    szerkeszto.betolt(adat);
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            });
        }
    });

    if ($.fn.mattable) {
        const datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter');

        datumtolfilter.datepicker($.datepicker.regional['hu']);
        datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        datumigfilter.datepicker($.datepicker.regional['hu']);
        datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#tipusfilter',
                    '#nevfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter',
                    '#jogahelyszinfilter',
                    '#idopontallapotfilter',
                    '#inaktivfilter',
                    '#ismetlodofilter'
                ]
            },
            tablebody: {
                url: '/admin/idopont/getlistbody',
                onStyle: function () {
                    new ClipboardJS('.js-uidcopy');
                    $('.js-emailkezdes').button();
                }
            },
            karb: mattkarbconfig
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#mattable-body')
            .on('click', '.js-flagcheckbox', function (e) {
                e.preventDefault();
                const $this = $(this);
                $.ajax({
                    url: '/admin/idopont/setflag',
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
            })
            .on('click', '.js-emailkezdes', function (e) {
                e.preventDefault();
                const $gomb = $(this);
                $.ajax({
                    url: '/admin/idopont/email/kezdes',
                    type: 'POST',
                    data: {id: $gomb.data('egyedid')},
                    success: function (data) {
                        const d = JSON.parse(data);
                        dialogcenter.html(d.msg).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
