$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-galadoxfordimport, .js-galadcgmimport, .js-galadproductimport, .js-galadsuomyimport, .js-szinimport, .js-meretimport, .js-orszagimport, .js-galadpartnerimport').on('click', function (e) {
                e.preventDefault();
                var data = new FormData($('#mattkarb-form')[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (msg) {
                        if (msg) {
                            alert(msg);
                        }
                    }
                });
            }).button();

            $('.js-galadkeszletimport').on('click', function (e) {
                e.preventDefault();
                const eredmeny = $('#galadkeszlet-eredmeny');
                eredmeny.text('Feldolgozás folyamatban…');
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: new FormData($('#mattkarb-form')[0]),
                    success: (msg) => eredmeny.empty().append(galadKeszletRiport(msg)),
                    error: () => eredmeny.text('Az import nem futott le.')
                });
            }).button();

            $('.js-termekfabutton').on('click', function (e) {
                var edit = $(this);
                e.preventDefault();
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            dialogcenter.jstree('get_selected').each(function () {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
                                edit.buttonLabel(treenode.text());
                            });
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }).button();
            $('input[name="dbtol"]').on('change', function (e) {
                if ($(this).val() * 1 !== 0) {
                    $('input[name="deltondownload"]').prop('checked', false);
                }
            });
        },
    }));
});

/**
 * Az "Előző program készlete" import válaszának megjelenítése: raktáranként a bevét
 * bizonylatszáma, alatta az új raktárak és a kimaradt sorok naplója.
 */
function galadKeszletRiport(msg) {
    if (!msg || !msg.ok) {
        return $('<div>').text((msg && msg.error) || 'Az import nem futott le.');
    }

    const doboz = $('<div>');
    doboz.append($('<p>').text(msg.uzenet));

    if (msg.bizonylatok && msg.bizonylatok.length) {
        const tabla = $('<table>');
        tabla.append($('<tr>').append(
            $('<th>').text('Raktár'),
            $('<th>').text('Bizonylat'),
            $('<th>').text('Tétel'),
            $('<th>').text('Mennyiség')
        ));
        msg.bizonylatok.forEach((b) => tabla.append($('<tr>').append(
            $('<td>').text(b.raktar),
            $('<td>').text(b.bizonylatszam),
            $('<td>').text(b.tetel),
            $('<td>').text(b.mennyiseg)
        )));
        doboz.append(tabla);
    }

    if (msg.ujraktarak && msg.ujraktarak.length) {
        doboz.append($('<p>').text(`Új raktár: ${msg.ujraktarak.join(', ')}`));
    }

    if (msg.kimaradt) {
        const sor = $('<p>').text(`${msg.kimaradt} sor kimaradt. `);
        if (msg.naplo) {
            sor.append($('<a>').attr('href', msg.naplo).text('Napló letöltése'));
        }
        doboz.append(sor);
    } else {
        doboz.append($('<p>').text('Minden sor betöltődött.'));
    }

    return doboz;
}
