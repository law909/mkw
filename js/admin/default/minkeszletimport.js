$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            const $uzenet = $('.js-importuzenet');

            $('.js-importbutton').on('click', function (e) {
                e.preventDefault();
                $uzenet.text('');
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: new FormData($('#minkeszletimport')[0]),
                    success: function (d) {
                        if (!d || !d.ok) {
                            $uzenet.text((d && d.error) ? d.error : 'Az import nem sikerült.');
                            return;
                        }
                        $uzenet.text(d.msg);
                        if (d.hibak && d.hibak.length) {
                            // a sorok a feltöltött fájlból jönnek: szövegként, nem html-ként
                            const $lista = $('<div></div>');
                            d.hibak.forEach(function (sor) {
                                $lista.append($('<div></div>').text(sor));
                            });
                            $('#dialogcenter').empty().append($lista).dialog({
                                title: 'Az import megjegyzései',
                                resizable: true,
                                width: 600,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    },
                    error: function () {
                        $uzenet.text('Az import nem sikerült.');
                    }
                });
            }).button();
        }
    }));
});
