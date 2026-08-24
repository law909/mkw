$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
            beforeShow: function () {

                function kuld(megerosites) {
                    var data = new FormData($('#mattkarb-form')[0]);
                    if (megerosites) {
                        data.append('megerosites', '1');
                    }
                    $.ajax({
                        type: 'POST',
                        url: '/admin/banktranzakcio/upload',
                        processData: false,
                        contentType: false,
                        data: data,
                        success: function (d) {
                            var adat = (typeof d === 'string') ? (d ? JSON.parse(d) : null) : d;
                            if (!adat) {
                                alert('Kész.');
                                return;
                            }
                            if (adat.duplikaltak && adat.duplikaltak.length) {
                                kerdez(adat);
                                return;
                            }
                            if (adat.url) {
                                document.location = adat.url;
                            } else if (adat.msg) {
                                alert(adat.msg);
                            }
                        },
                        error: function (xhr) {
                            alert('Nem sikerült a feltöltés (' + xhr.status + ' ' + xhr.statusText + ').');
                        }
                    });
                }

                // A tételek a feltöltött fájlból és az adatbázisból jönnek: szövegként rakjuk be,
                // nem html-ként.
                function kerdez(adat) {
                    var $tartalom = $('<div></div>'),
                        $tabla = $('<table class="mattable-table"></table>'),
                        $fej = $('<tr></tr>');

                    $tartalom.append($('<p></p>').text(
                        'A fájl ' + adat.sordb + ' tétele közül ' + adat.duplikaltak.length
                        + ' valószínűleg már be van töltve:'
                    ));
                    ['Dátum', 'Összeg', 'Partner', 'Közlemény', 'Betöltve', 'Bizonylatszám'].forEach(function (c) {
                        $fej.append($('<th></th>').text(c));
                    });
                    $tabla.append($('<thead></thead>').append($fej));
                    var $test = $('<tbody></tbody>');
                    adat.duplikaltak.forEach(function (sor) {
                        var $tr = $('<tr></tr>');
                        [sor.datum, sor.osszeg, sor.partnernev, sor.kozlemeny, sor.importalva, sor.bizonylatszamok]
                            .forEach(function (ertek) {
                                $tr.append($('<td class="cell"></td>').text(ertek === null ? '' : ertek));
                            });
                        $test.append($tr);
                    });
                    $tabla.append($test);
                    $tartalom.append($tabla);
                    $tartalom.append($('<p></p>').text(
                        'A fentiek nem töltődnek be újra. Biztosan feltölti a fájl többi tételét?'
                    ));

                    $('#dialogcenter').empty().append($tartalom).dialog({
                        title: 'Már betöltött tételek a fájlban',
                        resizable: true,
                        width: 900,
                        modal: true,
                        buttons: {
                            'Feltöltés': function () {
                                $(this).dialog('close');
                                kuld(true);
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }

                $('.js-upload').on('click', function (e) {
                    e.preventDefault();
                    kuld(false);
                }).button();
            }
        })
    );
});
