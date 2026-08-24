$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    pi.change();
                }
            }
        };
    }

    // A kiválasztott termék a rejtett termekid mezőben él, a fejléc és a változatlista pedig
    // követi. Konkrét termékkel megnyitva ezek a részek nincsenek is a képernyőn.
    function setTermek(termekid, cikkszam, termeknev, valtozatlista, valtozatid) {
        $('input[name="termekid"]').val(termekid || 0);
        $('.js-termekfejlec').text(termekid ? ' - ' + (cikkszam || '') + ' ' + (termeknev || '') : '');
        var $valtozat = $('select[name="valtozat"]');
        $valtozat.empty().append($('<option></option>').attr('value', '0').text('válasszon'));
        (valtozatlista || []).forEach(function (v) {
            $valtozat.append($('<option></option>').attr('value', v.id).text(v.caption));
        });
        $valtozat.val(valtozatid || '0');
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function (event, ui) {
                var termek = ui.item;
                if (!termek) {
                    return;
                }
                $.ajax({
                    url: '/admin/termekkarton/valtozatlista',
                    type: 'GET',
                    dataType: 'json',
                    data: {termekid: termek.id},
                    success: function (valtozatok) {
                        setTermek(termek.id, termek.cikkszam, termek.value, valtozatok, termek.valtozat);
                    }
                });
            }
        };
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                .autocompleteRenderer(termekAutocompleteRenderer);

            // Egyedi azonosítóból megkeressük a bizonylattételt, és beállítjuk a termékét és a
            // változatát is – utána a szűrő azonosítóba is bekerül, hogy a karton arra szűrjön.
            $('.js-egyediazonositokeres').on('click', function (e) {
                e.preventDefault();
                var azonosito = $('.js-egyediazonositokereso').val(),
                    $uzenet = $('.js-egyediazonositouzenet');
                $uzenet.text('');
                $.ajax({
                    url: '/admin/termekkarton/egyediazonositokereses',
                    type: 'GET',
                    dataType: 'json',
                    data: {egyediazonosito: azonosito},
                    success: function (res) {
                        if (!res || !res.ok) {
                            $uzenet.text((res && res.error) ? res.error : 'A keresés nem sikerült.');
                            return;
                        }
                        setTermek(res.termekid, res.cikkszam, res.termeknev, res.valtozatlista, res.valtozatid);
                        $('.js-termekselect').val(res.cikkszam + ' ' + res.termeknev);
                        $('.js-egyediazonositoszuro').val(azonosito);
                        $('.js-refresh').click();
                    },
                    error: function () {
                        $uzenet.text('A keresés nem sikerült.');
                    }
                });
            }).button();

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocompleteRenderer(partnerAutocompleteRenderer);

            // Egyedi azonosító szűrő autocomplete: a termékhez (és kiválasztott változatához)
            // tartozó, bizonylattételekben szereplő azonosítókat ajánlja, 1 karakter után.
            $('.js-egyediazonositoszuro').autocomplete({
                minLength: 0,
                source: function (request, response) {
                    $.ajax({
                        url: '/admin/termekkarton/egyediazonositolista',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            termekid: $('input[name="termekid"]').val(),
                            valtozatid: $('select[name="valtozat"]').val(),
                            term: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                }
            });

            $('.js-refresh')
                .on('click', function () {

                    let partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter'),
                        partnerid;
                    if (isPartnerAutocomplete()) {
                        partnerid = $('.js-partnerid').val();
                    } else {
                        partnerid = $('#PartnerEdit option:selected').val();
                    }

                    $.ajax({
                        url: '/admin/termekkarton/refresh',
                        type: 'GET',
                        data: {
                            termekid: $('input[name="termekid"]').val(),
                            valtozatid: $('select[name="valtozat"]').val(),
                            datumtipus: $('select[name="datumtipus"]').val(),
                            datumtol: $('input[name="tol"]').val(),
                            datumig: $('input[name="ig"]').val(),
                            mozgat: $('select[name="mozgat"]').val(),
                            rontott: $('select[name="rontott"]').val(),
                            raktarid: $('select[name="raktar"]').val(),
                            partnerid: partnerid,
                            partnercimkefilter: partnercimkefilter,
                            egyediazonosito: $('input[name="egyediazonosito"]').val()
                        },
                        success: function (d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
        },
    }));
});