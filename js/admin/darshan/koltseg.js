function koltseg() {

    var dialogcenter = $('#dialogcenter'),
        koltsegform = $('#KoltsegForm');

    function setPartnerData(d) {
        $('input[name="partnervezeteknev"]', koltsegform).val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]', koltsegform).val(d.keresztnev);
        $('input[name="partnerirszam"]', koltsegform).val(d.irszam);
        $('input[name="partnervaros"]', koltsegform).val(d.varos);
        $('input[name="partnerutca"]', koltsegform).val(d.utca);
        $('input[name="partnertelefon"]', koltsegform).val(d.telefon);
        $('input[name="partneremail"]', koltsegform).val(d.email);
    }

    function setTermekAr() {
        var partner = $('select[name="partner"] option:selected', koltsegform).val();

        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                partner: partner,
                termek: $('select[name="termek"] option:selected', koltsegform).val()
            },
            success: function(data) {
                var inp = $('input[name="egysegar"]', koltsegform),
                    adat = JSON.parse(data);
                inp.val(adat.brutto);
                inp.change();
            }
        });
    }

    function calcErtek() {
        var menny = $('input[name="mennyiseg"]', koltsegform).val() * 1,
            ear = $('input[name="egysegar"]', koltsegform).val() * 1;
        $('.js-ertek', koltsegform).text(menny * ear);
        $('input[name="penz"]', koltsegform).val(menny * ear);
    }

    function isKeszpenz() {
        var fm = $('#KtgFizmodEdit option:selected');
        return fm.data('tipus') === 'P';
    }

    function init() {
        mkwcomp.datumEdit.init('#KtgKeltEdit');
        mkwcomp.datumEdit.init('#KtgTeljesitesEdit');
        mkwcomp.datumEdit.init('#KtgEsedekessegEdit');
        mkwcomp.datumEdit.init('#KtgPenzdatumEdit');
        $('#KtgOKButton, #KtgCancelButton').button();

        koltsegform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
                $.blockUI({
                    message: 'Kérem várjon...',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
                return true;
            },
            success: function(data) {
                clearForm();
                dialogcenter.html('A mentés sikerült.');
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }
        });

        koltsegform
            .on('change', '#KtgPenztarEdit', function(e) {
                var v = $('#KtgPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', koltsegform).val(v);
            })
            .on('change', '#KtgPartnerEdit', function(e) {
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
                            setPartnerData(d, koltsegform);
                        }
                    });
                }
            })
            .on('change', '#KtgFizmodEdit', function(e) {
                if (isKeszpenz() && $('#KtgVanPenzmozgas').prop('checked')) {
                    setPenztarKotelezo();
                }
                else {
                    setPenztarNemkotelezo();
                }
                $('#KtgVanPenzmozgas').prop('checked', isKeszpenz());
            })
            .on('change', '#KtgVanPenzmozgas', function(e) {
                if (isKeszpenz() && $('#KtgVanPenzmozgas').prop('checked')) {
                    setPenztarKotelezo();
                }
                else {
                    setPenztarNemkotelezo();
                }
            })
            .on('change', '#KtgTeljesitesEdit', function(e) {
                $('#KtgPenzdatumEdit').val($('#KtgTeljesitesEdit').val());
            })
            .on('change', '#KtgTermekEdit', function(e) {
                $('#KtgTermeknevEdit').val($('#KtgTermekEdit option:selected').text());
                setTermekAr();
            })
            .on('change', '#KtgMennyisegEdit, #KtgEgysarEdit', function(e) {
                calcErtek();
            })
            .on('click', '#KtgCancelButton', function(e) {
                e.preventDefault();
                clearForm();
            });
    }

    function clearForm() {
        mkwcomp.datumEdit.clear('#KtgKeltEdit');
        mkwcomp.datumEdit.clear('#KtgTeljesitesEdit');
        mkwcomp.datumEdit.clear('#KtgEsedekessegEdit');
        mkwcomp.datumEdit.clear('#KtgPenzdatumEdit');
        $('#KtgPartnerEdit')[0].selectedIndex = 0;
        $('#KtgPartnervezeteknevEdit').val('');
        $('#KtgPartnerkeresztnevEdit').val('');
        $('#KtgPartnerirszamEdit').val('');
        $('#KtgPartnervarosEdit').val('');
        $('#KtgPartnerutcaEdit').val('');
        $('#KtgPartneremailEdit').val('');
        $('#KtgPartnertelefonEdit').val('');
        $('#KtgDolgozoEdit')[0].selectedIndex = 0;
        $('#KtgEgysarEdit').val('');
        $('#KtgMennyisegEdit').val(1);
        $('#KtgTermekEdit')[0].selectedIndex = 0;
        $('#KtgTermeknevEdit').val('');
        $('#KtgFizmodEdit')[0].selectedIndex = 0;
        $('#KtgPenztarEdit')[0].selectedIndex = 0;
        $('#KtgMegjegyzesEdit').val('');
        $('#KtgJogcimEdit')[0].selectedIndex = 0;
        $('#KtgErtek').text('');
        $('#KtgOsszegEdit').val('');
        $('#KtgVanPenzmozgas').attr('checked', 'checked');
        $('#KtgSzamlaEdit[value="koltsegszamla"]').prop('checked', true);
        setPenztarNemkotelezo();
    }

    function setPenztarKotelezo() {
        $('#KtgPenztarEdit').prop('required', true);
        $('#KtgPenzdatumEdit').prop('required', true);
        $('#KtgJogcimEdit').prop('required', true);
        $('#KtgOsszegEdit').prop('required', true);
    }

    function setPenztarNemkotelezo() {
        $('#KtgPenztarEdit').removeAttr('required');
        $('#KtgPenzdatumEdit').removeAttr('required');
        $('#KtgJogcimEdit').removeAttr('required');
        $('#KtgOsszegEdit').removeAttr('required');
    }

    return {
        init: init,
        clearForm: clearForm
    }
}