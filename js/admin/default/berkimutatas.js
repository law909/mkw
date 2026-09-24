$(document).ready(function () {

    const baseUrl = $('#mattkarb-header').data('baseurl');
    const drawChart = createReportChart('berkimutataschart', '#berkimutatasnote', 0);

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            const grouping = initReportGrouping(baseUrl);

            $('.js-refresh').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: `${baseUrl}/refresh`,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        tol: $('input[name="tol"]').val(),
                        ig: $('input[name="ig"]').val(),
                        dolgozo: $('select[name="dolgozo"]').val(),
                        szint: grouping.getSzintek(),
                        megjelenites: $('select[name="megjelenites"]').val()
                    },
                    success: (d) => {
                        $('#eredmeny').html(d.html);
                        drawChart(d.chart);
                    }
                });
            }).button();

            $('.js-exportbutton').on('click', function (e) {
                e.preventDefault();
                $('#berkimutatas').attr('action', $(this).attr('href')).submit();
            }).button();
        }
    }));
});
