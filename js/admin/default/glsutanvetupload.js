$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
            beforeShow: function () {
                $('.js-upload').on('click', function (e) {
                    e.preventDefault();
                    var data = new FormData($('#mattkarb-form')[0]);
                    $.ajax({
                        type: 'POST',
                        url: '/admin/glsutanvet/upload',
                        processData: false,
                        contentType: false,
                        data: data,
                        success: function (d) {
                            var adat = d ? JSON.parse(d) : null;
                            alert((adat && adat.msg) ? adat.msg : 'Kész.');
                            document.location = '/admin/glsutanvet/viewlist';
                        }
                    });
                }).button();
            }
        })
    );
});
