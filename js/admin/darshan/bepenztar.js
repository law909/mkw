function bepenztar() {

    var dialogcenter = $('#dialogcenter'),
        bepenztarform = $('#BepenztarForm');

    function setPartnerData(d) {
        $('input[name="partnervezeteknev"]', bepenztarform).val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]', bepenztarform).val(d.keresztnev);
        $('input[name="partnerirszam"]', bepenztarform).val(d.irszam);
        $('input[name="partnervaros"]', bepenztarform).val(d.varos);
        $('input[name="partnerutca"]', bepenztarform).val(d.utca);
        $('input[name="partnertelefon"]', bepenztarform).val(d.telefon);
        $('input[name="partneremail"]', bepenztarform).val(d.email);
    }

    function clearForm() {
        mkwcomp.datumEdit.clear('#BeKeltEdit');
        $('#BePenztarEdit')[0].selectedIndex = 0;
        $('#BePartnerEdit')[0].selectedIndex = 0;
        $('#BePartnervezeteknevEdit').val('');
        $('#BePartnerkeresztnevEdit').val('');
        $('#BePartnerirszamEdit').val('');
        $('#BePartnervarosEdit').val('');
        $('#BePartnerutcaEdit').val('');
        $('#BeEmailEdit').val('');
        $('#BeTelefonEdit').val('');
        $('#BeMegjegyzesEdit').val('');
        $('#BeSzovegEdit').val('');
        $('#BeJogcimEdit')[0].selectedIndex = 0;
        $('#BeHivatkozottBizonylatEdit').val('');
        $('#BeEsedekessegEdit').val('');
        $('#BeOsszegEdit').val('');
    }

    function init() {
        mkwcomp.datumEdit.init('#BeKeltEdit');
        $('.js-behivatkozottbizonylatbutton, #BeOKButton, #BeCancelButton').button();

        bepenztarform.ajaxForm({
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
        bepenztarform
            .on('change', '#BePenztarEdit', function(e) {
                var v = $('#BePenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', bepenztarform).val(v);
            })
            .on('click', '.js-behivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', bepenztarform).val(),
                        irany: $('input[name="irany"]', bepenztarform).val()
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
                                    $('input[name="tetelhivatkozottbizonylat"]', bepenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', bepenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', bepenztarform).val(sor.data('egyenleg'));
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
            .on('change', '#BePartnerEdit', function(e) {
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
            })
            .on('click', '#BeCancelButton', function(e) {
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