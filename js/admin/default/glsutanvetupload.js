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
                        // a válasz application/json, ezért a jQuery már objektumot ad
                        success: function (d) {
                            var adat = (typeof d === 'string') ? (d ? JSON.parse(d) : null) : d;
                            alert((adat && adat.msg) ? adat.msg : 'Kész.');
                            document.location = '/admin/glsutanvet/viewlist';
                        },
                        error: function (xhr) {
                            alert('Nem sikerült a feltöltés (' + xhr.status + ' ' + xhr.statusText + ').');
                        }
                    });
                }).button();
            }
        })
    );
});
