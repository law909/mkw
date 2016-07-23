var bizonylathelper = function($) {

    var nocalcarak = false;

    function setDates() {
        var keltedit = $('#KeltEdit'),
                esededit = $('#EsedekessegEdit'),
                kelt = keltedit.datepicker('getDate');
        $.ajax({
            url: '/admin/bizonylatfej/calcesedekesseg',
            data: {
                kelt: kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate(),
                fizmod: $('#FizmodEdit option:selected').val(),
                partner: $('#PartnerEdit option:selected').val()
            },
            success: function(data) {
                var d = JSON.parse(data);
                esededit.datepicker('setDate', d.esedekesseg);
            }
        });
    }

    function calcOsszesen() {
        var netto = 0, brutto = 0, nettohuf = 0, bruttohuf = 0;
        $('input[name^="tetelnetto_"]').each(function() {
            netto = netto + $(this).val() * 1;
        });
        $('input[name^="tetelbrutto_"]').each(function() {
            brutto = brutto + $(this).val() * 1;
        });
        $('input[name^="tetelnettohuf_"]').each(function() {
            nettohuf = nettohuf + $(this).val() * 1;
        });
        $('input[name^="tetelbruttohuf_"]').each(function() {
            bruttohuf = bruttohuf + $(this).val() * 1;
        });

        // quick
        $('.js-quickmennyiseginput').each(function() {
            var $this = $(this),
                id = $this.data('termektetelid');
            netto = netto + $('input[name="qtetelnettoegysar_' + id + '"]').val() * $this.val() * 1;
            brutto = brutto + $('input[name="qtetelbruttoegysar_' + id + '"]').val() * $this.val() * 1;
            nettohuf = nettohuf + $('input[name="qtetelnettoegysarhuf_' + id + '"]').val() * $this.val() * 1;
            bruttohuf = bruttohuf + $('input[name="qtetelbruttoegysarhuf_' + id + '"]').val() * $this.val() * 1;
        });

        $('.js-nettosum').text(accounting.formatNumber(tools.round(netto, -2), 2, ' '));
        $('.js-bruttosum').text(accounting.formatNumber(tools.round(brutto, -2), 2, ' '));
        $('.js-nettohufsum').text(accounting.formatNumber(tools.round(nettohuf, -2), 2, ' '));
        $('.js-bruttohufsum').text(accounting.formatNumber(tools.round(bruttohuf, -2), 2, ' '));
    }

    function recalcHufPrices(arfolyam) {
        $('.js-quicknettoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelnettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickbruttoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelbruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickenettoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelenettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-quickebruttoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="qtetelebruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-nettoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelnettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-bruttoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelbruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-enettoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelenettoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-ebruttoegysarinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelebruttoegysarhuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-nettoinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelnettohuf_' + id + '"]').val($this.val() * arfolyam);
        });
        $('.js-bruttoinput').each(function() {
            var $this = $(this),
                id = $this.attr('name').split('_')[1];
            $('input[name="tetelbruttohuf_' + id + '"]').val($this.val() * arfolyam);
        });
        calcOsszesen();
    }

    function getArfolyam() {
        var d = $('#TeljesitesEdit').datepicker('getDate');
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
                success: function(data) {
                    var arfolyam = data * 1;
                    $('#ArfolyamEdit').val(data);
                    recalcHufPrices(arfolyam);
                }
            });
        }
    }

    function setTermekAr(sorId) {
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                valutanem: $('#ValutanemEdit').val(),
                partner: $('#PartnerEdit').val(),
                termek: $('input[name="teteltermek_' + sorId + '"]').val(),
                valtozat: $('select[name="tetelvaltozat_' + sorId + '"]').val()
            },
            success: function(data) {
                var c = $('input[name="tetelnettoegysar_' + sorId + '"]'),
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
                success: function(data) {
                    var resp = JSON.parse(data),
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
        var kedv = $('input[name="tetelkedvezmeny_' + sorId + '"]').val() * 1;
        $('input[name="tetelnettoegysar_' + sorId + '"]').val($('input[name="tetelenettoegysar_' + sorId + '"]').val() * (100 - kedv) / 100);
        calcArak(sorId);
    }

    function checkKelt(kelt, biztipus) {
        var retval = false;
        $.ajax({
            async: false,
            url: '/admin/bizonylatfej/checkkelt',
            data: {
                kelt: kelt,
                biztipus: biztipus
            },
            success: function(data) {
                var d = JSON.parse(data);
                if (d.response == 'ok') {
                    retval = true;
                }
            }
        });
        return retval;
    }

    function checkBizonylatFej(biztipus) {
        var keltedit = $('#KeltEdit'),
                dialogcenter = $('#dialogcenter'),
                keltchanged = keltedit.attr('data-datum') != keltedit.val(),
                keltok = (!keltchanged) || (keltchanged && checkKelt($('#KeltEdit').val(), biztipus)),
                tetelok = ($('.js-termekid').length !== 0) && ($('.js-termekid[value=""]').length === 0) && ($('.js-termekid[value="0"]').length === 0),
                ret = keltok && tetelok;
        if (!keltok) {
            dialogcenter.html('Már van későbbi keltű bizonylat.').dialog({
                resizable: false,
                height: 140,
                modal: true,
                buttons: {
                    'OK': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }
        else {
            if (!tetelok) {
                dialogcenter.html('Nincsenek tételek a bizonylaton.').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'OK': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            }
        }
        return ret;
    }

    function loadValtozatList(id, sorid, selvaltozat, valtozatplace) {
        var raktarid = $('select[name="raktar"] option:selected').val();
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/valtozatlist',
            data: {
                id: id,
                tetelid: sorid,
                sel: selvaltozat,
                raktar: raktarid
            },
            success: function(data) {
                var d = JSON.parse(data);
                if (d.db) {
                    $(d.html).appendTo(valtozatplace);
                }
                else {
                    setTermekAr(sorid);
                }
            }
        });
    }

    function setNoCalcArak(n) {
        nocalcarak = n;
    }

    function termekAutocompleteRenderer(ul, item) {
        if (item.nemlathato) {
            return $('<li>')
                .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
                .appendTo( ul );
        }
        else {
            return $('<li>')
                .append('<a>' + item.label + '</a>')
                .appendTo( ul );
        };
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function(event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this),
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
                    $('input[name="tetelme_' + sorid + '"]').val(termek.me);
                    if (!$('input[name="tetelmennyiseg_' + sorid + '"]').val() && termek.defaultmennyiseg) {
                        $('input[name="tetelmennyiseg_' + sorid + '"]').val(termek.defaultmennyiseg);
                    }
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
        };
    }

    function quicksetTermekAr(sorId) {
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                valutanem: $('#ValutanemEdit').val(),
                partner: $('#PartnerEdit').val(),
                termek: $('input[name="qteteltermek_' + sorId + '"]').val(),
                valtozat: $('select[name="qtetelvaltozat_' + sorId + '"]').val()
            },
            success: function(data) {
                var c = $('input[name="qtetelnettoegysar_' + sorId + '"]'),
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
                success: function(data) {
                    var resp = JSON.parse(data);
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
        var kedv = $('input[name="qtetelkedvezmeny_' + sorId + '"]').val() * 1;
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
            success: function(data) {
                var d = JSON.parse(data);
                $('#valtozattable_' + d.tetelid).html(d.html);
            }
        });
    }

    function quicktermekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function(event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this),
                        sorid = $this.attr('name').split('_')[1],
                        partneredit = $('#PartnerEdit');
                    if (partneredit.data('afa')) {
                        termek.afa = partneredit.data('afa');
                        termek.afakulcs = partneredit.data('afakulcs');
                    }
                    $('input[name="qteteltermek_' + sorid + '"]').val(termek.id);
                    $('input[name="qtetelcikkszam_' + sorid + '"]').val(termek.cikkszam);
                    $('input[name="qtetelafa_' + sorid + '"]').val(termek.afa).data('afakulcs', termek.afakulcs);
                    $('input[name="qtetelme_' + sorid + '"]').val(termek.me);
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
        if (!firstrun || $('input[name="oper"]').val()==='add') {
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
        $('input[name="partneradoszam"]').val(d.adoszam);
        $('input[name="szallnev"]').val(d.szallnev);
        $('input[name="szallirszam"]').val(d.szallirszam);
        $('input[name="szallvaros"]').val(d.szallvaros);
        $('input[name="szallutca"]').val(d.szallutca);
        $('input[name="partnertelefon"]').val(d.telefon);
        $('input[name="partneremail"]').val(d.email);
        $('#PartnerEdit').data('afa', d.afa).data('afakulcs', d.afakulcs);
        setDates();
        valutanemChange();
    }

    function getMattKarbConfig(bizonylattipus) {
        return {
            container: '#mattkarb',
            viewUrl: '/admin/' + bizonylattipus + 'fej/getkarb',
            newWindowUrl: '/admin/' + bizonylattipus + 'fej/viewkarb',
            saveUrl: '/admin/' + bizonylattipus + 'fej/save',
            beforeShow: function() {
                var keltedit = $('#KeltEdit'),
                        teljesitesedit = $('#TeljesitesEdit'),
                        esedekessegedit = $('#EsedekessegEdit'),
                        hatidoedit = $('#HataridoEdit'),
                        fizmodedit = $('#FizmodEdit'),
                        alttab = $('#AltalanosTab'),
                        dialogcenter = $('#dialogcenter'),
                        fakekifizetesdatumedit = $('#FakeKifizetesdatumEdit');

                $('#EmailEdit').change(function() {
                    var pedit = $('#PartnerEdit'),
                        ee = $(this);
                    if (pedit.val() == -1) {
                        $.ajax({
                            url: '/admin/partner/getdata',
                            type: 'GET',
                            data: {
                                email: ee.val()
                            },
                            success: function(data) {
                                var d = JSON.parse(data);
                                if (d.id) {
                                    setPartnerData(d);
                                    pedit.val(d.id);
                                }
                            }
                        });
                    }
                });

                $('#PartnerEdit').change(function() {
                    var pe = $(this);
                    if (pe.val() > 0) {
                        $.ajax({
                            url: '/admin/partner/getdata',
                            type: 'GET',
                            data: {
                                partnerid: pe.val()
                            },
                            success: function(data) {
                                var d = JSON.parse(data);
                                setPartnerData(d);
                            }
                        });
                    }
                });
                $('#ValutanemEdit').change(function() {
                    valutanemChange();
                });
                fizmodedit.on('change', function() {
                    setDates();
                });
                alttab
                .on('click', '.js-quicktetelnewbutton', function(e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/bizonylattetel/getquickemptyrow',
                        data: {
                            type: bizonylattipus
                        },
                        type: 'GET',
                        success: function(data) {
                            $('.js-bizonylatosszesito').before(data);
                            $('.js-quicktetelnewbutton,.js-teteldelbutton').button();
                            $('.js-termekselect').autocomplete(quicktermekAutocompleteConfig())
                                .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-tetelnewbutton', function(e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/bizonylattetel/getemptyrow',
                        data: {
                            type: bizonylattipus
                        },
                        type: 'GET',
                        success: function(data) {
                            $('.js-bizonylatosszesito').before(data);
                            $('.js-tetelnewbutton,.js-teteldelbutton').button();
                            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                                .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-teteldelbutton', function(e) {
                    e.preventDefault();
                    var removegomb = $(this),
                            removeid = removegomb.attr('data-id');
                    if (removegomb.attr('data-source') == 'client') {
                        dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function() {
                                    $('#teteltable_' + removeid).remove();
                                    calcOsszesen();
                                    $(this).dialog('close');
                                },
                                'Nem': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                    else {
                        dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function() {
                                    $.ajax({
                                        url: '/admin/bizonylattetel/save',
                                        type: 'POST',
                                        data: {
                                            id: removeid,
                                            oper: 'del'
                                        },
                                        success: function(data) {
                                            $('#teteltable_' + data).remove();
                                            calcOsszesen();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
                .on('change', '.js-vtszselect', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var sorid = $this.attr('name').split('_')[1],
                            valasztott = $('option:selected', $this);
                    var afa = $('select[name="tetelafa_' + sorid + '"]');
                    afa.val(valasztott.data('afa'));
                    afa.change();
                })
                .on('change', '#ArfolyamEdit', function(e) {
                    e.preventDefault();
                    recalcHufPrices($(this).val() * 1);
                })
                .on('change', '.js-afaselect', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    calcArak(sorid);
                })
                .on('change', '.js-nettoegysarinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    calcArak(sorid);
                })
                .on('change', '.js-bruttoegysarinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    var afakulcs = $('select[name="tetelafa_' + sorid + '"] option:selected').data('afakulcs');
                    var n = $('input[name="tetelnettoegysar_' + sorid + '"]');
                    n.val($(this).val() / (100 + afakulcs) * 100);
                    n.change();
                })
                .on('change', '.js-quicknettoegysarinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    quickcalcArak(sorid);
                })
                .on('change', '.js-quickbruttoegysarinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    var afakulcs = $('input[name="qtetelafa_' + sorid + '"]').data('afakulcs');
                    var n = $('input[name="qtetelnettoegysar_' + sorid + '"]');
                    n.val($(this).val() / (100 + afakulcs) * 100);
                    n.change();
                })
                .on('change', '.js-nettoinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    var n = $('input[name="tetelnettoegysar_' + sorid + '"]');
                    n.val($(this).val() / $('input[name="tetelmennyiseg_' + sorid + '"]').val());
                    n.change();
                })
                .on('change', '.js-bruttoinput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    var afakulcs = $('select[name="tetelafa_' + sorid + '"] option:selected').data('afakulcs');
                    var n = $('input[name="tetelnetto_' + sorid + '"]');
                    n.val($(this).val() / (100 + afakulcs) * 100);
                    n.change();
                })
                .on('change', '.js-mennyiseginput', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    calcArak(sorid);
                })
                .on('change', '.js-tetelvaltozat', function(e) {
                    e.preventDefault();
                    var sorid = $(this).attr('name').split('_')[1];
                    setTermekAr(sorid);
                })
                .on('change', '.js-bizonylatstatuszedit', function(e) {
                    $('input[name="bizonylatstatuszertesito"]').prop('checked', true);
                })
                .on('change', '.js-quickmennyiseginput', function(e) {
                    calcOsszesen();
                })
                .on('change', '.js-kedvezmeny', function(e) {
                    calcKedvezmeny($(this).attr('name').split('_')[1]);
                })
                .on('change', '.js-quickkedvezmeny', function(e) {
                    quickcalcKedvezmeny($(this).attr('name').split('_')[1]);
                });

                $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                    .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;

                $('.js-tetelnewbutton,.js-teteldelbutton,.js-inheritbizonylat,.js-quicktetelnewbutton,.js-backorder').button();

                $('.js-inheritbizonylat').each(function() {
                    var $this = $(this);
                    $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                });

                keltedit.datepicker($.datepicker.regional['hu']);
                keltedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                keltedit.datepicker('setDate', keltedit.attr('data-datum'));
                keltedit.on('change', function(e) {
                    setDates();
                    valutanemChange();
                });
                teljesitesedit.datepicker($.datepicker.regional['hu']);
                teljesitesedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                teljesitesedit.datepicker('setDate', teljesitesedit.attr('data-datum'));
                teljesitesedit.on('change', function() {
                    getArfolyam();
                });
                esedekessegedit.datepicker($.datepicker.regional['hu']);
                esedekessegedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                esedekessegedit.datepicker('setDate', esedekessegedit.attr('data-datum'));
                hatidoedit.datepicker($.datepicker.regional['hu']);
                hatidoedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                hatidoedit.datepicker('setDate', hatidoedit.attr('data-datum'));
                fakekifizetesdatumedit.datepicker($.datepicker.regional['hu']);
                fakekifizetesdatumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                fakekifizetesdatumedit.datepicker('setDate', fakekifizetesdatumedit.attr('data-datum'));

                //valutanemChange(true);

                calcOsszesen();

                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            beforeSerialize: function(f, o, quick) {
                if (quick) {
                    var partneredit = $('#PartnerEdit');
                    $('.js-quickmennyiseginput').each(function() {
                        if (!$(this).val()) {
                            $(this).parents('tr').remove();
                        }
                    });
                    $('input[name="tetelid[]"]').each(function() {
                        var $this = $(this),
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
                return checkBizonylatFej(bizonylattipus);
            },
            onSubmit: function() {
                $('#messagecenter')
                        .html('A mentés sikerült.')
                        .hide()
                        .addClass('matt-messagecenter ui-widget ui-state-highlight')
                        .one('click', messagecenterclick)
                        .slideToggle('slow');
            }
        };
    }

    function createMattable(bizonylattipus) {
        if ($.fn.mattable) {
            var dialogcenter = $('#dialogcenter'),
            datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter');

            datumtolfilter.datepicker($.datepicker.regional['hu']);
            datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
            datumtolfilter.datepicker('setDate', datumtolfilter.attr('data-datum'));
            datumigfilter.datepicker($.datepicker.regional['hu']);
            datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
            $('#mattable-select').mattable({
                quickAddVisible: true,
                filter: {
                    fields: [
                        '#idfilter',
                        '#vevonevfilter',
                        '#vevoemailfilter',
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
                        '#referrerfilter'
                    ],
                    onClear:function() {
                        $('.js-cimkefilter').removeClass('ui-state-hover');
                    },
                    onFilter:function(obj) {
                        var cimkek = new Array();
                        $('.js-cimkefilter').filter('.ui-state-hover').each(function() {
                            cimkek.push($(this).attr('data-id'));
                        });
                        if (cimkek.length>0) {
                            obj['cimkefilter'] = cimkek;
                        }
                    }
                },
                tablebody: {
                    url: '/admin/' + bizonylattipus + 'fej/getlistbody',
                    onStyle: function() {
                        $('.js-printbizonylat, .js-rontbizonylat, .js-stornobizonylat1, .js-stornobizonylat2, ' +
                            '.js-inheritbizonylat, .js-printelolegbekero, .js-otpayrefund, .js-otpaystorno, .js-backorder, .js-mese, '+
                            '.js-feketelista').button();
                    },
                    onDoEditLink: function() {
                        $('.js-inheritbizonylat').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                        });
                        $('.js-printbizonylat').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + bizonylattipus + 'fej/print?id=' + $this.data('egyedid'));
                        });
                        $('.js-stornobizonylat1').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper') + '&stornotip=1');
                        });
                        $('.js-stornobizonylat2').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper') + '&stornotip=2');
                        });
                        $('.js-printelolegbekero').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + bizonylattipus + 'fej/printelolegbekero?id=' + $this.data('egyedid'));
                        });
                    }
                },
                onGetTBody: function(data) {
                    if (data.sumhtml) {
                        $('.js-sumcol').html(data.sumhtml);
                    }
                },
                karb: getMattKarbConfig(bizonylattipus)
            });
            $('.mattable-batchbtn').on('click', function(e) {
                var cbs;
                e.preventDefault();
                switch ($('.mattable-batchselect').val()) {
                    case 'foxpostsend':
                        cbs = $('.maincheckbox:checked');
                        if (cbs.length) {
                            dialogcenter.html('Biztos, hogy elküldi a megrendeléseket?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function() {
                                        var tomb = [],
                                                dia = $(this);
                                        cbs.closest('tr').each(function(index, elem) {
                                            tomb.push($(elem).data('egyedid'));
                                        });
                                        $.ajax({
                                            url: '/admin/' + bizonylattipus + 'fej/sendtofoxpost',
                                            type: 'POST',
                                            data: {
                                                ids: tomb
                                            },
                                            success: function() {
                                                dia.dialog('close');
                                                $('.mattable-tablerefresh').click();
                                            }
                                        });
                                    },
                                    'Nem': function() {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        else {
                            dialogcenter.html('Válasszon ki legalább egy megrendelést!').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        break;
                    case 'excelfejexport':
                        cbs = $('.maincheckbox:checked');
                        if (cbs.length) {
                            var tomb = [],
                                $exportform = $('#exportform');
                            cbs.closest('tr').each(function(index, elem) {
                                tomb.push($(elem).data('egyedid'));
                            });
                            $exportform.attr('action', '/admin/' + bizonylattipus + 'fej/fejexport');
                            $('input[name="ids"]', $exportform).val(tomb);
                            $exportform.submit();
                        }
                        else {
                            dialogcenter.html('Válasszon ki legalább egy bizonylatot!').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        break;
                    case 'exceltetelexport':
                        cbs = $('.maincheckbox:checked');
                        if (cbs.length) {
                            var tomb = [],
                                $exportform = $('#exportform');
                            cbs.closest('tr').each(function(index, elem) {
                                tomb.push($(elem).data('egyedid'));
                            });
                            $exportform.attr('action', '/admin/' + bizonylattipus + 'fej/tetelexport');
                            $('input[name="ids"]', $exportform).val(tomb);
                            $exportform.submit();
                        }
                        else {
                            dialogcenter.html('Válasszon ki legalább egy bizonylatot!').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        break;
                }

            });
            $('#mattable-body')
            .on('change', '.js-bizonylatstatuszedit', function(e) {
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
                var $this = $(this),
                    id = $this.parents('tr').data('egyedid'),
                    statusz = $this.val();
                dialogcenter.html('Küld email értesítést a változásról?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function() {
                            sendQ(id, statusz, true);
                            $(this).dialog('close');
                        },
                        'Nem': function() {
                            sendQ(id, statusz, false);
                            $(this).dialog('close');
                        }
                    }
                });
            })
            .on('click', '.js-otpayrefund', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/otpay/refund',
                    type: 'POST',
                    data: {
                        id: $this.data('egyedid')
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        if (d) {
                            alert(d);
                        }
                    }
                });
            })
            .on('click', '.js-otpaystorno', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/otpay/storno',
                    type: 'POST',
                    data: {
                        id: $this.data('egyedid')
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        if (d) {
                            alert(d);
                        }
                    }
                });
            })
            .on('click', '.js-rontbizonylat', function(e) {
                e.preventDefault();
                $.ajax({
                    url:'/admin/' + bizonylattipus + 'fej/ront',
                    type: 'POST',
                    data: {
                        id: $(this).data('egyedid')
                    },
                    success:function() {
                        $('.mattable-tablerefresh').click();
                    }
                });
            })
            .on('click', '.js-backorder', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/' + bizonylattipus + 'fej/backorder',
                    type: 'POST',
                    data: {
                        id: $(this).data('egyedid')
                    },
                    success:function(data) {
                        var d = JSON.parse(data);
                        if (d.refresh) {
                            dialogcenter.html('A backorder rendelés elkészült.').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        $('.mattable-tablerefresh').click();
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        else {
                            dialogcenter.html('A rendelés teljesíthető.').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        $('.mattable-tablerefresh').click();
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    }
                });
            })
            .on('click', '.js-mese', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: $this.data('href'),
                    type: 'GET',
                    success: function(d) {
                        if (d) {
                            var adat = JSON.parse(d), szoveg = '';
                            if (adat.qst) {
                                if (adat.msg) {
                                    szoveg = szoveg + adat.msg + '<br>';
                                }
                                szoveg = szoveg + adat.msg;
                                dialogcenter.html(szoveg).dialog({
                                    resizable: false,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'Igen': function() {
                                            var dial = $(this);
                                            $.ajax({
                                                url: $this.data('href'),
                                                type: 'GET',
                                                data: {
                                                    mindenaron: 1
                                                },
                                                success: function() {
                                                    $('.mattable-tablerefresh').click();
                                                    dial.dialog('close');
                                                }
                                            });
                                        },
                                        'Nem': function() {
                                            $('.mattable-tablerefresh').click();
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            }
                            else {
                                if (adat.msg) {
                                    dialogcenter.html(adat.msg).dialog({
                                        resizable: false,
                                        height: 140,
                                        modal: true,
                                        buttons: {
                                            'OK': function() {
                                                $('.mattable-tablerefresh').click();
                                                $(this).dialog('close');
                                            }
                                        }
                                    });
                                }
                            }
                        }
                        else {
                            $('.mattable-tablerefresh').click();
                        }
                    }
                });
            })
            .on('click', '.js-feketelista', function(e) {
                var $this = $(this),
                    $dia = $('#feketelistaokdialog');
                e.preventDefault();
                $dia.dialog({
                    title: 'Feketelista',
                    resizable: true,
                    height: 140,
                    modal: true,
                    buttons: {
                        'OK': function() {
                            var dial = $(this),
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
                                success: function() {
                                    dial.dialog('close');
                                }
                            });
                        },
                        'Mégsem': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            })
            .on('click', '.js-printbizonylat', function(e) {
                    var $this = $(this);
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
                                        success: function() {
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
                });
            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
            $('.js-maincheckbox').change(function() {
                $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
            });
        }
        else {
            if ($.fn.mattkarb) {
                $('#mattkarb').mattkarb($.extend({}, getMattKarbConfig(bizonylattipus), {independent: true}));
            }
        }

    }

    return {
        createMattable: createMattable
    };

}(jQuery);