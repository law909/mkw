$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            const $adatok = $('.js-adatok');
            const $gombsor = $('.js-gombsor');
            const $uzenet = $('.js-uzenet');
            const $id = $('#RvBizonylatEdit');
            let betoltott = null;

            const szam = (n) => Number(n).toLocaleString('hu-HU', {maximumFractionDigits: 2});
            const sor = (cimke, ertek) => $('<div></div>')
                .append($('<b></b>').text(`${cimke}: `))
                .append($('<span></span>').text(ertek ?? ''));

            function render(d) {
                const b = d.bizonylat;
                $adatok.empty()
                    .append(sor('Bizonylat', `${b.id} (${b.tipus || ''})`))
                    .append(sor('Kelt', b.kelt))
                    .append(sor('Partner', b.partner))
                    .append(sor('Fizetendő', `${szam(b.fizetendo)} ${b.valutanem || ''}`));

                if (d.szarmaztatott.length) {
                    const $lista = $('<ul></ul>');
                    d.szarmaztatott.forEach((s) => $lista.append($('<li></li>').text(`${s.id} (${s.tipus || ''}, ${s.kelt})`)));
                    $adatok.append('<div class="matt-hseparator"></div>')
                        .append($('<div></div>').append($('<b></b>').text(
                            'Figyelem: ebből a bizonylatból élő bizonylat készült (szétbontás vagy továbbalakítás). Visszavétel után a tételei kétszer szerepelhetnek a készletben, a foglalásban vagy a folyószámlán:'
                        )))
                        .append($lista)
                        .append($('<label></label>')
                            .append($('<input type="checkbox" name="szarmaztatott">'))
                            .append(document.createTextNode(' Tudomásul veszem, mégis visszaveszem')));
                }

                $adatok.append('<div class="matt-hseparator"></div>');
                if (!d.penzmozgasok.length) {
                    $adatok.append($('<div></div>').text('Nincs a bizonylatra hivatkozó rontott pénztár- vagy bankbizonylat.'));
                } else {
                    $adatok.append($('<div></div>').append($('<b></b>').text('Visszaállítandó pénzmozgások (a rontással együtt lerontottak):')));
                    d.penzmozgasok.forEach((p) => {
                        const nev = p.tipus === 'penztar' ? 'pénztárbizonylat' : 'bankbizonylat';
                        let szoveg = ` ${p.id} – ${nev}, ${p.kelt}, ebből erre: ${szam(p.sajat)} ${p.valutanem || ''} (összesen ${szam(p.brutto)})`;
                        if (p.masik.length) {
                            szoveg += `; más bizonylatra is szól: ${p.masik.join(', ')}`;
                        }
                        if (p.zarolt) {
                            szoveg += ' – a pénztár időszaka zárt, nem állítható vissza';
                        }
                        $adatok.append($('<div></div>').append($('<label></label>')
                            .append($('<input type="checkbox">').attr('name', `${p.tipus}[]`).val(p.id).prop('disabled', p.zarolt))
                            .append(document.createTextNode(szoveg))));
                    });
                }
                $gombsor.show();
            }

            $('.js-keresbutton').on('click', function (e) {
                e.preventDefault();
                $uzenet.text('');
                $adatok.empty();
                $gombsor.hide();
                betoltott = null;
                $.ajax({
                    type: 'GET',
                    url: $(this).attr('href'),
                    dataType: 'json',
                    data: {id: $id.val()},
                    success: (d) => {
                        if (!d || !d.ok) {
                            $uzenet.text((d && d.error) ? d.error : 'A bizonylat nem kérdezhető le.');
                            return;
                        }
                        betoltott = d.bizonylat.id;
                        render(d);
                    },
                    error: () => $uzenet.text('A bizonylat nem kérdezhető le.')
                });
            }).button();

            $id.on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('.js-keresbutton').trigger('click');
                }
            });

            $('.js-okbutton').on('click', function (e) {
                e.preventDefault();
                const $button = $(this);
                if (!betoltott || $button.button('option', 'disabled')) {
                    return;
                }
                const data = {
                    id: betoltott,
                    penztar: $adatok.find('input[name="penztar[]"]:checked').map((i, el) => el.value).get(),
                    bank: $adatok.find('input[name="bank[]"]:checked').map((i, el) => el.value).get(),
                    szarmaztatott: $adatok.find('input[name="szarmaztatott"]').prop('checked') ? 1 : 0
                };
                $('#dialogcenter').text(`Biztosan visszaveszi a(z) ${betoltott} bizonylat rontását?`).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            const $dia = $(this);
                            $button.button('disable');
                            $.ajax({
                                type: 'POST',
                                url: $button.attr('href'),
                                dataType: 'json',
                                data: data,
                                success: (d) => {
                                    if (!d || !d.ok) {
                                        $uzenet.text((d && d.error) ? d.error : 'A rontás visszavétele nem sikerült.');
                                        return;
                                    }
                                    $adatok.empty();
                                    $gombsor.hide();
                                    betoltott = null;
                                    $uzenet.text(d.msg);
                                },
                                error: () => $uzenet.text('A rontás visszavétele nem sikerült.'),
                                complete: () => {
                                    $button.button('enable');
                                    $dia.dialog('close');
                                }
                            });
                        },
                        'Mégsem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }).button();
        }
    }));
});
