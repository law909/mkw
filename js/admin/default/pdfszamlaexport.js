$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            const $utolso = $('#utolsoszamlainput'),
                $utolsoeseti = $('#utolsoesetiszamlainput'),
                $utolsoeloleg = $('#utolsoelolegszamlainput');
            let figyelo = null;

            function setUtolso(d) {
                if (!d) {
                    return;
                }
                if (d.utolsoszamla) {
                    $utolso.val(d.utolsoszamla);
                }
                if (d.utolsoesetiszamla) {
                    $utolsoeseti.val(d.utolsoesetiszamla);
                }
                if (d.utolsoelolegszamla) {
                    $utolsoeloleg.val(d.utolsoelolegszamla);
                }
            }

            // A letöltés másik lapon megy, a válaszát nem látjuk: amíg a szerver el nem menti az új
            // sorszámokat, időnként lekérdezzük őket, hogy a form is a friss értéket mutassa.
            function figyelUtolso() {
                const allapot = () => [$utolso.val(), $utolsoeseti.val(), $utolsoeloleg.val()].join('|');
                const regi = allapot();
                let hatravan = 60;
                clearInterval(figyelo);
                figyelo = setInterval(function () {
                    hatravan--;
                    $.getJSON('/admin/pdfszamlaexport/getutolso', function (d) {
                        setUtolso(d);
                        if (hatravan <= 0 || allapot() !== regi) {
                            clearInterval(figyelo);
                        }
                    });
                }, 5000);
            }

            const tipus = () => $('.js-tipus:checked').val();
            $('.js-tipus').on('change', function () {
                $('.js-tipus-szamla').toggle(tipus() === 'szamla');
                $('.js-tipus-elolegszamla').toggle(tipus() === 'elolegszamla');
            });

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-emailbutton, .js-downloadbutton').button();

            $('.js-downloadbutton').on('click', function (e) {
                e.preventDefault();
                const $this = $(this),
                    $ff = $('#pdfszamlaexport');
                $('input[name="szures"]', $ff).val($this.data('szures'));
                $ff.attr('action', $this.attr('href'));
                $ff.submit();
                // a sorszámokat csak a bizonylatszám szerinti feladás lépteti
                if ($this.data('szures') === 'szam') {
                    figyelUtolso();
                }
            });

            $('.js-emailbutton').on('click', function (e) {
                e.preventDefault();
                const $this = $(this);
                $.ajax({
                    type: 'POST',
                    url: $this.attr('href'),
                    data: {
                        szures: $this.data('szures'),
                        tipus: tipus(),
                        utolsoszamla: $utolso.val(),
                        utolsoesetiszamla: $utolsoeseti.val(),
                        utolsoelolegszamla: $utolsoeloleg.val(),
                        tol: $('#TolEdit').val(),
                        ig: $('#IgEdit').val()
                    },
                    success: function (d) {
                        if (!d) {
                            alert('Kész.');
                            return;
                        }
                        const adat = JSON.parse(d);
                        setUtolso(adat);
                        if (adat.url) {
                            document.location = adat.url;
                        } else if (adat.msg) {
                            alert(adat.msg);
                        }
                    }
                });
            });

        }
    }));
});
