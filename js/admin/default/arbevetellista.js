$(document).ready(function () {

    let chart = null;

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function getPartnerId() {
        return isPartnerAutocomplete() ? $('.js-partnerid').val() : $('#PartnerEdit option:selected').val();
    }

    const huf = new Intl.NumberFormat('hu-HU', {maximumFractionDigits: 0});

    function drawChart(data) {
        if (chart) {
            chart.destroy();
            chart = null;
        }
        if (typeof Chart === 'undefined' || !data.labels.length) {
            return;
        }
        chart = new Chart(document.getElementById('arbevetelchart'), {
            type: 'bar',
            data: {labels: data.labels, datasets: data.datasets},
            plugins: [chartValueLabels],
            options: {
                maintainAspectRatio: false,
                layout: {padding: {top: 18}},
                scales: {
                    x: {stacked: data.stacked},
                    y: {stacked: data.stacked, ticks: {callback: (value) => huf.format(value)}}
                },
                plugins: {
                    legend: {display: data.legend},
                    valueLabels: {format: (value) => huf.format(value)},
                    tooltip: {callbacks: {label: (ctx) => `${ctx.dataset.label}: ${huf.format(ctx.parsed.y)} Ft`}}
                }
            }
        });
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

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
                $.ajax({
                    url: '/admin/arbevetellista/refresh',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        datumtipus: $('select[name="datumtipus"]').val(),
                        tol: $('input[name="tol"]').val(),
                        ig: $('input[name="ig"]').val(),
                        ertektipus: $('select[name="ertektipus"]').val(),
                        partner: getPartnerId(),
                        partnertipus: $('select[name="partnertipus"]').val(),
                        gyarto: $('select[name="gyarto"]').val(),
                        webshopnum: $('select[name="webshopnum"]').val(),
                        idoszakcsoport: $('select[name="idoszakcsoport"]').val(),
                        gyartocsoport: $('input[name="gyartocsoport"]').prop('checked') ? 1 : 0,
                        webshopcsoport: $('input[name="webshopcsoport"]').prop('checked') ? 1 : 0,
                        fafilter: fak.length > 0 ? fak : undefined
                    },
                    success: (d) => {
                        $('#eredmeny').html(d.html);
                        drawChart(d.chart);
                    }
                });
            }).button();

            $('.js-exportbutton').on('click', function (e) {
                e.preventDefault();
                const $ff = $('#arbevetel');
                $ff.find('input.js-fafilter').remove();
                for (const id of mkwcomp.termekfaFilter.getFilter('#termekfa')) {
                    $ff.append($('<input type="hidden" class="js-fafilter" name="fafilter[]">').val(id));
                }
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
        }
    }));
});
