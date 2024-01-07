function pleaseWait(msg) {
    if (typeof (msg) !== 'string') {
        msg = 'Kérem várjon...';
    }
    $.blockUI({
        message: msg,
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        }
    });
}

$(document).ready(
    function () {

        let dialogcenter = $('#dialogcenter');

        mkwcomp.datumEdit.init('#TolEdit');
        mkwcomp.datumEdit.init('#IgEdit');

        $('#konyveloexportButton').button();
        $('#konyveloexportButton').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                url: '/admin/konyveloexport',
                data: {
                    tol: mkwcomp.datumEdit.getDate('#TolEdit'),
                    ig: mkwcomp.datumEdit.getDate('#IgEdit'),
                },
                success: function (data) {
                    let adat = JSON.parse(data);
                    if (adat.url) {
                        document.location = adat.url;
                    } else {
                        if (adat.msg) {
                            alert(adat.msg);
                        }
                    }
                }
            })
        });

    }
);