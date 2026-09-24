$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function getPartnerId() {
        return isPartnerAutocomplete() ? $('.js-partnerid').val() : $('#PartnerEdit option:selected').val();
    }

    // shared by the revenue and the sales report, the header tells them apart
    const $header = $('#mattkarb-header');
    const baseUrl = $header.data('baseurl');
    const drawChart = createReportChart('arbevetelchart', '#arbevetelchartnote', Number($header.data('decimals')) || 0);

    // the levels count up to the first empty one; a dimension can be chosen once
    function syncSzintek() {
        const $szintek = $('.js-szint');
        let ures = false;
        $szintek.each(function () {
            $(this).prop('disabled', ures);
            if (!$(this).val()) {
                ures = true;
            }
        });
        const hasznalt = $szintek.filter(':enabled').map(function () {
            return $(this).find('option:selected').data('dim');
        }).get();
        $szintek.each(function () {
            const sajat = $(this).find('option:selected').data('dim');
            $(this).find('option[data-dim]').each(function () {
                const dim = $(this).data('dim');
                $(this).prop('disabled', dim !== sajat && hasznalt.includes(dim));
            });
        });
        // the cross table puts the periods into the columns, so it needs a period level
        const $megjelenites = $('select[name="megjelenites"]');
        if (!hasznalt.includes('idoszak')) {
            $megjelenites.val('lista');
        }
        $megjelenites.prop('disabled', !hasznalt.includes('idoszak'));
        $('select[name="pivotertek"]').toggle($megjelenites.val() === 'kereszttabla');
    }

    function getSzintek() {
        return $('.js-szint:enabled').map(function () {
            return $(this).val();
        }).get().filter((szint) => szint);
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-szint, select[name="megjelenites"]').on('change', syncSzintek);
            syncSzintek();

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            $('.js-partnerautocomplete').autocomplete({
                minLength: 4,
                autoFocus: true,
                source: '/admin/bizonylatfej/getpartnerlist',
                select: (event, ui) => {
                    if (ui.item) {
                        $('input[name="partner"]').val(ui.item.id).change();
                    }
                }
            }).autocompleteRenderer(partnerAutocompleteRenderer);

            $('.js-refresh').on('click', function (e) {
                e.preventDefault();
                const fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                const cimkek = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                const biztipusok = mkwcomp.bizonylattipusFilter.getFilter('input[name="bizonylattipus[]"]');
                $.ajax({
                    url: `${baseUrl}/refresh`,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        datumtipus: $('select[name="datumtipus"]').val(),
                        tol: $('input[name="tol"]').val(),
                        ig: $('input[name="ig"]').val(),
                        ertektipus: $('select[name="ertektipus"]').val(),
                        partner: getPartnerId(),
                        partnertipus: $('select[name="partnertipus"]').val(),
                        partnercimkefilter: cimkek.length > 0 ? cimkek : undefined,
                        uzletkoto: $('select[name="uzletkoto"]').val(),
                        valutanem: $('select[name="valutanem"]').val(),
                        bizonylattipus: biztipusok.length > 0 ? biztipusok : undefined,
                        gyarto: $('select[name="gyarto"]').val(),
                        webshopnum: $('select[name="webshopnum"]').val(),
                        nev: $('input[name="nev"]').val(),
                        szint: getSzintek(),
                        megjelenites: $('select[name="megjelenites"]').val(),
                        pivotertek: $('select[name="pivotertek"]').val(),
                        fafilter: fak.length > 0 ? fak : undefined
                    },
                    success: (d) => {
                        $('#eredmeny').html(d.html);
                        drawChart(d.chart);
                    }
                });
            }).button();

            // the form targets a new window for the export, Enter must not submit it
            $('input[name="nev"]').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('.js-refresh').click();
                }
            });

            $('.js-exportbutton').on('click', function (e) {
                e.preventDefault();
                const $ff = $('#arbevetel');
                $ff.find('input.js-fafilter, input.js-partnercimkefilter').remove();
                for (const id of mkwcomp.termekfaFilter.getFilter('#termekfa')) {
                    $ff.append($('<input type="hidden" class="js-fafilter" name="fafilter[]">').val(id));
                }
                for (const id of mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter')) {
                    $ff.append($('<input type="hidden" class="js-partnercimkefilter" name="partnercimkefilter[]">').val(id));
                }
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
        }
    }));
});
