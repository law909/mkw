$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            const $utolso = $('#utolsoszamlainput'),
                $utolsoeseti = $('#utolsoesetiszamlainput');
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
            }

            // A letöltés másik lapon megy, a válaszát nem látjuk: amíg a szerver el nem menti az új
            // sorszámokat, időnként lekérdezzük őket, hogy a form is a friss értéket mutassa.
            function figyelUtolso() {
                const regi = $utolso.val() + '|' + $utolsoeseti.val();
                let hatravan = 60;
                clearInterval(figyelo);
                figyelo = setInterval(function () {
                    hatravan--;
                    $.getJSON('/admin/xmlszamlaexport/getutolso', function (d) {
                        setUtolso(d);
                        if (hatravan <= 0 || ($utolso.val() + '|' + $utolsoeseti.val()) !== regi) {
                            clearInterval(figyelo);
                        }
                    });
                }, 5000);
            }

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-emailbutton, .js-downloadbutton').button();

            $('.js-downloadbutton').on('click', function (e) {
                e.preventDefault();
                const $this = $(this),
                    $ff = $('#xmlszamlaexport');
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
                        utolsoszamla: $utolso.val(),
                        utolsoesetiszamla: $utolsoeseti.val(),
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
