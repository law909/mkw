var bizonylathelper = function($) {

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
        if (!d) {
            d = $('#KeltEdit').datepicker('getDate');
        }
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
                var c = $('input[name="tetelnettoegysar_' + sorId + '"]');
                c.val(data);
                c.change();
            }
        });
    }

    function calcArak(sorId) {
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
                var resp = JSON.parse(data);
                $('input[name="tetelnettoegysar_' + sorId + '"]').val(resp.nettoegysar);
                $('input[name="tetelbruttoegysar_' + sorId + '"]').val(resp.bruttoegysar);
                $('input[name="tetelnetto_' + sorId + '"]').val(resp.netto);
                $('input[name="tetelbrutto_' + sorId + '"]').val(resp.brutto);
                $('input[name="tetelnettoegysarhuf_' + sorId + '"]').val(resp.nettoegysarhuf);
                $('input[name="tetelbruttoegysarhuf_' + sorId + '"]').val(resp.bruttoegysarhuf);
                $('input[name="tetelnettohuf_' + sorId + '"]').val(resp.nettohuf);
                $('input[name="tetelbruttohuf_' + sorId + '"]').val(resp.bruttohuf);
            }
        });
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

    function checkBizonylatFej(biztipus, dialogcenter) {
        var keltedit = $('#KeltEdit'),
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

    return {
        setDates: setDates,
        getArfolyam: getArfolyam,
        setTermekAr: setTermekAr,
        calcArak: calcArak,
        checkKelt: checkKelt,
        checkBizonylatFej: checkBizonylatFej,
        loadValtozatList: loadValtozatList
    };

}(jQuery);