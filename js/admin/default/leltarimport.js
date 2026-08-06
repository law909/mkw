$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-importbutton').on('click', function (e) {
                e.preventDefault();
                var data = new FormData($('#leltarimport')[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (d) {
                        if (!d) {
                            alert('Kész.');
                        } else {
                            var adat = JSON.parse(d);
                            if (adat.url) {
                                document.location = adat.url;
                            } else {
                                if (adat.msg) {
                                    alert(adat.msg);
                                }
                            }
                        }
                    }
                });
            }).button();

        }
    }));
});