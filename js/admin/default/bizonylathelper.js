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
                    $('#ArfolyamEdit').val(data);
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
                    mennyiseg: $('input[name="tetelmennyiseg_' + sorId + '"]').val()
                },
                success: function(data) {
                    var resp = JSON.parse(data),
                        eb = $('#eladasibruttoar_' + sorId),
                        hasz = $('#haszonszazalek_' + sorId),
                        n = eb.data('ertek') / resp.bruttoegysar * 100 - 100;
                    $('input[name="tetelnettoegysar_' + sorId + '"]').val(resp.nettoegysar);
                    $('input[name="tetelbruttoegysar_' + sorId + '"]').val(resp.bruttoegysar);
                    $('input[name="tetelnetto_' + sorId + '"]').val(resp.netto);
                    $('input[name="tetelbrutto_' + sorId + '"]').val(resp.brutto);
                    $('input[name="tetelnettoegysarhuf_' + sorId + '"]').val(resp.nettoegysarhuf);
                    $('input[name="tetelbruttoegysarhuf_' + sorId + '"]').val(resp.bruttoegysarhuf);
                    $('input[name="tetelnettohuf_' + sorId + '"]').val(resp.nettohuf);
                    $('input[name="tetelbruttohuf_' + sorId + '"]').val(resp.bruttohuf);

                    hasz.text(n.toFixed(2));
                }
            });
        }
        nocalcarak = false;
    }

    function checkKelt(kelt, biztipus) {
        var retval = false;
        $.ajax({
            async: false,
            url: '/admin/bizonylatfej/checkkelt',
            async: false,
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
        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/valtozatlist',
            data: {
                id: id,
                tetelid: sorid,
                sel: selvaltozat
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
                if (ui.item) {
                    var $this = $(this),
                            sorid = $this.attr('name').split('_')[1],
                            vtsz = $('select[name="tetelvtsz_' + sorid + '"]'),
                            afa = $('select[name="tetelafa_' + sorid + '"]'),
                            selvaltozat = $('select[name="tetelvaltozat_' + sorid + '"]').val(),
                            valtozatplace = $('#ValtozatPlaceholder' + sorid);
                    setNoCalcArak(true);
                    valtozatplace.empty();
                    $this.siblings().val(ui.item.id);
                    $('input[name="tetelnev_' + sorid + '"]').val(ui.item.value);
                    $('input[name="tetelcikkszam_' + sorid + '"]').val(ui.item.cikkszam);
                    $('input[name="tetelme_' + sorid + '"]').val(ui.item.me);
                    vtsz.val(ui.item.vtsz);
                    vtsz.change();
                    afa.val(ui.item.afa);
                    afa.change();
                    kepsor = $('.js-termekpicturerow_' + sorid);
                    $('.js-toflyout', kepsor).attr('href', ui.item.mainurl + ui.item.kepurl);
                    $('.js-toflyout img', kepsor).attr('src', ui.item.mainurl + ui.item.kiskepurl);
                    $('.js-termeklink', kepsor).attr('href', ui.item.link).html(ui.item.link);
                    loadValtozatList(ui.item.id, sorid, selvaltozat, valtozatplace);
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
                        dialogcenter = $('#dialogcenter');

                $('#PartnerEdit').change(function() {
                    var pe = $(this);
                    $.ajax({
                        url: '/admin/partner/getdata',
                        type: 'GET',
                        data: {
                            partnerid: pe.val()
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            if (d.fizmod) {
                                fizmodedit.val(d.fizmod);
                            }
                            if (d.valutanem) {
                                $('#ValutanemEdit').val(d.valutanem);
                            }
                            if (d.szallitasimod) {
                                $('#SzallitasimodEdit').val(d.szallitasimod);
                            }
                            $('input[name="partnernev"]').val(d.nev);
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
                            setDates();
                            valutanemChange();
                        }
                    });
                });
                $('#ValutanemEdit').change(function() {
                    valutanemChange();
                });
                fizmodedit.on('change', function() {
                    setDates();
                });
                alttab.on('click', '.js-tetelnewbutton', function(e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/bizonylattetel/getemptyrow',
                        data: {
                            type: bizonylattipus
                        },
                        type: 'GET',
                        success: function(data) {
                            alttab.append(data);
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
                });

                $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                    .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;

                $('.js-tetelnewbutton,.js-teteldelbutton,.js-inheritbizonylat').button();

                $('.js-inheritbizonylat').each(function() {
                    var $this = $(this);
                    $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                });

                keltedit.datepicker($.datepicker.regional['hu']);
                keltedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                keltedit.datepicker('setDate', keltedit.attr('data-datum'));
                keltedit.on('change', function(e) {
                    setDates();
                });
                teljesitesedit.datepicker($.datepicker.regional['hu']);
                teljesitesedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                teljesitesedit.datepicker('setDate', teljesitesedit.attr('data-datum'));
                esedekessegedit.datepicker($.datepicker.regional['hu']);
                esedekessegedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                esedekessegedit.datepicker('setDate', esedekessegedit.attr('data-datum'));
                hatidoedit.datepicker($.datepicker.regional['hu']);
                hatidoedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                hatidoedit.datepicker('setDate', hatidoedit.attr('data-datum'));

                valutanemChange(true);

                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            beforeSerialize: function() {
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
                        '#bizonylatrontottfilter',
                        '#fizmodfilter',
                        '#szallitasimodfilter',
                        '#fuvarlevelszamfilter',
                        '#erbizonylatszamfilter'
                    ]
                },
                tablebody: {
                    url: '/admin/' + bizonylattipus + 'fej/getlistbody',
                    onStyle: function() {
                        $('.js-printbizonylat, .js-rontbizonylat, .js-stornobizonylat, .js-inheritbizonylat, .js-printelolegbekero, .js-otpayrefund, .js-otpaystorno').button();
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
                        $('.js-stornobizonylat').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + $this.data('egyednev') + '/viewkarb?id=' + $this.data('egyedid') + '&source=' + bizonylattipus + '&oper=' + $this.data('oper'));
                        });
                        $('.js-printelolegbekero').each(function() {
                            var $this = $(this);
                            $this.attr('href', '/admin/' + bizonylattipus + 'fej/printelolegbekero?id=' + $this.data('egyedid'));
                        });
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
                }

            });
            $('#mattable-body')
            .on('change', '.js-bizonylatstatuszedit', function(e) {
                e.preventDefault();
                function sendQ(id, s, ertesit) {
                    $.ajax({
                        url: '/admin/' + bizonylattipus + 'fej/setstatusz',
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