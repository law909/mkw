$(document).ready(function() {
    var dialogcenter = $('#dialogcenter'),
    termekautocomplete = {
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
                valtozatplace.empty();
                $this.siblings().val(ui.item.id);
                $('input[name="tetelnev_' + sorid + '"]').val(ui.item.value);
                $('input[name="tetelcikkszam_' + sorid + '"]').val(ui.item.cikkszam);
                $('input[name="tetelme_' + sorid + '"]').val(ui.item.me);
//                        bizonylathelper.setTermekAr(sorid);
                vtsz.val(ui.item.vtsz);
                vtsz.change();
                afa.val(ui.item.afa);
                afa.change();
                kepsor = $('.js-termekpicturerow_' + sorid);
                $('.js-toflyout', kepsor).attr('href', ui.item.mainurl + ui.item.kepurl);
                $('.js-toflyout img', kepsor).attr('src', ui.item.mainurl + ui.item.kiskepurl);
                $('.js-termeklink', kepsor).attr('href', ui.item.link).html(ui.item.link);
                bizonylathelper.loadValtozatList(ui.item.id, sorid, selvaltozat, valtozatplace);
            }
        }
    },
    szamla = {
        container: '#mattkarb',
        viewUrl: '/admin/szamlafej/getkarb',
        newWindowUrl: '/admin/szamlafej/viewkarb',
        saveUrl: '/admin/szamlafej/save',
        beforeShow: function() {
            var keltedit = $('#KeltEdit'),
                    teljesitesedit = $('#TeljesitesEdit'),
                    esedekessegedit = $('#EsedekessegEdit'),
                    hatidoedit = $('#HataridoEdit'),
                    fizmodedit = $('#FizmodEdit'),
                    bankszamlaedit = $('#BankszamlaEdit'),
                    alttab = $('#AltalanosTab');
            $('#PartnerEdit').change(function() {
                var pe = $(this);
                $.ajax({
                    asyn: false,
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
                    }
                });
            });
            $('#ValutanemEdit').change(function() {
                bankszamlaedit.val($('option:selected', this).data('bankszamla'));
                bizonylathelper.getArfolyam();
            });
            alttab.on('click', '.js-tetelnewbutton', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/bizonylattetel/getemptyrow',
                    type: 'GET',
                    success: function(data) {
                        var tbody = $('#RecepturaTab');
                        alttab.append(data);
                        $('.js-tetelnewbutton,.js-teteldelbutton').button();
                        $('.js-termekselect').autocomplete(termekautocomplete);
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
                        bizonylathelper.calcArak(sorid);
                    })
                    .on('change', '.js-nettoegysarinput', function(e) {
                        e.preventDefault();
                        var sorid = $(this).attr('name').split('_')[1];
                        bizonylathelper.calcArak(sorid);
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
                        bizonylathelper.calcArak(sorid);
                    })
                    .on('change', '.js-tetelvaltozat', function(e) {
                        e.preventDefault();
                        var sorid = $(this).attr('name').split('_')[1];
                        bizonylathelper.setTermekAr(sorid);
                    });
            $('.js-termekselect').autocomplete(termekautocomplete);
            $('.js-tetelnewbutton,.js-teteldelbutton').button();
            keltedit.datepicker($.datepicker.regional['hu']);
            keltedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            keltedit.datepicker('setDate', keltedit.attr('data-datum'));
            teljesitesedit.datepicker($.datepicker.regional['hu']);
            teljesitesedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            teljesitesedit.datepicker('setDate', teljesitesedit.attr('data-datum'));
            esedekessegedit.datepicker($.datepicker.regional['hu']);
            esedekessegedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            esedekessegedit.datepicker('setDate', esedekessegedit.attr('data-datum'));
            hatidoedit.datepicker($.datepicker.regional['hu']);
            hatidoedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            hatidoedit.datepicker('setDate', hatidoedit.attr('data-datum'));
            if (!$.browser.mobile) {
                $('.js-toflyout').flyout();
            }
        },
        beforeSerialize: function() {
            return bizonylathelper.checkBizonylatFej('szamla', dialogcenter);
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

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#idfilter']
            },
            tablebody: {
                url: '/admin/szamlafej/getlistbody'
            },
            karb: szamla
        });
        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, szamla, {independent: true}));
        }
    }
});
