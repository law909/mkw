function kipenztar() {

    var dialogcenter = $('#dialogcenter'),
        kipenztarform = $('#KipenztarForm');

    function setPartnerData(d) {
        $('input[name="partnervezeteknev"]', kipenztarform).val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]', kipenztarform).val(d.keresztnev);
        $('input[name="partnerirszam"]', kipenztarform).val(d.irszam);
        $('input[name="partnervaros"]', kipenztarform).val(d.varos);
        $('input[name="partnerutca"]', kipenztarform).val(d.utca);
        $('input[name="partnertelefon"]', kipenztarform).val(d.telefon);
        $('input[name="partneremail"]', kipenztarform).val(d.email);
    }

    function clearForm() {
        mkwcomp.datumEdit.clear('#KiKeltEdit');
        $('#KiPenztarEdit')[0].selectedIndex = 0;
        $('#KiPartnerEdit')[0].selectedIndex = 0;
        $('#KiPartnervezeteknevEdit').val('');
        $('#KiPartnerkeresztnevEdit').val('');
        $('#KiPartnerirszamEdit').val('');
        $('#KiPartnervarosEdit').val('');
        $('#KiPartnerutcaEdit').val('');
        $('#KiEmailEdit').val('');
        $('#KiTelefonEdit').val('');
        $('#KiMegjegyzesEdit').val('');
        $('#KiSzovegEdit').val('');
        $('#KiJogcimEdit')[0].selectedIndex = 0;
        $('#KiHivatkozottBizonylatEdit').val('');
        $('#KiEsedekessegEdit').val('');
        $('#KiOsszegEdit').val('');
    }

    function init() {
        mkwcomp.datumEdit.init('#KiKeltEdit');
        $('.js-kihivatkozottbizonylatbutton, #KiOKButton, #KiCancelButton').button();

        kipenztarform.ajaxForm({
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

        kipenztarform
            .on('change', '#KiPenztarEdit', function(e) {
                var v = $('#KiPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', kipenztarform).val(v);
            })
            .on('click', '.js-kihivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', kipenztarform).val(),
                        irany: $('input[name="irany"]', kipenztarform).val()
                    },
                    success: function(d) {
                        var data = JSON.parse(d);
                        dialogcenter.html(data.html);
                        dialogcenter.dialog({
                            resizable: true,
                            height: 340,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    var sor = $('tr.js-selected', dialogcenter);
                                    $('input[name="tetelhivatkozottbizonylat"]', kipenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', kipenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', kipenztarform).val(sor.data('egyenleg'));
                                    $(this).dialog('close');
                                },
                                'Bezár': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('change', '#KiPartnerEdit', function(e) {
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
                            setPartnerData(d, kipenztarform);
                        }
                    });
                }
            })
            .on('click', '#KiCancelButton', function(e) {
                e.preventDefault();
                clearForm();
            });

        dialogcenter.on('click', 'tr', function(e) {
            e.preventDefault();
            $('tr', dialogcenter).removeClass('ui-state-highlight js-selected');
            $(this).addClass('ui-state-highlight js-selected');
        })
    }

    return {
        init: init
    }
}