$(document).ready(function() {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function setPartnerData(d) {
        $('input[name="partnernev"]').val(d.nev);
        $('input[name="partnervezeteknev"]').val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]').val(d.keresztnev);
        $('input[name="partnerirszam"]').val(d.irszam);
        $('input[name="partnervaros"]').val(d.varos);
        $('input[name="partnerutca"]').val(d.utca);
        $('input[name="partneradoszam"]').val(d.adoszam);
        $('input[name="partnertelefon"]').val(d.telefon);
        $('input[name="partneremail"]').val(d.email);
    }

    var rendezvenyjel = {
        container: '#mattkarb',
        viewUrl: '/admin/rendezvenyjelentkezes/getkarb',
        newWindowUrl: '/admin/rendezvenyjelentkezes/viewkarb',
        saveUrl: '/admin/rendezvenyjelentkezes/save',
        beforeShow: function() {
            var datumedit = $('#DatumEdit');
            datumedit.datepicker($.datepicker.regional['hu']);
            datumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            datumedit.datepicker('setDate', datumedit.attr('data-datum'));
            $('#EmailEdit').change(function() {
                var partner,
                    ee = $(this);
                if (isPartnerAutocomplete()) {
                    partner = $('.js-partnerid').val();
                }
                else {
                    partner = $('#PartnerEdit option:selected').val();
                }
                if (partner == -1) {
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
                                if (isPartnerAutocomplete()) {
                                    $('.js-partnerid').val(d.id);
                                }
                                else {
                                    $('#PartnerEdit').val(d.id);
                                }
                            }
                        }
                    });
                }
            });

            $('.js-partnerid').change(function() {
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
        var dialogcenter = $('#dialogcenter'),
            datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter'),
            afizetvedatum = $('#afizetvedatumedit'),
            alemondvadatum = $('#alemondasdatumedit'),
            aszamlazvakelt = $('#aszamlazvakeltedit'),
            aszamlazvateljesites = $('#aszamlazvateljesitesedit');

        datumtolfilter.datepicker($.datepicker.regional['hu']);
        datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        datumtolfilter.datepicker('setDate', datumtolfilter.attr('data-datum'));
        datumigfilter.datepicker($.datepicker.regional['hu']);
        datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        aszamlazvakelt.datepicker($.datepicker.regional['hu']);
        aszamlazvakelt.datepicker('option', 'dateFormat', 'yy.mm.dd');
        aszamlazvateljesites.datepicker($.datepicker.regional['hu']);
        aszamlazvateljesites.datepicker('option', 'dateFormat', 'yy.mm.dd');
        afizetvedatum.datepicker($.datepicker.regional['hu']);
        afizetvedatum.datepicker('option', 'dateFormat', 'yy.mm.dd');
        alemondvadatum.datepicker($.datepicker.regional['hu']);
        alemondvadatum.datepicker('option', 'dateFormat', 'yy.mm.dd');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#partnernevfilter',
                    '#partneremailfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#fizmodfilter',
                    '#rendezvenyfilter'
                ]
            },
            tablebody: {
                url: '/admin/rendezvenyjelentkezes/getlistbody',
                onStyle: function() {
                    $('.js-fizetve, .js-szamlazva, .js-lemondva, .js-visszautalva').button();
                }
            },
            karb: rendezvenyjel
        });
        $('#mattable-body')
            .on('click', '.js-fizetve', function(e) {

                function clearForm() {
                    $('#afizetveosszegedit').val('');
                    afizetvedatum.datepicker('setDate', null);
                    $('#afizetvepenztaredit').val(0);
                    $('#afizetvebankszamlaedit').val(0);
                    $('#afizetvefizmodedit').val(0);
                    $('#afizetvejogcimedit').val(0);
                }

                var $gomb = $(this);
                e.preventDefault();


                $.ajax({
                    url: '/admin/rendezvenyjelentkezes/getar',
                    type: 'GET',
                    data: {
                        id: $gomb.data('id')
                    },
                    success: function (data) {
                        var d = JSON.parse(data);
                        if (d.result === 'error') {
                            dialogcenter.html(d.msg).dialog({
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
                            $('#afizetveosszegedit').val(d.price);
                            $('#fizetveform').show().dialog({
                                resizable: false,
                                height: 210,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        var dia = $(this),
                                            datum = afizetvedatum.datepicker('getDate'),
                                            datumstr;
                                        if (datum) {
                                            datumstr = datum.getFullYear() + '.' + (datum.getMonth() + 1) + '.' + datum.getDate();
                                        }
                                        $.ajax({
                                            url: '/admin/rendezvenyjelentkezes/fizet',
                                            type: 'POST',
                                            data: {
                                                id: $gomb.data('id'),
                                                fizmod: $('#afizetvefizmodedit option:selected').val(),
                                                bankszamla: $('#afizetvebankszamlaedit option:selected').val(),
                                                penztar: $('#afizetvepenztaredit option:selected').val(),
                                                jogcim: $('#afizetvejogcimedit option:selected').val(),
                                                datum: datumstr,
                                                osszeg: $('#afizetveosszegedit').val()
                                            },
                                            success: function (data) {
                                                var d = JSON.parse(data);
                                                if (d.result === 'ok') {
                                                    dia.dialog('close').dialog('destroy');
                                                    clearForm();
                                                    $('#fizetveform').hide();
                                                    $('.mattable-tablerefresh').click();
                                                }
                                                else {
                                                    dia.dialog('close').dialog('destroy');
                                                    clearForm();
                                                    $('#fizetveform').hide();
                                                    dialogcenter.html(d.msg).dialog({
                                                        resizable: false,
                                                        height: 210,
                                                        modal: true,
                                                        buttons: {
                                                            'OK': function () {
                                                                dialogcenter.dialog('close').dialog('destroy');
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    },
                                    'Mégsem': function () {
                                        $(this).dialog('close').dialog('destroy');
                                        clearForm();
                                        $('#fizetveform').hide();
                                    }
                                }
                            });
                        }
                    }
                });
            })
            .on('click', '.js-szamlazva', function(e) {

                function clearForm() {
                    $('#aszamlazvabiztipusedit').val('');
                    aszamlazvakelt.datepicker('setDate', null);
                    aszamlazvateljesites.datepicker('setDate', null);
                    $('#aszamlazvaosszegedit').val(0);
                }

                var $gomb = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/rendezvenyjelentkezes/getfizetettosszeg',
                    type: 'GET',
                    data: {
                        id: $gomb.data('id')
                    },
                    success: function (data) {
                        var d = JSON.parse(data);
                        if (d.result === 'error') {
                            dialogcenter.html(d.msg).dialog({
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
                            $('#aszamlazvaosszegedit').val(d.price);
                            $('#szamlazvaform').show().dialog({
                                resizable: false,
                                height: 210,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        var dia = $(this),
                                            kelt = aszamlazvakelt.datepicker('getDate'),
                                            keltstr,
                                            teljesites = aszamlazvateljesites.datepicker('getDate'),
                                            teljesitesstr;
                                        if (kelt) {
                                            keltstr = kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate();
                                        }
                                        if (teljesites) {
                                            teljesitesstr = teljesites.getFullYear() + '.' + (teljesites.getMonth() + 1) + '.' + teljesites.getDate();
                                        }
                                        $.ajax({
                                            url: '/admin/rendezvenyjelentkezes/szamlaz',
                                            type: 'POST',
                                            data: {
                                                id: $gomb.data('id'),
                                                kelt: keltstr,
                                                teljesites: teljesitesstr,
                                                osszeg: $('#aszamlazvaosszegedit').val(),
                                                biztipus: $('input[name="aszamlazvabiztipus"]:checked').val()
                                            },
                                            success: function (data) {
                                                var d = JSON.parse(data);
                                                if (d.result === 'ok') {
                                                    dia.dialog('close').dialog('destroy');
                                                    clearForm();
                                                    $('#szamlazvaform').hide();
                                                    $('.mattable-tablerefresh').click();
                                                }
                                                else {
                                                    dia.dialog('close').dialog('destroy');
                                                    clearForm();
                                                    $('#szamlazvaform').hide();
                                                    dialogcenter.html(d.msg).dialog({
                                                        resizable: false,
                                                        height: 210,
                                                        modal: true,
                                                        buttons: {
                                                            'OK': function () {
                                                                dialogcenter.dialog('close').dialog('destroy');
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    },
                                    'Mégsem': function () {
                                        $(this).dialog('close').dialog('destroy');
                                        clearForm();
                                        $('#szamlazvaform').hide();
                                    }
                                }
                            });
                        }
                    }
                });
            })
            .on('click', '.js-lemondva', function(e) {

                function clearForm() {
                    $('#alemondasokaedit').val('');
                    alemondvadatum.datepicker('setDate', null);
                }

                var $gomb = $(this);
                e.preventDefault();

                $('#lemondvaform').show().dialog({
                    resizable: false,
                    height: 210,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var dia = $(this),
                                datum = alemondvadatum.datepicker('getDate'),
                                datumstr;
                            if (datum) {
                                datumstr = datum.getFullYear() + '.' + (datum.getMonth() + 1) + '.' + datum.getDate();
                            }
                            $.ajax({
                                url: '/admin/rendezvenyjelentkezes/lemond',
                                type: 'POST',
                                data: {
                                    id: $gomb.data('id'),
                                    datum: datumstr,
                                    ok: $('#alemondasokaedit').val()
                                },
                                success: function (data) {
                                    var d = JSON.parse(data);
                                    if (d.result === 'ok') {
                                        dia.dialog('close').dialog('destroy');
                                        clearForm();
                                        $('#lemondvaform').hide();
                                        $('.mattable-tablerefresh').click();
                                    }
                                    else {
                                        dia.dialog('close').dialog('destroy');
                                        clearForm();
                                        $('#lemondvaform').hide();
                                        dialogcenter.html(d.msg).dialog({
                                            resizable: false,
                                            height: 210,
                                            modal: true,
                                            buttons: {
                                                'OK': function () {
                                                    dialogcenter.dialog('close').dialog('destroy');
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        },
                        'Mégsem': function () {
                            $(this).dialog('close').dialog('destroy');
                            clearForm();
                            $('#lemondvaform').hide();
                        }
                    }
                });
            })
            .on('click', '.js-visszautalva', function(e) {
                var $gomb = $(this);
                e.preventDefault();
            });

        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, rendezvenyjel, {independent: true}));
        }
    }
});