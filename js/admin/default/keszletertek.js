$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#DatumEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            // a két gomb ugyanazt az űrlapot küldi, csak más útvonalra
            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                e.preventDefault();
                const ff = $('#keszletertek');
                const fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                $('input[name="fafilter"]').val(fak.length > 0 ? fak : '');
                ff.attr('action', $(this).attr('href'));
                ff.submit();
            }).button();

            $('.js-recalcbutton').on('click', function (e) {
                e.preventDefault();
                const gomb = $(this);
                const eredmeny = $('#fifoeredmeny');
                gomb.button('disable');
                eredmeny.text('Számolás folyamatban…');
                $.ajax({
                    type: 'POST',
                    url: gomb.attr('href'),
                    dataType: 'json',
                    success: (msg) => {
                        if (!msg || !msg.ok) {
                            eredmeny.text((msg && msg.error) || 'A számítás nem futott le.');
                            return;
                        }
                        let szoveg = msg.uzenet;
                        if (msg.fedezetlen) {
                            szoveg += ` ${msg.fedezetlen} fedezetlen csoport.`;
                        }
                        if (msg.becsult) {
                            szoveg += ` ${msg.becsult} csoportban becsült ár szerepel.`;
                        }
                        eredmeny.text(szoveg);
                        $('#fifoszamitva').text(`Utolsó számítás: ${msg.szamitva}`);
                    },
                    error: () => eredmeny.text('A számítás nem futott le.'),
                    complete: () => gomb.button('enable')
                });
            }).button();

        }
    }));
});
