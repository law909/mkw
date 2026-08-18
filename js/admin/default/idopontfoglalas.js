$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item;
                if (partner) {
                    $('.js-partnerid').val(partner.id);
                    $('.js-ujpartnercb').prop('checked', false);
                }
            }
        };
    }

    function showError(msg) {
        const $dialog = $('#dialogcenter').length ? $('#dialogcenter') : $('<div id="dialogcenter"></div>').appendTo('body');
        $dialog.html(msg).dialog({
            resizable: false,
            height: 160,
            modal: true,
            buttons: {
                'OK': function () {
                    $(this).dialog('close');
                }
            }
        });
    }

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopontfoglalas',
        // a szerver a mentést is elutasítja (409), de a hibaüzenetet itt tudjuk megmutatni
        beforeSubmit: function (arr, form) {
            if (!$('.js-idopontedit').length) {
                return true;
            }
            let ok = true;
            $.ajax({
                url: '/admin/idopontfoglalas/check',
                type: 'GET',
                async: false,
                data: $(form).serialize(),
                success: function (data) {
                    const valasz = JSON.parse(data);
                    if (valasz.result !== 'ok') {
                        ok = false;
                        showError(valasz.msg);
                    }
                }
            });
            return ok;
        },
        beforeShow: function () {
            if (!$('.js-idopontedit').length) {
                return;
            }
            mkwcomp.datumEdit.init('#DatumEdit');
            if (isPartnerAutocomplete()) {
                $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                    .autocompleteRenderer(partnerAutocompleteRenderer);
                // az "Új" jelölő üríti a partner azonosítót, így a beírt név/email alapján keletkezik partner
                $('.js-ujpartnercb').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('.js-partnerid').val('');
                    }
                });
            }

            // egyszeri időpontnál a nap adott, ismétlődőnél a következő olyan napra ugrunk
            $('.js-idopontedit').on('change', function () {
                const $opt = $('option:selected', this),
                    $datum = $('#DatumEdit');
                if (!$opt.val()) {
                    return;
                }
                if ($opt.data('ismetlodo') == 1) {
                    const nap = $opt.data('nap') * 1;
                    if (nap) {
                        const d = new Date();
                        while (((d.getDay() + 6) % 7) + 1 !== nap) {
                            d.setDate(d.getDate() + 1);
                        }
                        $datum.datepicker('setDate', d);
                    }
                } else {
                    $datum.datepicker('setDate', $opt.data('datum'));
                }
            });
        }
    });

    if ($.fn.mattable) {
        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#partnernevfilter',
                    '#partneremailfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter'
                ]
            },
            tablebody: {
                url: '/admin/idopontfoglalas/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
