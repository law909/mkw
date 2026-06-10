let bizonylathelper = function ($) {

    let nocalcarak = false;
    let afaEllenorzesAtlepes = false;

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function setDates() {
        let keltedit = $('#KeltEdit'),
            esededit = $('#EsedekessegEdit'),
            kelt = keltedit.datepicker('getDate'),
            partner;
        if (isPartnerAutocomplete()) {
            partner = $('.js-partnerid').val();
        } else {
            partner = $('#PartnerEdit option:selected').val();
        }
        $.ajax({
            url: '/admin/bizonylatfej/calcesedekesseg',
            data: {
                kelt: kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate(),
                fizmod: $('#FizmodEdit option:selected').val(),
                partner: partner
            },
            success: function (data) {
                let d = JSON.parse(data);
                esededit.datepicker('setDate', d.esedekesseg);
            }
        });
    }

    function calcOsszesen() {
        let netto = 0, brutto = 0, nettohuf = 0, bruttohuf = 0;
        $('input[name^="tetelnetto_"]').each(function () {
            netto = netto + $(this).val() * 1;
        });
        $('input[name^="tetelbrutto_"]').each(function () {
            brutto = brutto + $(this).val() * 1;
        });
        $('input[name^="tetelnettohuf_"]').each(function () {
            nettohuf = nettohuf + $(this).val() * 1;
        });
        $('input[name^="tetelbruttohuf_"]').each(function () {
            bruttohuf = bruttohuf + $(this).val() * 1;
        });

        // quick
        $('.js-quickmennyiseginput').each(function () {
            let $this = $(this),
                menny = $this.val() * 1,
                id = $this.data('termektetelid');
            netto = netto + $('#NettoegysarEdit' + id).val() * menny;
            brutto = brutto + $('#BruttoegysarEdit' + id).val() * menny;
            nettohuf = nettohuf + $('#NettoegysarHufEdit' + id).val() * menny;
            bruttohuf = bruttohuf + $('#BruttoegysarHufEdit' + id).val() * menny;
        });

        $('.js-nettosum').text(accounting.formatNumber(tools.round(netto, -2), 2, ' '));
        $('.js-bruttosum').text(accounting.formatNumber(tools.round(brutto, -2), 2, ' '));
        $('.js-nettohufsum').text(accounting.formatNumber(tools.round(nettohuf, -2), 2, ' '));
        $('.js-bruttohufsum').text(accounting.formatNumber(tools.round(bruttohuf, -2), 2, ' '));
    }

    function recalcHufPrices(arfolyam) {
        $('.js-quicknettoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelnettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickbruttoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelbruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickenettoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelenettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickebruttoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelebruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-nettoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelnettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-bruttoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelbruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-enettoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelenettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-ebruttoegysarinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelebruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-nettoinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelnettohuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-bruttoinput').each(function () {
            let $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelbruttohuf_' + id + '"]').val($this.val() * arfolyam);
        });
        calcOsszesen();
    }

    function getArfolyam() {
        let d = $('#TeljesitesEdit').datepicker('getDate');
        if (d.getDate === undefined) {
            d = $('#KeltEdit').datepicker('getDate');
        }
        if (d.getDate !== undefined) {
            $.ajax({
                async: false,
                url: '/admin/arfolyam/getarfolyam',
                data: {
                    valutanem: $('#ValutanemEdit').val(),
                    datum: d.getFullYear() + '.' + (d.getMonth() + 1) + '.' + d.getDate()
                },
                success: function (data) {
                    let arfolyam = data * 1;
                    $('#ArfolyamEdit').val(data);
                    recalcHufPrices(arfolyam);
                }
            });
        }
    }

    function setTermekAr(sorId) {
        let partner, termekedit;
        if (isPartnerAutocomplete()) {
            partner = $('.js-partnerid').val();
        } else {
            partner = $('#PartnerEdit option:selected').val();
        }

        termekedit = $('input[name="teteltermek_' + sorId + '"]');
        if (!termekedit.length) {
            termekedit = $('select[name="teteltermek_' + sorId + '"] option:selected');
        }

        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                valutanem: $('#ValutanemEdit').val(),
                partner: partner,
                termek: termekedit.val(),
                valtozat: $('select[name="tetelvaltozat_' + sorId + '"]').val()
            },
            success: function (data) {
                let c = $('input[name="tetelnettoegysar_' + sorId + '"]'),
                    eb = $('#eladasibruttoar_' + sorId),
                    hasz = $('#haszonszazalek_' + sorId),
                    adat = JSON.parse(data);
                if (eb.length > 0) {
                    eb.text(adat.brutto);
                    eb.data('ertek', adat.brutto);
                    hasz.text('0%');
                }
                $('input[name="tetelenettoegysar_' + sorId + '"]').val(adat.enetto);
                $('input[name="tetelebruttoegysar_' + sorId + '"]').val(adat.ebrutto);
                $('input[name="tetelkedvezmeny_' + sorId + '"]').val(adat.kedvezmeny);
                c.val(adat.netto);
                c.change();
            }
        });
    }

    function calcArak(sorId) {
        if (!nocalcarak) {
            $.ajax({
                async: false,
                url: '/admin/bizonylattetel/calcar',
                data: {
                    valutanem: $('#ValutanemEdit').val(),
                    arfolyam: $('#ArfolyamEdit').val(),
                    afa: $('select[name="tetelafa_' + sorId + '"]').val(),
                    nettoegysar: $('input[name="tetelnettoegysar_' + sorId + '"]').val(),
                    enettoegysar: $('input[name="tetelenettoegysar_' + sorId + '"]').val(),
                    mennyiseg: $('input[name="tetelmennyiseg_' + sorId + '"]').val()
                },
                success: function (data) {
                    let resp = JSON.parse(data),
                        eb = $('#eladasibruttoar_' + sorId),
                        hasz = $('#haszonszazalek_' + sorId),
                        n = eb.data('ertek') / resp.bruttoegysar * 100 - 100;

                    $('input[name="tetelnettoegysar_' + sorId + '"]').val(resp.nettoegysar);
                    $('input[name="tetelbruttoegysar_' + sorId + '"]').val(resp.bruttoegysar);

                    $('input[name="tetelenettoegysar_' + sorId + '"]').val(resp.enettoegysar);
                    $('input[name="tetelebruttoegysar_' + sorId + '"]').val(resp.ebruttoegysar);

                    $('input[name="tetelnetto_' + sorId + '"]').val(resp.netto);
                    $('input[name="tetelbrutto_' + sorId + '"]').val(resp.brutto);

                    $('input[name="tetelnettoegysarhuf_' + sorId + '"]').val(resp.nettoegysarhuf);
                    $('input[name="tetelbruttoegysarhuf_' + sorId + '"]').val(resp.bruttoegysarhuf);

                    $('input[name="tetelenettoegysarhuf_' + sorId + '"]').val(resp.enettoegysarhuf);
                    $('input[name="tetelebruttoegysarhuf_' + sorId + '"]').val(resp.ebruttoegysarhuf);

                    $('input[name="tetelnettohuf_' + sorId + '"]').val(resp.nettohuf);
                    $('input[name="tetelbruttohuf_' + sorId + '"]').val(resp.bruttohuf);

                    hasz.text(tools.round(n, -2));
                    calcOsszesen();
                }
            });
        }
        nocalcarak = false;
    }

    function calcKedvezmeny(sorId) {
        let kedv = $('input[name="tetelkedvezmeny_' + sorId + '"]').val() * 1;
        $('input[name="tetelnettoegysar_' + sorId + '"]').val($('input[name="tetelenettoegysar_' + sorId + '"]').val() * (100 - kedv) / 100);
        calcArak(sorId);
    }

    function checkKelt(kelt, biztipus, bizszam) {
        let retval = false;
        $.ajax({
            async: false,
            url: '/admin/bizonylatfej/checkkelt',
            data: {
                kelt: kelt,
                biztipus: biztipus,
                bizszam: bizszam
            },
            success: function (data) {
                let d = JSON.parse(data);
                if (d.response === 'ok') {
                    retval = true;
                }
            }
        });
        return retval;
    }

    // A bizonylatfej adatainak ellenőrzése. A hibaüzeneteket tömbként adja vissza
    // (üres tömb = nincs hiba), a megjelenítésről a hívó (beforeSerialize) gondoskodik,
    // hogy az összes hibaüzenet egyszerre legyen kiírható.
    function checkBizonylatFej(biztipus, bizszam) {
        const keltedit = $('#KeltEdit'),
            partnerselect = $('.js-partnerid'),
            form = $('#mattkarb-form'),
            keltchanged = keltedit.attr('data-datum') !== keltedit.val(),
            keltok = (!keltchanged) || (keltchanged && checkKelt(keltedit.val(), biztipus, bizszam)),
            tetelok = ($('.js-termekid').length !== 0) && ($('.js-termekid[value=""]').length === 0) && ($('.js-termekid[value="0"]').length === 0),
            partnerok = (isPartnerAutocomplete() && ((partnerselect.val() !== '') || (partnerselect.val() === '' && $('.js-ujpartnercb').val() === '1'))) || (!isPartnerAutocomplete()),
            partnernevok = $('input[name="partnernev"]').val() !== '' || $('input[name="partnerkeresztnev"]').val() !== '' || $('input[name="partnervezeteknev"]').val() !== '',
            funnypartnermessage = form.data('funnypartnermessage'),
            lastname = form.data('lastname');
        let hibak = [];
        if (!keltok) {
            hibak.push('A bizonylatoknak szigorú sorszámozás van előírva.');
        }
        if (!tetelok) {
            hibak.push('Nincsenek tételek a bizonylaton.');
        }
        if (!partnerok) {
            if (funnypartnermessage) {
                hibak.push(lastname + ', kérlek figyelj oda jobban, az "Új" pipát elfelejtetted berakni!');
            } else {
                hibak.push('Válasszon partnert, vagy kapcsolja be az "Új" pipát.');
            }
        } else if (!partnernevok) {
            hibak.push('Nincs megadva partner név.');
        }
        return hibak;
    }

    function checkTetelOsszegek() {
        let mindenOk = true,
            afaEgyezik = true,
            hibaClass = 'tetelszamhiba',
            arfolyam = $('#ArfolyamEdit').val() * 1,
            szuksegesAfa = $('#PartnerEdit').data('afa');

        // Korábbi hibajelölések törlése, hogy a javított mezők ne maradjanak megjelölve.
        $('.' + hibaClass).removeClass(hibaClass);

        // Két összeg akkor tekinthető egyezőnek, ha az eltérés a kerekítésből
        // adódó hibahatáron belül van. A hibahatár a szorzótényezővel arányosan nő.
        function egyezik(tenyleges, elvart, tenyezo) {
            let hibahatar = 0.1 + Math.abs(tenyezo) * 0.01;
            return Math.abs(tenyleges - elvart) <= hibahatar;
        }

        // Az adott nevű input megjelölése hibásként.
        function jelol(name) {
            $('input[name="' + name + '"]').addClass(hibaClass);
            mindenOk = false;
        }

        $('input.js-mennyiseginput').each(function () {
            let sorId = $(this).attr('name').split('_')[1],
                menny = $('input[name="tetelmennyiseg_' + sorId + '"]').val() * 1,
                kedv = $('input[name="tetelkedvezmeny_' + sorId + '"]').val() * 1,
                afakulcs = $('select[name="tetelafa_' + sorId + '"] option:selected').data('afakulcs') * 1,
                enettoegysar = $('input[name="tetelenettoegysar_' + sorId + '"]').val() * 1,
                ebruttoegysar = $('input[name="tetelebruttoegysar_' + sorId + '"]').val() * 1,
                nettoegysar = $('input[name="tetelnettoegysar_' + sorId + '"]').val() * 1,
                bruttoegysar = $('input[name="tetelbruttoegysar_' + sorId + '"]').val() * 1,
                netto = $('input[name="tetelnetto_' + sorId + '"]').val() * 1,
                brutto = $('input[name="tetelbrutto_' + sorId + '"]').val() * 1,
                nettohuf = $('input[name="tetelnettohuf_' + sorId + '"]'),
                bruttohuf = $('input[name="tetelbruttohuf_' + sorId + '"]');

            if (isNaN(kedv)) {
                kedv = 0;
            }

            // 1. Mennyiség * Egységár = Érték
            if (!egyezik(netto, menny * nettoegysar, menny)) {
                jelol('tetelnetto_' + sorId);
            }
            if (!egyezik(brutto, menny * bruttoegysar, menny)) {
                jelol('tetelbrutto_' + sorId);
            }

            // 2. Eredeti egységár * (100 - Kedvezmény%) = Egységár
            //    (csak akkor, ha van eredeti egységár)
            if (kedv !== 0) {
                if (enettoegysar && !egyezik(nettoegysar, enettoegysar * (100 - kedv) / 100, 1)) {
                    jelol('tetelnettoegysar_' + sorId);
                    jelol('tetelkedvezmeny_' + sorId);
                }
                if (ebruttoegysar && !egyezik(bruttoegysar, ebruttoegysar * (100 - kedv) / 100, 1)) {
                    jelol('tetelbruttoegysar_' + sorId);
                    jelol('tetelkedvezmeny_' + sorId);
                }
            }

            // 3. Nettó * (100 + ÁFA%) = Bruttó
            //    (csak akkor, ha van kiválasztott ÁFA kulcs)
            if (!isNaN(afakulcs)) {
                if (!egyezik(bruttoegysar, nettoegysar * (100 + afakulcs) / 100, 1)) {
                    jelol('tetelbruttoegysar_' + sorId);
                }
                if (!egyezik(brutto, netto * (100 + afakulcs) / 100, 1)) {
                    jelol('tetelbrutto_' + sorId);
                }
            }

            // 4. Érték * Árfolyam = Érték HUF (csak valutás bizonylatnál)
            if (nettohuf.length && !isNaN(arfolyam) && arfolyam) {
                if (!egyezik(nettohuf.val() * 1, netto * arfolyam, arfolyam)) {
                    jelol('tetelnettohuf_' + sorId);
                }
                if (!egyezik(bruttohuf.val() * 1, brutto * arfolyam, arfolyam)) {
                    jelol('tetelbruttohuf_' + sorId);
                }
            }

            // 5. A tétel ÁFA kulcsa egyezzen meg a partnernél előírt (szükséges) ÁFA kulccsal.
            //    Csak akkor ellenőrizzük, ha a partnerhez tartozik előírt (override) ÁFA.
            if (szuksegesAfa) {
                let afaselect = $('select[name="tetelafa_' + sorId + '"]');
                if (afaselect.length && (afaselect.val() != szuksegesAfa)) {
                    afaselect.addClass(hibaClass);
                    afaEgyezik = false;
                }
            }
        });

        return {osszegekok: mindenOk, afaegyezik: afaEgyezik};
    }

    // Egy tétel egyedi azonosító mezőjének beállítása aszerint, hogy a kiválasztott
    // termékhez tartozik-e egyedi azonosító. Ha igen, a mező megjelenik és kötelező lesz,
    // valamint a mennyiség 1-re áll (egyedi azonosítónál a mennyiség csak 1 vagy -1 lehet).
    function setEgyediAzonositoMezo(sorid, kell) {
        let row = $('.js-egyediazonositorow_' + sorid),
            input = $('input[name="teteltermekegyediazonosito_' + sorid + '"]'),
            kellinput = $('input[name="tetelkellegyediazonosito_' + sorid + '"]');
        kellinput.val(kell ? 1 : 0);
        if (kell) {
            row.show();
            input.prop('required', true);
            let menny = $('input[name="tetelmennyiseg_' + sorid + '"]'),
                mval = menny.val() * 1;
            if (mval !== 1 && mval !== -1) {
                menny.val(1).change();
            }
        } else {
            row.hide();
            input.prop('required', false).val('');
        }
    }

    // Az egyedi azonosítót igénylő tételek ellenőrzése mentés előtt:
    // az azonosító kötelező, a mennyiség pedig csak 1 vagy -1 lehet.
    function checkEgyediAzonositok() {
        let hibaClass = 'tetelszamhiba',
            mindenOk = true;
        $('input.js-egyediazonositokell').each(function () {
            if ($(this).val() * 1 === 1) {
                let sorId = $(this).attr('name').split('_')[1],
                    azon = $('input[name="teteltermekegyediazonosito_' + sorId + '"]');
                if (!azon.val() || !azon.val().trim()) {
                    azon.addClass(hibaClass);
                    mindenOk = false;
                }
            }
        });
        return mindenOk;
    }

    function checkEgyediAzonositosMennyisegek() {
        let hibaClass = 'tetelszamhiba',
            mindenOk = true;
        $('input.js-egyediazonositokell').each(function () {
            if ($(this).val() * 1 === 1) {
                let sorId = $(this).attr('name').split('_')[1],
                    menny = $('input[name="tetelmennyiseg_' + sorId + '"]'),
                    mval = menny.val() * 1;
                if (mval !== 1 && mval !== -1) {
                    menny.addClass(hibaClass);
                    mindenOk = false;
                }
            }
        });
        return mindenOk;
    }

    // Az egyedi azonosítós termékek azonosítóinak egyediségének ellenőrzése: ha ugyanaz a termék
    // több tételben szerepel, az egyedi azonosítóknak különbözniük kell. Az ütköző (azonos termékhez
    // azonos azonosítójú) mezőket megjelöli. (Szerver: Bizonylatfej::checkTetelOsszegHibak 7. pont)
    function checkEgyediAzonositoEgyediseg() {
        let hibaClass = 'tetelszamhiba',
            mindenOk = true,
            elofordulasok = {}; // termékid + '|' + azonosító -> [sorId, ...]
        $('input.js-egyediazonositokell').each(function () {
            if ($(this).val() * 1 === 1) {
                let sorId = $(this).attr('name').split('_')[1],
                    termekid = $('[name="teteltermek_' + sorId + '"]').val(),
                    azon = $('input[name="teteltermekegyediazonosito_' + sorId + '"]').val();
                azon = azon ? azon.trim() : '';
                // Az üres azonosítót a checkEgyediAzonositok kezeli, itt nem foglalkozunk vele.
                if (azon === '') {
                    return;
                }
                let kulcs = termekid + '|' + azon;
                if (!elofordulasok[kulcs]) {
                    elofordulasok[kulcs] = [];
                }
                elofordulasok[kulcs].push(sorId);
            }
        });
        $.each(elofordulasok, function (kulcs, sorIdk) {
            if (sorIdk.length > 1) {
                mindenOk = false;
                $.each(sorIdk, function (i, sorId) {
                    $('input[name="teteltermekegyediazonosito_' + sorId + '"]').addClass(hibaClass);
                });
            }
        });
        return mindenOk;
    }

    function loadValtozatList(id, sorid, selvaltozat, valtozatplace) {
        let raktarid = $('select[name="raktar"] option:selected').val();
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/valtozatlist',
            data: {
                id: id,
                tetelid: sorid,
                sel: selvaltozat,
                raktar: raktarid
            },
            success: function (data) {
                let d = JSON.parse(data);
                if (d.db) {
                    $(d.html).appendTo(valtozatplace);
                } else {
                    setTermekAr(sorid);
                }
            }
        });
    }

    function setNoCalcArak(n) {
        nocalcarak = n;
    }

    function irszamAutocomplete(irszam, varos) {
        $(irszam).autocomplete({
            minLength: 2,
            source: function (req, resp) {
                $.ajax({
                    url: '/admin/irszam',
                    type: 'GET',
                    data: {
                        term: req.term,
                        tip: 1
                    },
                    success: function (data) {
                        let d = JSON.parse(data);
                        resp(d);
                    },
                    error: function () {
                        resp();
                    }
                });
            },
            select: function (event, ui) {
                $(varos).val(ui.item.nev);
            }
        });
    }

    function varosAutocomplete(irszam, varos) {
        $(varos).autocomplete({
            minLength: 4,
            source: function (req, resp) {
                $.ajax({
                    url: '/admin/varos',
                    type: 'GET',
                    data: {
                        term: req.term,
                        tip: 1
                    },
                    success: function (data) {
                        let d = JSON.parse(data);
                        resp(d);
                    },
                    error: function () {
                        resp();
                    }
                });
            },
            select: function (event, ui) {
                $(irszam).val(ui.item.szam);
            }
        });
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo(ul);
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                let partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    $('.js-ujpartnercb').prop('checked', false);
                    pi.change();
                }
            }
        };
    }

    function termekAutocompleteRenderer(ul, item) {
        if (item.nemlathato) {
            return $('<li>')
                .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
                .appendTo(ul);
        } else {
            return $('<li>')
                .append('<a>' + item.label + '</a>')
                .appendTo(ul);
        }
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function (event, ui) {
                let termek = ui.item;
                if (termek) {
                    let $this = $(this),
                        sorid = $this.attr('name').split('_')[1],
                        vtsz = $('select[name="tetelvtsz_' + sorid + '"]'),
                        afa = $('select[name="tetelafa_' + sorid + '"]'),
                        selvaltozat = $('select[name="tetelvaltozat_' + sorid + '"]').val(),
                        valtozatplace = $('#ValtozatPlaceholder' + sorid),
                        partneredit = $('#PartnerEdit');
                    if (partneredit.data('afa')) {
                        termek.afa = partneredit.data('afa');
                        termek.afakulcs = partneredit.data('afakulcs');
                    }
                    setNoCalcArak(true);
                    valtozatplace.empty();
                    $this.siblings().val(termek.id);
                    $('input[name="tetelnev_' + sorid + '"]').val(termek.value);
                    $('input[name="tetelcikkszam_' + sorid + '"]').val(termek.cikkszam);
                    $('select[name="tetelme_' + sorid + '"]').val(termek.me);
                    if (!$('input[name="tetelmennyiseg_' + sorid + '"]').val() && termek.defaultmennyiseg) {
                        $('input[name="tetelmennyiseg_' + sorid + '"]').val(termek.defaultmennyiseg);
                    }
                    setEgyediAzonositoMezo(sorid, termek.kellegyediazonosito);
                    vtsz.val(termek.vtsz);
                    vtsz.change();
                    afa.val(termek.afa);
                    afa.change();
                    kepsor = $('.js-termekpicturerow_' + sorid);
                    $('.js-toflyout', kepsor).attr('href', termek.mainurl + termek.kepurl);
                    $('.js-toflyout img', kepsor).attr('src', termek.mainurl + termek.kiskepurl);
                    $('.js-termeklink', kepsor).attr('href', termek.link).html(termek.link);
                    $('.js-kartonlink', kepsor).attr('href', termek.kartonurl);
                    if (termek.valtozat) {  // cikkszam alapjan beazonositott konkret valtozat
                        selvaltozat = termek.valtozat;
                    }
                    loadValtozatList(termek.id, sorid, selvaltozat, valtozatplace);
                    if (termek.valtozat) {  // valtozat select kitoltese + valtozat ar betoltese
                        $('select[name="tetelvaltozat_' + sorid + '"]').val(termek.valtozat).change();
                    }
                }
            }
        };
    }

    function quicksetTermekAr(sorId) {
        let partner;
        if (isPartnerAutocomplete()) {
            partner = $('.js-partnerid').val();
        } else {
            partner = $('#PartnerEdit option:selected').val();
        }
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                valutanem: $('#ValutanemEdit').val(),
                partner: partner,
                termek: $('input[name="qteteltermek_' + sorId + '"]').val(),
                valtozat: $('select[name="qtetelvaltozat_' + sorId + '"]').val()
            },
            success: function (data) {
                let c = $('input[name="qtetelnettoegysar_' + sorId + '"]'),
                    adat = JSON.parse(data);
                $('input[name="qtetelenettoegysar_' + sorId + '"]').val(adat.enetto);
                $('input[name="qtetelebruttoegysar_' + sorId + '"]').val(adat.ebrutto);
                $('input[name="qtetelkedvezmeny_' + sorId + '"]').val(adat.kedvezmeny);
                c.val(adat.netto);
                c.change();
            }
        });
    }

    function quickcalcArak(sorId) {
        if (!nocalcarak) {
            $.ajax({
                async: false,
                url: '/admin/bizonylattetel/calcar',
                data: {
                    valutanem: $('#ValutanemEdit').val(),
                    arfolyam: $('#ArfolyamEdit').val(),
                    afa: $('input[name="qtetelafa_' + sorId + '"]').val(),
                    nettoegysar: $('input[name="qtetelnettoegysar_' + sorId + '"]').val(),
                    enettoegysar: $('input[name="qtetelenettoegysar_' + sorId + '"]').val(),
                    mennyiseg: 1
                },
                success: function (data) {
                    let resp = JSON.parse(data);
                    $('input[name="qtetelnettoegysar_' + sorId + '"]').val(resp.nettoegysar);
                    $('input[name="qtetelbruttoegysar_' + sorId + '"]').val(resp.bruttoegysar);

                    $('input[name="qtetelenettoegysar_' + sorId + '"]').val(resp.enettoegysar);
                    $('input[name="qtetelebruttoegysar_' + sorId + '"]').val(resp.ebruttoegysar);

                    $('input[name="qtetelnetto_' + sorId + '"]').val(resp.netto);
                    $('input[name="qtetelbrutto_' + sorId + '"]').val(resp.brutto);

                    $('input[name="qtetelnettoegysarhuf_' + sorId + '"]').val(resp.nettoegysarhuf);
                    $('input[name="qtetelbruttoegysarhuf_' + sorId + '"]').val(resp.bruttoegysarhuf);

                    $('input[name="qtetelenettoegysarhuf_' + sorId + '"]').val(resp.enettoegysarhuf);
                    $('input[name="qtetelebruttoegysarhuf_' + sorId + '"]').val(resp.ebruttoegysarhuf);

                    $('input[name="qtetelnettohuf_' + sorId + '"]').val(resp.nettohuf);
                    $('input[name="qtetelbruttohuf_' + sorId + '"]').val(resp.bruttohuf);
                    calcOsszesen();
                }
            });
        }
        nocalcarak = false;
    }

    function quickcalcKedvezmeny(sorId) {
        let kedv = $('input[name="qtetelkedvezmeny_' + sorId + '"]').val() * 1;
        $('input[name="qtetelnettoegysar_' + sorId + '"]').val($('input[name="qtetelenettoegysar_' + sorId + '"]').val() * (100 - kedv) / 100);
        quickcalcArak(sorId);
    }

    function loadquickValtozatList(id, sorid) {
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/quickvaltozatlist',
            data: {
                id: id,
                tetelid: sorid
            },
            success: function (data) {
                let d = JSON.parse(data);
                $('#valtozattable_' + d.tetelid).html(d.html);
            }
        });
    }

    function quicktermekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function (event, ui) {
                let termek = ui.item;
                if (termek) {
                    // Egyedi azonosítót igénylő termék a gyors felvitellel nem vehető fel,
                    // mert ott nincs lehetőség a kötelező azonosító megadására.
                    if (termek.kellegyediazonosito) {
                        $('#dialogcenter').html('Ez a termék egyedi azonosítót igényel, ezért csak a normál tételszerkesztővel vehető fel (a gyors felvitel nem támogatja).').dialog({
                            resizable: false,
                            width: 460,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        return false;
                    }
                    let $this = $(this),
                        sorid = $this.attr('name').split('_')[1],
                        partneredit = $('#PartnerEdit');
                    if (partneredit.data('afa')) {
                        termek.afa = partneredit.data('afa');
                        termek.afakulcs = partneredit.data('afakulcs');
                    }
                    $('input[name="qteteltermek_' + sorid + '"]').val(termek.id);
                    $('input[name="qtetelcikkszam_' + sorid + '"]').val(termek.cikkszam);
                    $('input[name="qtetelafa_' + sorid + '"]').val(termek.afa).data('afakulcs', termek.afakulcs);
                    $('select[name="qtetelme_' + sorid + '"]').val(termek.me);
                    kepsor = $('.js-termekpicturerow_' + sorid);
                    $('.js-toflyout', kepsor).attr('href', termek.mainurl + termek.kepurl);
                    $('.js-toflyout img', kepsor).attr('src', termek.mainurl + termek.kiskepurl);
                    $('.js-termeklink', kepsor).attr('href', termek.link).html(termek.link);
                    $('.js-kartonlink', kepsor).attr('href', termek.kartonurl);
                    loadquickValtozatList(termek.id, sorid);
                    quicksetTermekAr(sorid);
                }
            }
        };
    }

    function valutanemChange(firstrun) {
        if (!firstrun || $('input[name="oper"]').val() === 'add') {
            $('#BankszamlaEdit').val($('option:selected', $('#ValutanemEdit')).data('bankszamla'));
        }
        getArfolyam();
    }

    function setPartnerData(d) {
        if (d.fizmod) {
            $('#FizmodEdit').val(d.fizmod);
        }
        if (d.valutanem) {
            $('#ValutanemEdit').val(d.valutanem);
        }
        if (d.szallitasimod) {
            $('#SzallitasimodEdit').val(d.szallitasimod);
        }
        if (d.uzletkoto) {
            $('#UzletkotoEdit').val(d.uzletkoto);
        }
        if (d.bizonylatnyelv) {
            $('#BizonylatnyelvEdit').val(d.bizonylatnyelv);
        }
        $('input[name="partnernev"]').val(d.nev);
        $('input[name="partnervezeteknev"]').val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]').val(d.keresztnev);
        $('input[name="partnerirszam"]').val(d.irszam);
        $('input[name="partnervaros"]').val(d.varos);
        $('input[name="partnerutca"]').val(d.utca);
        $('input[name="partnerhazszam"]').val(d.hazszam);
        $('input[name="partneradoszam"]').val(d.adoszam);
        $('input[name="partnereuadoszam"]').val(d.euadoszam);
        $('input[name="partnerthirdadoszam"]').val(d.thirdadoszam);
        $('input[name="szallnev"]').val(d.szallnev);
        $('input[name="szallirszam"]').val(d.szallirszam);
        $('input[name="szallvaros"]').val(d.szallvaros);
        $('input[name="szallutca"]').val(d.szallutca);
        $('input[name="szallhazszam"]').val(d.szallhazszam);
        $('input[name="partnertelefon"]').val(d.telefon);
        $('input[name="partneremail"]').val(d.email);
        if (d.orszag) {
            $('#OrszagEdit').val(d.orszag);
        }
        if (d.szallorszag) {
            $('#SzallOrszagEdit').val(d.szallorszag);
        }
        if (d.vatstatus) {
            $('#VatstatusEdit').val(d.vatstatus);
        }
        $('#SzamlatipusEdit').val(d.szamlatipus);
        $('#PartnerEdit').data('afa', d.afa).data('afakulcs', d.afakulcs);
        setDates();
        valutanemChange();
    }

    function handleAFAOverrideFieldChange(e) {
        e.preventDefault();
        let dialogcenter = $('#dialogcenter');
        $.ajax({
            async: false,
            url: '/admin/partner/getafaoverride',
            data: {
                szallorszag: $('#SzallOrszagEdit').val(),
                orszag: $('#OrszagEdit').val(),
                szamlatipus: $('#SzamlatipusEdit').val(),
                euadoszam: $('input[name="partnereuadoszam"]').val()
            },
            success: function (data) {
                let d = JSON.parse(data);
                if (d.id) {
                    $('#PartnerEdit').data('afa', d.id).data('afakulcs', d.ertek);
                    $('.js-afaselect').each(function () {
                        let $this = $(this);
                        $this.val(d.id);
                        $this.change();
                    })
                } else {
                    dialogcenter.html('Az ÁFA kulcsot nem lehet megállapítani.').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            }
        });

    }

    function getMattKarbConfig(bizonylattipus) {
        return {
            container: '#mattkarb',
            viewUrl: '/admin/' + bizonylattipus + 'fej/getkarb',
            newWindowUrl: '/admin/' + bizonylattipus + 'fej/viewkarb',
            saveUrl: '/admin/' + bizonylattipus + 'fej/save',
            beforeShow: function () {
                let keltedit = $('#KeltEdit'),
                    teljesitesedit = $('#TeljesitesEdit'),
                    fizmodedit = $('#FizmodEdit'),
                    alttab = $('#AltalanosTab'),
                    dialogcenter = $('#dialogcenter');
                let doktab = $('#DokTab');

                doktab
                    .on('click', '.js-doknewbutton', function (e) {
                        let $this = $(this);
                        e.preventDefault();
                        $.ajax({
                            url: '/admin/bizonylatdok/getemptyrow',
                            type: 'GET',
                            success: function (data) {
                                doktab.append(data);
                                $('.js-doknewbutton,.js-dokdelbutton,.js-dokbrowsebutton,.js-dokopenbutton,.js-dokopen2button').button();
                                $this.remove();
                            }
                        });
                    })
                    .on('click', '.js-dokdelbutton', function (e) {
                        e.preventDefault();
                        let $this = $(this);
                        dialogcenter.html('Biztos, hogy törli a dokumentumot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/bizonylatdok/del',
                                        type: 'POST',
                                        data: {
                                            id: $this.attr('data-id')
                                        },
                                        success: function (data) {
                                            $('#doktable_' + data).remove();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    })
                    .on('click', '.js-dokbrowsebutton', function (e) {
                        e.preventDefault();
                        let finder = new CKFinder(),
                            $dokpathedit = $('#DokPathEdit_' + $(this).attr('data-id')),
                            path = $dokpathedit.val();
                        finder.resourceType = 'Images';
                        if (path) {
                            finder.startupPath = path.substring(path.indexOf('/', 1));
                        }
                        finder.selectActionFunction = function (fileUrl, data) {
                            $dokpathedit.val(fileUrl);
                        };
                        finder.popup();
                    });
                $('.js-doknewbutton,.js-dokbrowsebutton,.js-dokdelbutton,.js-dokopenbutton,.js-dokopen2button').button();

                $('#EmailEdit').change(function () {
                    let partner,
                        ee = $(this);
                    if (isPartnerAutocomplete()) {
                        partner = $('.js-partnerid').val();
                    } else {
                        partner = $('#PartnerEdit option:selected').val();
                    }
                    if (partner == -1) {
                        $.ajax({
                            url: '/admin/partner/getdata',
                            type: 'GET',
                            data: {
                                email: ee.val()
                            },
                            success: function (data) {
                                let d = JSON.parse(data);
                                if (d.id) {
                                    setPartnerData(d);
                                    if (isPartnerAutocomplete()) {
                                        $('.js-partnerid').val(d.id);
                                    } else {
                                        $('#PartnerEdit').val(d.id);
                                    }
                                }
                            }
                        });
                    }
                });

                $('.js-partnerid').change(function () {
                    let pe = $(this);
                    if (pe.val() > 0) {
                        $.ajax({
                            url: '/admin/partner/getdata',
                            type: 'GET',
                            data: {
                                partnerid: pe.val()
                            },
                            success: function (data) {
                                let d = JSON.parse(data);
                                setPartnerData(d);
                            }
                        });
                    }
                });

                $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                    .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

                irszamAutocomplete('input[name="partnerirszam"]', 'input[name="partnervaros"]');
                varosAutocomplete('input[name="partnerirszam"]', 'input[name="partnervaros"]');

                irszamAutocomplete('input[name="szallirszam"]', 'input[name="szallvaros"]');
                varosAutocomplete('input[name="szallirszam"]', 'input[name="szallvaros"]');

                $('#SzallOrszagEdit,#OrszagEdit,#SzamlatipusEdit,#EUAdoszamEdit').change(handleAFAOverrideFieldChange);

                $('.js-querytaxpayer').on('click', function (e) {
                    e.preventDefault();
                    let adoszam = $('#AdoszamEdit').val();
                    if (adoszam.length > 0) {
                        $.ajax({
                            url: '/admin/partner/querytaxpayer',
                            type: 'GET',
                            data: {
                                adoszam: adoszam
                            },
                            success: function (data) {
                                data = JSON.parse(data);
                                $('#NevEdit').val(data.taxpayerName);
                                let adoszam = data.taxNumberDetail,
                                    cim;
                                $('#AdoszamEdit').val(adoszam.taxpayerId + '-' + adoszam.vatCode + '-' + adoszam.countyCode);
                                $('#VatstatusEdit').val('1');
                                if (Array.isArray(data.taxpayerAddressList.taxpayerAddressItem)) {
                                    data.taxpayerAddressList.taxpayerAddressItem.forEach(elem => {
                                        if (elem.taxpayerAddressType === 'HQ') {
                                            cim = elem.taxpayerAddress;
                                        }
                                    });
                                } else {
                                    cim = data.taxpayerAddressList.taxpayerAddressItem.taxpayerAddress;
                                }
                                if (cim) {
                                    $('#VarosEdit').val(cim.city);
                                    $('#IrszamEdit').val(cim.postalCode);
                                    $('#UtcaEdit').val(cim.streetName + ' ' + cim.publicPlaceCategory);
                                    $('#HazszamEdit').val(cim.number);
                                }
                            }
                        })
                    }
                }).button();
                $('.js-ujpartnercb').on('change', function () {
                    if ($(this).prop('checked')) {
                        $('input[name="partner"]').val(-1);
                        $('#PartnerEdit').val('');
                        $('.js-querytaxpayer').show();
                    } else {
                        $(this).prop('checked', true);
                    }
                });
                $('#ValutanemEdit').change(function () {
                    valutanemChange();
                });
                fizmodedit.on('change', function () {
                    setDates();
                });
                alttab
                    .on('click', '.js-quicktetelnewbutton', function (e) {
                        let $this = $(this);
                        e.preventDefault();
                        $.ajax({
                            url: '/admin/bizonylattetel/getquickemptyrow',
                            data: {
                                type: bizonylattipus
                            },
                            type: 'GET',
                            success: function (data) {
                                $('.js-bizonylatosszesito').before(data);
                                $('.js-quicktetelnewbutton,.js-teteldelbutton').button();
                                $('.js-termekselect').autocomplete(quicktermekAutocompleteConfig())
                                    .autocomplete("instance")._renderItem = termekAutocompleteRenderer;
                                $this.remove();
                            }
                        });
                    })
                    .on('click', '.js-tetelnewbutton', function (e) {
                        let $this = $(this);
                        e.preventDefault();
                        if (isPartnerAutocomplete()) {
                            partner = $('.js-partnerid').val();
                        } else {
                            partner = $('#PartnerEdit option:selected').val();
                        }
                        $.ajax({
                            url: '/admin/bizonylattetel/getemptyrow',
                            data: {
                                type: bizonylattipus,
                                partner: partner
                            },
                            type: 'GET',
                            success: function (data) {
                                $('.js-bizonylatosszesito').before(data);
                                $('.js-tetelnewbutton,.js-teteldelbutton').button();
                                $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                                    .autocomplete("instance")._renderItem = termekAutocompleteRenderer;
                                $this.remove();
                            }
                        });
                    })
                    .on('click', '.js-teteldelbutton', function (e) {
                        e.preventDefault();
                        let removegomb = $(this),
                            removeid = removegomb.attr('data-id');
                        if (removegomb.attr('data-source') == 'client') {
                            dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        $('#teteltable_' + removeid).remove();
                                        calcOsszesen();
                                        $(this).dialog('close');
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        } else {
                            dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        $.ajax({
                                            url: '/admin/bizonylattetel/save',
                                            type: 'POST',
                                            data: {
                                                id: removeid,
                                                oper: 'del'
                                            },
                                            success: function (data) {
                                                $('#teteltable_' + data).remove();
                                                calcOsszesen();
                                            }
                                        });
                                        $(this).dialog('close');
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    })
                    .on('change', '.js-vtszselect', function (e) {
                        e.preventDefault();
                        let $this = $(this);
                        let sorid = $this.attr('name').split('_')[1],
                            valasztott = $('option:selected', $this);
                        let afa = $('select[name="tetelafa_' + sorid + '"]');
                        afa.val(valasztott.data('afa'));
                        afa.change();
                    })
                    .on('change', '#ArfolyamEdit', function (e) {
                        e.preventDefault();
                        recalcHufPrices($(this).val() * 1);
                    })
                    .on('change', '.js-afaselect', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        calcArak(sorid);
                    })
                    .on('change', '.js-nettoegysarinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        calcArak(sorid);
                    })
                    .on('change', '.js-bruttoegysarinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        let afakulcs = $('select[name="tetelafa_' + sorid + '"] option:selected').data('afakulcs') * 1;
                        let n = $('input[name="tetelnettoegysar_' + sorid + '"]');
                        n.val($(this).val() / (100 + afakulcs) * 100);
                        n.change();
                    })
                    .on('change', '.js-quicknettoegysarinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        quickcalcArak(sorid);
                    })
                    .on('change', '.js-quickbruttoegysarinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        let afakulcs = $('input[name="qtetelafa_' + sorid + '"]').data('afakulcs') * 1;
                        let n = $('input[name="qtetelnettoegysar_' + sorid + '"]');
                        n.val($(this).val() / (100 + afakulcs) * 100);
                        n.change();
                    })
                    .on('change', '.js-nettoinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        let n = $('input[name="tetelnettoegysar_' + sorid + '"]');
                        n.val($(this).val() / $('input[name="tetelmennyiseg_' + sorid + '"]').val());
                        n.change();
                    })
                    .on('change', '.js-bruttoinput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        let afakulcs = $('select[name="tetelafa_' + sorid + '"] option:selected').data('afakulcs') * 1;
                        let n = $('input[name="tetelnetto_' + sorid + '"]');
                        n.val($(this).val() / (100 + afakulcs) * 100);
                        n.change();
                    })
                    .on('change', '.js-mennyiseginput', function (e) {
                        e.preventDefault();
                        let sorid = $(this).attr('name').split('_')[1];
                        calcArak(sorid);
                    })
                    .on('change', '.js-tetelvaltozat', function (e) {
                        e.preventDefault();
                        let $this = $(this),
                            sorid = $this.attr('name').split('_')[1],
                            valtozatcikkszam = $('option:selected', $this).attr('data-cikkszam');
                        if (valtozatcikkszam) {
                            $('input[name="tetelcikkszam_' + sorid + '"]').val(valtozatcikkszam);
                        }
                        setTermekAr(sorid);
                    })
                    .on('change', '.js-bizonylatstatuszedit', function (e) {
                        $('input[name="bizonylatstatuszertesito"]').prop('checked', true);
                    })
                    .on('change', '.js-quickmennyiseginput', function (e) {
                        calcOsszesen();
                    })
                    .on('change', '.js-kedvezmeny', function (e) {
                        calcKedvezmeny($(this).attr('name').split('_')[1]);
                    })
                    .on('change', '.js-quickkedvezmeny', function (e) {
                        quickcalcKedvezmeny($(this).attr('name').split('_')[1]);
                    })
                    .on('change', '#SzallitasimodEdit', function (e) {
                        $.ajax({
                            url: '/admin/csomagterminal/gethtmllist',
                            type: 'GET',
                            data: {
                                szmid: $('#SzallitasimodEdit option:selected').val()
                            },
                            success: function (data) {
                                let d = JSON.parse(data);
                                if (d) {
                                    $('#CsomagTerminalEdit').html(d.html);
                                }
                            }
                        });

                    })
                    .on('change', '.js-termekselectreal', function (e) {
                        let $this = $(this),
                            sorid = $this.attr('name').split('_')[1],
                            vtsz = $('select[name="tetelvtsz_' + sorid + '"]'),
                            afa = $('select[name="tetelafa_' + sorid + '"]'),
                            selvaltozat = $('select[name="tetelvaltozat_' + sorid + '"]').val(),
                            valtozatplace = $('#ValtozatPlaceholder' + sorid),
                            partneredit = $('#PartnerEdit');
                        $.ajax({
                            method: 'GET',
                            url: '/admin/bizonylattetel/gettermeklist',
                            data: {
                                id: $('option:selected', $this).val()
                            },
                            success: function (data) {
                                let termek = JSON.parse(data);
                                if (termek) {
                                    if (partneredit.data('afa')) {
                                        termek.afa = partneredit.data('afa');
                                        termek.afakulcs = partneredit.data('afakulcs');
                                    }
                                    setNoCalcArak(true);
                                    valtozatplace.empty();
                                    $('input[name="tetelnev_' + sorid + '"]').val(termek.value);
                                    $('input[name="tetelcikkszam_' + sorid + '"]').val(termek.cikkszam);
                                    $('select[name="tetelme_' + sorid + '"]').val(termek.me);
                                    if (!$('input[name="tetelmennyiseg_' + sorid + '"]').val() && termek.defaultmennyiseg) {
                                        $('input[name="tetelmennyiseg_' + sorid + '"]').val(termek.defaultmennyiseg);
                                    }
                                    setEgyediAzonositoMezo(sorid, termek.kellegyediazonosito);
                                    vtsz.val(termek.vtsz);
                                    vtsz.change();
                                    afa.val(termek.afa);
                                    afa.change();
                                    kepsor = $('.js-termekpicturerow_' + sorid);
                                    $('.js-toflyout', kepsor).attr('href', termek.mainurl + termek.kepurl);
                                    $('.js-toflyout img', kepsor).attr('src', termek.mainurl + termek.kiskepurl);
                                    $('.js-termeklink', kepsor).attr('href', termek.link).html(termek.link);
                                    $('.js-kartonlink', kepsor).attr('href', termek.kartonurl);
                                    loadValtozatList(termek.id, sorid, selvaltozat, valtozatplace);
                                }
                            }
                        });
                    });

                $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                    .autocomplete("instance")._renderItem = termekAutocompleteRenderer;

                $('.js-tetelnewbutton,.js-teteldelbutton,.js-inheritbizonylat,.js-quicktetelnewbutton,.js-backorder,.js-nav,.js-navstat,.js-email').button();

                $('.js-inheritbizonylat').each(function () {
                    let $this = $(this);
                    $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                });

                mkwcomp.datumEdit.init('#KeltEdit');
                keltedit.on('change', function (e) {
                    setDates();
                    valutanemChange();
                });
                mkwcomp.datumEdit.init('#TeljesitesEdit');
                teljesitesedit.on('change', function () {
                    getArfolyam();
                });
                mkwcomp.datumEdit.init('#EsedekessegEdit');
                mkwcomp.datumEdit.init('#HataridoEdit');
                mkwcomp.datumEdit.init('#FakeKifizetesdatumEdit');
                mkwcomp.datumEdit.init('#SZEPKartyaErvenyessegEdit');

                //valutanemChange(true);

                calcOsszesen();

                if (!$.browser.mobile && $.fn.flyout) {
                    $('.js-toflyout').flyout();
                }
            },
            beforeSerialize: function (f, o, quick) {
                if (quick) {
                    $('.js-quickmennyiseginput').each(function () {
                        if (!$(this).val()) {
                            $(this).parents('tr').remove();
                        }
                    });
                    $('input[name="tetelid[]"]').each(function () {
                        let $this = $(this),
                            parent = $this.parent(),
                            termeksorid = $this.parents('tbody').data('id');
                        parent.append('<input name="tetelnettoegysar_' + $this.val() + '" type="hidden" value="' +
                            $('input[name="qtetelnettoegysar_' + termeksorid + '"]').val() + '">');
                        parent.append('<input name="tetelbruttoegysar_' + $this.val() + '" type="hidden" value="' +
                            $('input[name="qtetelbruttoegysar_' + termeksorid + '"]').val() + '">');

                        parent.append('<input name="tetelafa_' + $this.val() + '" type="hidden" value="' + $('input[name="qtetelafa_' + termeksorid + '"]').val() + '">');

                        parent.append('<input name="tetelenettoegysar_' + $this.val() + '" type="hidden" value="' +
                            $('input[name="qtetelenettoegysar_' + termeksorid + '"]').val() + '">');
                        parent.append('<input name="tetelebruttoegysar_' + $this.val() + '" type="hidden" value="' +
                            $('input[name="qtetelebruttoegysar_' + termeksorid + '"]').val() + '">');

                        parent.append('<input name="tetelkedvezmeny_' + $this.val() + '" type="hidden" value="' +
                            $('input[name="qtetelkedvezmeny_' + termeksorid + '"]').val() + '">');
                    });
                }

                let hibak = checkBizonylatFej(bizonylattipus, $('input[name="id"]').val()),
                    tetelek = checkTetelOsszegek(),
                    osszegekok = tetelek.osszegekok,
                    egyediazonositook = checkEgyediAzonositok(),
                    egyediazonositosmennyok = checkEgyediAzonositosMennyisegek(),
                    egyediazonositoegyedi = checkEgyediAzonositoEgyediseg();
                if (!osszegekok) {
                    hibak.push('Hibás összeg a tételek között. A pirossal jelölt mezőket ellenőrizze.');
                }
                // Egyedi azonosítóval rendelkező termékek ellenőrzése.
                if (!egyediazonositook) {
                    hibak.push('Egyedi azonosítóval rendelkező termék esetén kötelező megadni az azonosítót. A pirossal jelölt mezőket ellenőrizze.');
                }
                // Egyedi azonosítóval rendelkező termékek mennyiségének ellenőrzése.
                if (!egyediazonositosmennyok) {
                    hibak.push('Egyedi azonosítóval rendelkező termék esetén a mennyiség csak 1 vagy -1 lehet. A pirossal jelölt mezőket ellenőrizze.');
                }
                // Egyedi azonosítók egyediségének ellenőrzése: ugyanazon termék több tételében az
                // azonosítóknak különbözniük kell.
                if (!egyediazonositoegyedi) {
                    hibak.push('Ugyanaz az a termék több tételben ugyanazzal az egyedi azonosítóval szerepel, az azonosítóknak különbözniük kell. A pirossal jelölt mezőket ellenőrizze.');
                }
                // Az összegyűjtött hibaüzeneteket egyszerre, <br>-rel elválasztva írjuk ki.
                if (hibak.length) {
                    $('#dialogcenter').html(hibak.join('<br>')).dialog({
                        resizable: false,
                        width: 460,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                    return false;
                }
                // ÁFA eltérés esetén a felhasználó dönt: így menti, vagy javítja az ÁFA kulcsokat.
                // Ha a bizonylaton már korábban jóváhagyták az ÁFA-ellenőrzés átlépését
                // (afaellenorzesnemkell), akkor többé nem kell az ÁFA kulcsokat ellenőrizni.
                let afaEllenorzesNemKell = $('#AfaellenorzesnemkellEdit').val() == '1';
                if (!tetelek.afaegyezik && !afaEllenorzesAtlepes && !afaEllenorzesNemKell) {
                    $('#dialogcenter').html('Egy vagy több tétel ÁFA kulcsa eltér a partnernél szükséges ÁFA kulcstól (pirossal jelölve). Így menti a bizonylatot, vagy javítja az ÁFA kulcsokat?').dialog({
                        resizable: false,
                        width: 460,
                        modal: true,
                        buttons: {
                            'Mentés így': function () {
                                afaEllenorzesAtlepes = true;
                                // jelezzük a szervernek, hogy ezen a bizonylaton többé nem kell ÁFA-ellenőrzés
                                $('#AfaellenorzesnemkellEdit').val('1');
                                $(this).dialog('close');
                                $('#mattkarb-form').submit();
                            },
                            'ÁFA kulcsok javítása': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                    return false;
                }
                afaEllenorzesAtlepes = false;
                return true;
            }
        };
    }

    function createMattable(bizonylattipus) {
        if ($.fn.mattable) {
            let dialogcenter = $('#dialogcenter'),
                datumtolfilter = $('#datumtolfilter'),
                datumigfilter = $('#datumigfilter'),
                mattableselect = $('#mattable-select');

            datumtolfilter.datepicker($.datepicker.regional['hu']);
            datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
            datumtolfilter.datepicker('setDate', datumtolfilter.attr('data-datum'));
            datumigfilter.datepicker($.datepicker.regional['hu']);
            datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
            mattableselect.mattable({
                quickAddVisible: mattableselect.data('szamlazhat'),
                addVisible: mattableselect.data('szamlazhat'),
                filter: {
                    fields: [
                        '#idfilter',
                        '#vevonevfilter',
                        '#vevoemailfilter',
                        '#vevotelefonfilter',
                        '#szallitasiirszamfilter',
                        '#szallitasivarosfilter',
                        '#szallitasiutcafilter',
                        '#szamlazasiirszamfilter',
                        '#szamlazasivarosfilter',
                        '#szamlazasiutcafilter',
                        '#datumtipusfilter',
                        '#datumtolfilter',
                        '#datumigfilter',
                        '#bizonylatstatuszfilter',
                        '#bizonylatstatuszcsoportfilter',
                        '#bizonylatrontottfilter',
                        '#fizmodfilter',
                        '#valutanemfilter',
                        '#raktarfilter',
                        '#szallitasimodfilter',
                        '#fuvarlevelszamfilter',
                        '#erbizonylatszamfilter',
                        '#uzletkotofilter',
                        '#feketelistafilter',
                        '#naveredmenyfilter',
                        '#referrerfilter',
                        '#osszegtolfilter',
                        '#osszegigfilter',
                        '#megjegyzesfilter',
                        '#termekertekeleskikuldvefilter'
                    ],
                    onClear: function () {
                        $('.js-cimkefilter').removeClass('ui-state-hover');
                    },
                    onFilter: function (obj) {
                        let cimkek = new Array();
                        $('.js-cimkefilter').filter('.ui-state-hover').each(function () {
                            cimkek.push($(this).attr('data-id'));
                        });
                        if (cimkek.length > 0) {
                            obj['cimkefilter'] = cimkek;
                        }
                    }
                },
                tablebody: {
                    url: '/admin/' + bizonylattipus + 'fej/getlistbody',
                    onStyle: function () {
                        $('.js-printbizonylat, .js-rontbizonylat, .js-stornobizonylat1, .js-stornobizonylat2, ' +
                            '.js-inheritbizonylat, .js-printelolegbekero, .js-backorder, ' +
                            '.js-feketelista, .js-vissza, .js-nav, .js-navstat, .js-pdf, .js-emailpdf, .js-email').button();
                    },
                    onDoEditLink: function () {
                        $('.js-inheritbizonylat').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                        });
                        $('.js-printbizonylat').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/' + bizonylattipus + 'fej/print?id=' + $this.data('egyedid'));
                        });
                        $('.js-pdf').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/bizonylatfej/pdf?id=' + $this.data('egyedid'));
                        });
                        $('.js-stornobizonylat1').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper') + '&stornotip=1');
                        });
                        $('.js-stornobizonylat2').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper') + '&stornotip=2');
                        });
                        $('.js-printelolegbekero').each(function () {
                            let $this = $(this);
                            $this.attr('href', '/admin/' + bizonylattipus + 'fej/printelolegbekero?id=' + $this.data('egyedid'));
                        });
                        $('.js-nav').each(function () {
                            if ($('#mattable-table').data('noversion') <= '1_1') {
                                let $this = $(this);
                                $this.attr('href', '/admin/' + bizonylattipus + 'fej/navonline?id=' + $this.data('egyedid'));
                            }
                        });
                    }
                },
                onGetTBody: function (data) {
                    if (data.sumhtml) {
                        $('.js-sumcol').html(data.sumhtml);
                    }
                },
                karb: getMattKarbConfig(bizonylattipus)
            });
            $('.mattable-batchbtn').on('click', function (e) {
                let cbs,
                    tomb = [];
                e.preventDefault();
                cbs = $('.maincheckbox:checked');
                if (cbs.length) {
                    cbs.closest('tr').each(function (index, elem) {
                        tomb.push($(elem).data('egyedid'));
                    });

                    switch ($('.mattable-batchselect').val()) {
                        case 'foxpostlabel':
                            dialogcenter.html('Biztos, hogy lekéri a megrendelés címkéket?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        let dia = $(this);
                                        $.ajax({
                                            url: '/admin/' + bizonylattipus + 'fej/generatefoxpostlabel',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function () {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                        case 'foxpostsend':
                            dialogcenter.html('Biztos, hogy elküldi a megrendeléseket?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        let dia = $(this);
                                        $.ajax({
                                            url: '/admin/' + bizonylattipus + 'fej/sendtofoxpost',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function () {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                        case 'glssend':
                            dialogcenter.html('Biztos, hogy elküldi a megrendeléseket?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        let dia = $(this);
                                        $.ajax({
                                            url: '/admin/' + bizonylattipus + 'fej/sendtogls',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function () {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                        case 'recalcprice':
                            dialogcenter.html('Biztos, hogy újra számolja a megrendelések árait?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        let dia = $(this);
                                        $.ajax({
                                            url: '/admin/' + bizonylattipus + 'fej/recalcprice',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function () {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                        case 'excelfejexport':
                            let $exportfejform = $('#exportform');
                            $exportfejform.attr('action', '/admin/' + bizonylattipus + 'fej/fejexport');
                            $('input[name="ids"]', $exportfejform).val(tomb);
                            $exportfejform.submit();
                            break;
                        case 'exceltetelexport':
                            let $exporttetelform = $('#exportform');
                            $exporttetelform.attr('action', '/admin/' + bizonylattipus + 'fej/tetelexport');
                            $('input[name="ids"]', $exporttetelform).val(tomb);
                            $exporttetelform.submit();
                            break;
                        case 'sendemailek':
                            let $dia = $('#emailsablondialog');
                            $dia.dialog({
                                title: 'Email sablon',
                                resizable: true,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        let dial = $(this),
                                            sablon = $('select[name="emailsablon"]').val();
                                        $('select[name="emailsablon"]').val('');
                                        $.ajax({
                                            url: '/admin/bizonylatfej/sendemailsablonok',
                                            type: 'POST',
                                            data: {
                                                ids: tomb,
                                                sablon: sablon
                                            },
                                            success: function () {
                                                dial.dialog('close');
                                            }
                                        });
                                    },
                                    'Mégsem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                        case 'rendelesconcat':
                            dialogcenter.html('Biztos, hogy összevonja a megrendeléseket?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        let dia = $(this);
                                        $.ajax({
                                            url: '/admin/megrendelesfej/concat',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function () {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            break;
                    }
                } else {
                    dialogcenter.html('Válasszon ki legalább egy megrendelést!').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }

            });
            $('#mattable-body')
                .on('change', '.js-bizonylatstatuszedit', function (e) {
                    e.preventDefault();

                    function sendQ(id, s, ertesit) {
                        $.ajax({
                            url: '/admin/bizonylatfej/setstatusz',
                            type: 'POST',
                            data: {
                                id: id,
                                statusz: s,
                                bizonylatstatuszertesito: ertesit
                            }
                        });
                    }

                    let $this = $(this),
                        id = $this.parents('tr').data('egyedid'),
                        statusz = $this.val();
                    dialogcenter.html('Küld email értesítést a változásról?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                sendQ(id, statusz, true);
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                sendQ(id, statusz, false);
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-rontbizonylat', function (e) {
                    let $this = $(this);
                    e.preventDefault();
                    dialogcenter.html('Biztosan rontja a bizonylatot?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/' + bizonylattipus + 'fej/ront',
                                    type: 'POST',
                                    data: {
                                        id: $this.data('egyedid')
                                    },
                                    success: function () {
                                        $('.mattable-tablerefresh').click();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-backorder', function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/' + bizonylattipus + 'fej/backorder',
                        type: 'POST',
                        data: {
                            id: $(this).data('egyedid')
                        },
                        success: function (data) {
                            let d = JSON.parse(data);
                            if (d.refresh) {
                                dialogcenter.html('A backorder rendelés elkészült.').dialog({
                                    resizable: false,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'OK': function () {
                                            $('.mattable-tablerefresh').click();
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            } else {
                                dialogcenter.html('A rendelés teljesíthető.').dialog({
                                    resizable: false,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'OK': function () {
                                            $('.mattable-tablerefresh').click();
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            }
                        }
                    });
                })
                .on('click', '.js-vissza', function (e) {
                    let $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: $this.data('href'),
                        type: 'GET',
                        success: function (d) {
                            if (d) {
                                let adat = JSON.parse(d), szoveg = '';
                                if (adat.qst) {
                                    if (adat.msg) {
                                        szoveg = szoveg + adat.msg + '<br>';
                                    }
                                    szoveg = szoveg + adat.qst;
                                    dialogcenter.html(szoveg).dialog({
                                        resizable: false,
                                        height: 140,
                                        modal: true,
                                        buttons: {
                                            'Igen': function () {
                                                let dial = $(this);
                                                $.ajax({
                                                    url: $this.data('href'),
                                                    type: 'GET',
                                                    data: {
                                                        mindenaron: 1
                                                    },
                                                    success: function () {
                                                        $('.mattable-tablerefresh').click();
                                                        dial.dialog('close');
                                                    }
                                                });
                                            },
                                            'Nem': function () {
                                                $('.mattable-tablerefresh').click();
                                                $(this).dialog('close');
                                            }
                                        }
                                    });
                                } else {
                                    if (adat.msg) {
                                        dialogcenter.html(adat.msg).dialog({
                                            resizable: false,
                                            height: 140,
                                            modal: true,
                                            buttons: {
                                                'OK': function () {
                                                    $('.mattable-tablerefresh').click();
                                                    $(this).dialog('close');
                                                }
                                            }
                                        });
                                    }
                                }
                            } else {
                                $('.mattable-tablerefresh').click();
                            }
                        }
                    });
                })
                .on('click', '.js-feketelista', function (e) {
                    let $this = $(this),
                        $dia = $('#feketelistaokdialog');
                    e.preventDefault();
                    $dia.dialog({
                        title: 'Feketelista',
                        resizable: true,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                let dial = $(this),
                                    ok = $('textarea[name="feketelistaok"]').val();
                                $('textarea[name="feketelistaok"]').val('');
                                $.ajax({
                                    url: '/admin/feketelista/add',
                                    type: 'POST',
                                    data: {
                                        email: $this.data('email'),
                                        ip: $this.data('ip'),
                                        ok: ok
                                    },
                                    success: function () {
                                        dial.dialog('close');
                                    }
                                });
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-emailpdf', function (e) {
                    let $this = $(this),
                        $dia = $('#emailpdfdialog');
                    e.preventDefault();
                    $dia.dialog({
                        title: 'Küldés emailben',
                        resizable: true,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                let dial = $(this);
                                $.ajax({
                                    url: '/admin/bizonylatfej/emailpdf',
                                    type: 'POST',
                                    data: {
                                        id: $this.data('egyedid')
                                    },
                                    success: function () {
                                        dial.dialog('close');
                                        if ($this.data('kellkerdezni') == 1) {
                                            dialogcenter.html('Sikerült a küldés?').dialog({
                                                resizable: false,
                                                height: 140,
                                                modal: true,
                                                buttons: {
                                                    'Igen': function () {
                                                        $.ajax({
                                                            url: '/admin/bizonylatfej/setnyomtatva',
                                                            type: 'POST',
                                                            data: {
                                                                id: $this.data('egyedid'),
                                                                printed: true
                                                            },
                                                            success: function (r) {
                                                                if (r) {
                                                                    $('#naverrordialog').html(r).dialog({
                                                                        resizable: true,
                                                                        height: 160,
                                                                        modal: true,
                                                                        buttons: {
                                                                            'OK': function () {
                                                                                $(this).dialog('close');
                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                                $('.mattable-tablerefresh').click();
                                                            }
                                                        });
                                                        $(this).dialog('close');
                                                    },
                                                    'Nem': function () {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                            });
                                        }
                                    }
                                });
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-email', function (e) {
                    let $this = $(this),
                        $dia = $('#emailsablondialog');
                    e.preventDefault();
                    $dia.dialog({
                        title: 'Email sablon',
                        resizable: true,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                let dial = $(this),
                                    sablon = $('select[name="emailsablon"]').val();
                                $('select[name="emailsablon"]').val('');
                                $.ajax({
                                    url: '/admin/bizonylatfej/sendemailsablon',
                                    type: 'POST',
                                    data: {
                                        id: $this.data('egyedid'),
                                        sablon: sablon
                                    },
                                    success: function () {
                                        dial.dialog('close');
                                    }
                                });
                            },
                            'Mégsem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-nav', function (e) {
                    if ($('#mattable-table').data('noversion') > '1_1') {
                        let $this = $(this),
                            $dia = $('#navdialog');
                        e.preventDefault();
                        $dia.dialog({
                            title: 'NAV beküldés',
                            resizable: true,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    let dial = $(this);
                                    $.ajax({
                                        url: '/admin/bizonylatfej/navonline',
                                        type: 'POST',
                                        data: {
                                            id: $this.data('egyedid')
                                        },
                                        success: function (r) {
                                            dial.dialog('close');
                                            if (r) {
                                                $('#naverrordialog').html(r).dialog({
                                                    resizable: true,
                                                    height: 160,
                                                    modal: true,
                                                    buttons: {
                                                        'OK': function () {
                                                            $(this).dialog('close');
                                                        }
                                                    }
                                                });
                                            }
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
                .on('click', '.js-navstat', function (e) {
                    if ($('#mattable-table').data('noversion') > '1_1') {
                        let $this = $(this),
                            $dia = $('#naverrordialog');
                        e.preventDefault();
                        $.ajax({
                            url: '/admin/bizonylatfej/navstat',
                            type: 'POST',
                            data: {
                                id: $this.data('egyedid')
                            },
                            success: function () {
                                $('#naverrordialog').html('Az eredmény megtekintéséhez frissítse az ablakot.').dialog({
                                    resizable: true,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'OK': function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            }
                        });
                    }
                })
                .on('click', '.js-folyoszamlabtn', function (e) {
                    let $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/bizonylatfej/getfolyoszamla',
                        data: {
                            bizszam: $this.data('id')
                        },
                        success: function (data) {
                            dialogcenter.html(data).dialog({
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        dialogcenter.dialog('close');
                                    }
                                }
                            });
                        }
                    });
                })
                .on('click', '.js-printbizonylat, .js-pdf', function (e) {
                    let $this = $(this);
                    e.preventDefault();
                    window.open($this.attr('href'));
                    if ($this.data('kellkerdezni') == 1) {
                        dialogcenter.html('Sikerült a nyomtatás?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/bizonylatfej/setnyomtatva',
                                        type: 'POST',
                                        data: {
                                            id: $this.data('egyedid'),
                                            printed: true
                                        },
                                        success: function (r) {
                                            if (r) {
                                                $('#naverrordialog').html(r).dialog({
                                                    resizable: true,
                                                    height: 160,
                                                    modal: true,
                                                    buttons: {
                                                        'OK': function () {
                                                            $(this).dialog('close');
                                                        }
                                                    }
                                                });
                                            }
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
                .on('click', '.js-delglsparcel', function (e) {
                    e.preventDefault();
                    let $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a csomagot a GLS-nél?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                let dia = $(this);
                                $.ajax({
                                    url: '/admin/' + bizonylattipus + 'fej/delglsparcel',
                                    type: 'POST',
                                    data: {
                                        id: $this.data('egyedid')
                                    },
                                    success: function (data) {
                                        dia.dialog('close');
                                        $('.mattable-tablerefresh').click();
                                    }
                                });
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                });

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
            $('.js-maincheckbox').change(function () {
                $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
            });
        } else {
            if ($.fn.mattkarb) {
                $('#mattkarb').mattkarb($.extend({}, getMattKarbConfig(bizonylattipus), {independent: true}));
            }
        }

    }

    return {
        createMattable: createMattable,
        getMattKarbConfig: getMattKarbConfig,
        checkTetelOsszegek: checkTetelOsszegek
    };

}(jQuery);