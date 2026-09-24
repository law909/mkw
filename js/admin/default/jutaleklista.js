$(document).ready(function () {

    const $header = $('#mattkarb-header');
    const baseUrl = $header.data('baseurl');
    const drawChart = createReportChart('jutalekchart', '#jutalekchartnote', Number($header.data('decimals')) || 0);

    function getCimkek() {
        return $('.js-cimkefilter').filter('.ui-state-hover').map(function () {
            return $(this).attr('data-id');
        }).get();
    }

    function submitForm(url) {
        const $ff = $('#jutalek');
        let $c = $('input[name="cimkefilter"]');
        if ($c.length == 0) {
            $ff.append('<input type="hidden" name="cimkefilter">');
            $c = $('input[name="cimkefilter"]');
        }
        const cimkek = getCimkek();
        $c.val(cimkek.length > 0 ? cimkek : '');
        $ff.attr('action', url);
        $ff.submit();
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            const grouping = initReportGrouping(baseUrl);

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            const getParams = () => {
                const cimkek = getCimkek();
                return {
                    tol: $('input[name="tol"]').val(),
                    ig: $('input[name="ig"]').val(),
                    uzletkoto: $('select[name="uzletkoto"]').val(),
                    belso: $('input[name="belso"]').prop('checked') ? 1 : undefined,
                    cimkefilter: cimkek.length > 0 ? cimkek : undefined,
                    szint: grouping.getSzintek(),
                    megjelenites: $('select[name="megjelenites"]').val()
                };
            };

            const refresh = () => $.ajax({
                url: `${baseUrl}/refresh`,
                type: 'GET',
                dataType: 'json',
                data: getParams()
            }).then((d) => {
                $('#eredmeny').html(d.html);
                drawChart(d.chart);
            });

            $('.js-refresh').on('click', function (e) {
                e.preventDefault();
                refresh();
            }).button();

            initReportPdfButton(`${baseUrl}/pdf`, refresh, getParams, drawChart);

            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                e.preventDefault();
                submitForm($(this).attr('href'));
            }).button();

        }
    }));
});
