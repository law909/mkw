$(document).ready(function () {

    const drawChart = createReportChart('berkimutataschart', '#berkimutatasnote', 0);

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-refresh').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/berkimutatas/refresh',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        tol: $('input[name="tol"]').val(),
                        ig: $('input[name="ig"]').val(),
                        dolgozo: $('select[name="dolgozo"]').val(),
                        idoszakcsoport: $('select[name="idoszakcsoport"]').val(),
                        dolgozocsoport: $('input[name="dolgozocsoport"]').prop('checked') ? 1 : 0,
                        berjogcimcsoport: $('input[name="berjogcimcsoport"]').prop('checked') ? 1 : 0
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
