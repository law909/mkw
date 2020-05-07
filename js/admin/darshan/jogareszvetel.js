function jogareszvetel() {

    var dialogcenter = $('#dialogcenter'),
        form = $('#JogareszvetelForm'),
        reszveteldb = 0;

    function getEmptyRow() {
        $.ajax({
            type: 'GET',
            url: '/admin/jogareszvetel/getemptyrow',
            success: function(d) {
                var data = JSON.parse(d);
                reszveteldb = reszveteldb + 1;
                $('.js-jrreszvetelnewbutton').before(data.html);
                $('.js-jrreszveteldelbutton').button();
                $('.js-counter' + data.id).text(reszveteldb);
                $('#JRPartnerEdit_' + data.id).focus();
            }
        });
    }

    function loadBerletSelect(fid, partnerid) {
        $.ajax({
            url: '/admin/jogaberlet/getselect',
            data: {
                partnerid: partnerid
            },
            type: 'GET',
            success: function(data) {
                $('select[name="jogaberlet_' + fid + '"]').html(data);
            }
        });
    }

    function setPartnerData(d, fid) {
        $('input[name="partnervezeteknev_' + fid + '"]', form).val(d.vezeteknev);
        $('input[name="partnerkeresztnev_' + fid + '"]', form).val(d.keresztnev);
        $('input[name="partnerirszam_' + fid + '"]', form).val(d.irszam);
        $('input[name="partnervaros_' + fid + '"]', form).val(d.varos);
        $('input[name="partnerutca_' + fid + '"]', form).val(d.utca);
        $('input[name="partnertelefon_' + fid + '"]', form).val(d.telefon);
        $('input[name="partneremail_' + fid + '"]', form).val(d.email);
    }

    function setTermekAr(fid) {
        var partner = $('select[name="partner_' + fid + '"] option:selected', form).val();

        $.ajax({
            async: false,
            url: '/admin/jogareszvetel/getar',
            data: {
                partner: partner,
                termek: $('select[name="termek_' + fid + '"] option:selected', form).val()
            },
            success: function(data) {
                var inp = $('input[name="ar_' + fid + '"]', form),
                    adat = JSON.parse(data);
                inp.val(adat.brutto);
                inp.change();
            }
        });
    }

    function clearForm() {
        mkwcomp.datumEdit.clear('#JRDatumEdit');
        $('.js-reszveteltable').remove();

        $('#JRJogateremEdit')[0].selectedIndex = 0;
        $('#JRJogaoratipusEdit')[0].selectedIndex = 0;
        $('#JRTanarEdit')[0].selectedIndex = 0;
        reszveteldb = 0;
    }

    function init() {
        mkwcomp.datumEdit.init('#JRDatumEdit');
        $('.js-jrreszvetelnewbutton, #JROKButton, #JRCancelButton').button();

        $.fn.ajaxSubmit.debug = true;

        form.ajaxForm({
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

        $(document)
            .on('keypress', function(e) {
                if (e.which == 43) {
                    e.preventDefault();
                    getEmptyRow();
                }
            });

        form
            .on('change', '#KiPenztarEdit', function(e) {
                var v = $('#KiPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', kipenztarform).val(v);
            })
            .on('click', '.js-jrreszvetelnewbutton', function(e) {
                e.preventDefault();
                getEmptyRow();
            })
            .on('click', '.js-jrreszveteldelbutton', function(e) {
                var id = $(this).data('id');
                e.preventDefault();
                dialogcenter.html('Biztos, hogy törli a résztvevőt?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
                            $('#jogareszveteltable_' + id).remove();
                            reszveteldb = reszveteldb - 1;
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
            .on('change', '.js-jrpartneredit', function(e) {
                var pe = $(this);
                if (pe.val() > 0) {
                    loadBerletSelect(pe.data('id'), pe.val());
                    $.ajax({
                        url: '/admin/partner/getdata',
                        type: 'GET',
                        data: {
                            partnerid: pe.val()
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            setPartnerData(d, pe.data('id'));
                            $('#JRBerletEdit_' + pe.data('id')).focus();
                        }
                    });
                }
            })
            .on('change', '.js-jrberletedit', function(e) {
                var termekid = $('option:selected', this).data('termekid');
                $('select[name="termek_' + $(this).data('id') + '"] option[value="' + termekid + '"]').attr('selected', 'selected').change();
            })
            .on('change', '.js-jrtermekedit', function(e) {
                setTermekAr($(this).data('id'));
            })
            .on('click', '#JRCancelButton', function(e) {
                e.preventDefault();
                clearForm();
            });

    }

    return {
        init: init
    }
}