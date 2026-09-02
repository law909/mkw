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

            $('.js-emailbutton, .js-downloadbutton').button();

            $('.js-downloadbutton').on('click', function (e) {
                e.preventDefault();
                const $ff = $('#xmlszamlaexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
                figyelUtolso();
            });

            $('.js-emailbutton').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    data: {
                        utolsoszamla: $utolso.val(),
                        utolsoesetiszamla: $utolsoeseti.val()
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
