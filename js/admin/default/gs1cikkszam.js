$(document).ready(() => {

    const download = (base64, filename) => {
        const bytes = Uint8Array.from(atob(base64), (c) => c.charCodeAt(0));
        const url = URL.createObjectURL(new Blob([bytes], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}));
        const $link = $('<a></a>').attr({href: url, download: filename}).appendTo('body');
        $link[0].click();
        $link.remove();
        URL.revokeObjectURL(url);
    };

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: () => {
            const $uzenet = $('.js-frissitesuzenet');

            $('.js-frissitesbutton').on('click', function (e) {
                e.preventDefault();
                $uzenet.text('Feldolgozás…');
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: new FormData($('#gs1cikkszam')[0]),
                    success: (d) => {
                        if (!d || !d.ok) {
                            $uzenet.text((d && d.error) ? d.error : 'A frissítés nem sikerült.');
                            return;
                        }
                        $uzenet.text(d.msg);
                        download(d.file, d.filename);
                        if (d.hibak && d.hibak.length) {
                            // a sorok a feltöltött fájlból jönnek: szövegként, nem html-ként
                            const $lista = $('<div></div>');
                            d.hibak.forEach((sor) => {
                                $lista.append($('<div></div>').text(sor));
                            });
                            $('#dialogcenter').empty().append($lista).dialog({
                                title: `Megjegyzések (${d.hibak.length})`,
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
                    error: () => {
                        $uzenet.text('A frissítés nem sikerült.');
                    }
                });
            }).button();
        }
    }));
});
