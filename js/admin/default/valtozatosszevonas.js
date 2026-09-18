$(document).ready(() => {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: () => {
            const $termek = $('.js-termekselect'),
                $termekid = $('.js-termekid'),
                $termekjelzo = $('.js-termekjelzo'),
                $forras = $('.js-forras'),
                $cel = $('.js-cel'),
                $torles = $('.js-forrastorles'),
                $uzenet = $('.js-osszevonasuzenet'),
                $statisztika = $('.js-statisztika'),
                $ok = $('.js-okbutton');
            // az első OK a kimutatást kéri le, a második indítja a megerősítéseket
            let kimutatva = false;

            function ujraValaszt() {
                kimutatva = false;
                $statisztika.empty();
                $uzenet.text('');
            }

            function valtozatFelirat(valtozat) {
                const jelzok = [];
                if (valtozat.inaktiv) {
                    jelzok.push('inaktív');
                }
                if (!valtozat.lathato) {
                    jelzok.push('nem látható');
                }
                return (valtozat.cikkszam ? valtozat.cikkszam + ' - ' : '')
                    + (valtozat.nev || '#' + valtozat.id)
                    + ' (' + 'készlet: ' + valtozat.keszlet + ')'
                    + (jelzok.length ? ' [' + jelzok.join(', ') + ']' : '');
            }

            function toltValtozatok(termekid) {
                $forras.add($cel).empty().append($('<option>').val('').text('válasszon'));
                $termekjelzo.text('');
                ujraValaszt();
                if (!termekid) {
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: '/admin/termekvaltozat/osszevonasvaltozatlista',
                    dataType: 'json',
                    data: {termekid: termekid},
                    success: (d) => {
                        if (!d || !d.ok) {
                            mkwHiba(d && d.error);
                            return;
                        }
                        const termekjelzok = [];
                        if (d.termekinaktiv) {
                            termekjelzok.push('inaktív');
                        }
                        if (d.termekfuggoben) {
                            termekjelzok.push('függőben');
                        }
                        $termekjelzo.text(termekjelzok.length ? '[' + termekjelzok.join(', ') + ']' : '');
                        (d.valtozatok || []).forEach((v) => {
                            const felirat = valtozatFelirat(v);
                            $forras.append($('<option>').val(v.id).text(felirat));
                            $cel.append($('<option>').val(v.id).text(felirat));
                        });
                        if (!(d.valtozatok || []).length) {
                            $uzenet.text('Ennek a terméknek nincs változata.');
                        }
                    },
                    error: (xhr) => mkwHiba(mkwAjaxHibaUzenet(xhr))
                });
            }

            function mutatStatisztika(d) {
                const $tabla = $('<table>').addClass('ui-widget ui-widget-content ui-corner-all mattable-repeatable'),
                    $tbody = $('<tbody>');
                $tbody.append($('<tr>')
                    .append($('<td>').attr('colspan', 2).text(d.forras + '  →  ' + d.cel)));
                (d.sorok || []).forEach((sor) => {
                    $tbody.append($('<tr>')
                        .append($('<td>').text(sor.nev))
                        .append($('<td>').text(sor.db + (sor.utkozes ? ' (' + sor.utkozes + ' ütközik, az törlődik)' : ''))));
                });
                $statisztika.empty().append($tabla.append($tbody));
                $uzenet.text(d.osszes
                    ? 'Összesen ' + d.osszes + ' sort érint. Újabb OK: összevonás.'
                    : 'Egyetlen sort sem érint. Újabb OK: összevonás.');
            }

            function kerdez(cim, szoveg, igenre) {
                $('#dialogcenter')
                    .empty()
                    .append($('<div>').text(szoveg))
                    .dialog({
                        title: cim,
                        resizable: false,
                        modal: true,
                        width: 460,
                        buttons: {
                            'Igen': function () {
                                $(this).dialog('close');
                                igenre();
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
            }

            function osszevon() {
                $ok.button('disable');
                $uzenet.text('Összevonás folyik…');
                $.ajax({
                    type: 'POST',
                    url: '/admin/termekvaltozat/osszevonas',
                    dataType: 'json',
                    data: {
                        forrasid: $forras.val(),
                        celid: $cel.val(),
                        forrastorles: $torles.is(':checked') ? 1 : 0
                    },
                    success: (d) => {
                        $ok.button('enable');
                        if (!d || !d.ok) {
                            $uzenet.text('');
                            mkwHiba(d && d.error);
                            return;
                        }
                        $uzenet.text(d.msg);
                        $statisztika.empty();
                        kimutatva = false;
                        // a forrás eltűnhetett, a készletek pedig biztosan változtak
                        toltValtozatok($termekid.val());
                    },
                    error: (xhr) => {
                        $ok.button('enable');
                        $uzenet.text('');
                        mkwHiba(mkwAjaxHibaUzenet(xhr));
                    }
                });
            }

            function statisztika() {
                $ok.button('disable');
                $uzenet.text('Számolás…');
                $.ajax({
                    type: 'POST',
                    url: '/admin/termekvaltozat/osszevonasstat',
                    dataType: 'json',
                    data: {forrasid: $forras.val(), celid: $cel.val()},
                    success: (d) => {
                        $ok.button('enable');
                        if (!d || !d.ok) {
                            $uzenet.text('');
                            mkwHiba(d && d.error);
                            return;
                        }
                        mutatStatisztika(d);
                        kimutatva = true;
                    },
                    error: (xhr) => {
                        $ok.button('enable');
                        $uzenet.text('');
                        mkwHiba(mkwAjaxHibaUzenet(xhr));
                    }
                });
            }

            $termek.autocomplete({
                minLength: 2,
                autoFocus: true,
                source: '/admin/termekvaltozat/osszevonastermeklista',
                select: function (event, ui) {
                    if (!ui.item) {
                        return;
                    }
                    $termekid.val(ui.item.id);
                    toltValtozatok(ui.item.id);
                }
            });
            $termek.on('input', function () {
                if (!$(this).val()) {
                    $termekid.val('');
                    toltValtozatok('');
                }
            });

            $forras.add($cel).add($torles).on('change', ujraValaszt);

            $ok.on('click', function (e) {
                e.preventDefault();
                if (!$forras.val() || !$cel.val()) {
                    mkwHiba('Mindkét változatot ki kell választani.');
                    return;
                }
                if ($forras.val() === $cel.val()) {
                    mkwHiba('A két változat nem lehet ugyanaz.');
                    return;
                }
                if (!kimutatva) {
                    statisztika();
                    return;
                }
                kerdez('Megerősítés', 'Biztosan összevonja a két változatot?', () => {
                    kerdez(
                        'Utolsó megerősítés',
                        'Tényleg? Az összevonás nem visszafordítható'
                        + ($torles.is(':checked') ? ', és az „erről” változat törlődik' : '') + '.',
                        osszevon
                    );
                });
            }).button();
        }
    }));
});
