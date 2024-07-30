$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo(ul);
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    pi.change();
                }
            }
        };
    }

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

            $('.js-refresh')
                .on('click', function () {

                    var fak, fafilter, biztipusfilter, partnercimkefilter, partner,
                        datumtipus, tol, ig, raktar, gyarto, uzletkoto, fizmod,
                        forgalomfilter, ertektipus, arsav, nevfilter, nyelv,
                        bizonylatstatusz, bizonylatstatuszcsoport, csoportositas,
                        keszletkell, csakfoglalas;
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        fafilter = fak;
                    }

                    partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');

                    biztipusfilter = mkwcomp.bizonylattipusFilter.getFilter('input[name="bizonylattipus[]"]');

                    datumtipus = $('select[name="datumtipus"]').val();
                    tol = $('input[name="tol"]').val();
                    ig = $('input[name="ig"]').val();
                    raktar = $('select[name="raktar"]').val();
                    gyarto = $('select[name="gyarto"]').val();
                    uzletkoto = $('select[name="uzletkoto"]').val();
                    fizmod = $('select[name="fizmod"]').val();
                    forgalomfilter = $('select[name="forgalomfilter"]').val();
                    ertektipus = $('select[name="ertektipus"]').val();
                    arsav = $('select[name="arsav"]').val();
                    nevfilter = $('input[name="nevfilter"]').val();
                    nyelv = $('select[name="nyelv"]').val();
                    bizonylatstatusz = $('select[name="bizonylatstatusz"]').val();
                    bizonylatstatuszcsoport = $('select[name="bizonylatstatuszcsoport"]').val();
                    csoportositas = $('select[name="csoportositas"]').val();
                    keszletkell = $('input[name="keszletkell"]').prop('checked');
                    csakfoglalas = $('input[name="csakfoglalas"]').prop('checked');
                    if (isPartnerAutocomplete()) {
                        partner = $('.js-partnerid').val();
                    } else {
                        partner = $('#PartnerEdit option:selected').val();
                    }

                    $.ajax({
                        url: '/admin/bizonylattetellista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: datumtipus,
                            tol: tol,
                            ig: ig,
                            raktar: raktar,
                            gyarto: gyarto,
                            uzletkoto: uzletkoto,
                            partner: partner,
                            fizmod: fizmod,
                            forgalomfilter: forgalomfilter,
                            ertektipus: ertektipus,
                            arsav: arsav,
                            nevfilter: nevfilter,
                            nyelv: nyelv,
                            fafilter: fafilter,
                            bizonylatstatusz: bizonylatstatusz,
                            bizonylatstatuszcsoport: bizonylatstatuszcsoport,
                            bizonylattipus: biztipusfilter,
                            partnercimkefilter: partnercimkefilter,
                            csoportositas: csoportositas,
                            keszletkell: keszletkell,
                            csakfoglalas: csakfoglalas
                        },
                        success: function (d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();

            $('.js-exportbutton').on('click', function (e) {
                var fak, fafilter, $ff, partnercimkefilter;
                e.preventDefault();
                $ff = $('#bizonylattetel');

                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    fafilter = fak;
                }
                if (fafilter) {
                    arrayLength = fafilter.length;
                    for (var i = 0; i < arrayLength; i++) {
                        $ff.append('<input id="FaFilter" type="hidden" name="fafilter[]" value="' + fafilter[i] + '">');
                    }
                }
//                $('#FaFilter').val(fafilter);

                partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                if (partnercimkefilter) {
                    arrayLength = partnercimkefilter.length;
                    for (var i = 0; i < arrayLength; i++) {
                        $ff.append('<input id="PartnerCimkeFilter" type="hidden" name="partnercimkefilter[]" value="' + partnercimkefilter[i] + '">');
                    }
                }
//                $('#PartnerCimkeFilter').val(partnercimkefilter);

                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-print').on('click', function (e) {
                var fak, fafilter, $ff, partnercimkefilter;
                e.preventDefault();
                $ff = $('#bizonylattetel');

                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    fafilter = fak;
                }
                if (fafilter) {
                    arrayLength = fafilter.length;
                    for (var i = 0; i < arrayLength; i++) {
                        $ff.append('<input id="FaFilter" type="hidden" name="fafilter[]" value="' + fafilter[i] + '">');
                    }
                }
                //$('#FaFilter').val(fafilter);
                partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                if (partnercimkefilter) {
                    arrayLength = partnercimkefilter.length;
                    for (var i = 0; i < arrayLength; i++) {
                        $ff.append('<input id="PartnerCimkeFilter" type="hidden" name="partnercimkefilter[]" value="' + partnercimkefilter[i] + '">');
                    }
                }
                //$('#PartnerCimkeFilter').val(partnercimkefilter);

                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    });
});