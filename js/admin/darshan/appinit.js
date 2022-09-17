function pleaseWait(msg) {
    if (typeof(msg)!=='string') {
        msg='Kérem várjon...';
    }
    $.blockUI({
        message:msg,
        css:{
            border:'none',
            padding:'15px',
            backgroundColor:'#000',
            '-webkit-border-radius':'10px',
            '-moz-border-radius':'10px',
            opacity:.5,
            color:'#fff'
        }
    });
}

$(document).ready(
	function(){

        let dialogcenter = $('#dialogcenter');

        eladas = eladas();
        eladas.init();

        koltseg = koltseg();
        koltseg.init();

        kipenztar = kipenztar();
        kipenztar.init();

        bepenztar = bepenztar();
        bepenztar.init();

        reszvetel = jogareszvetel();
        reszvetel.init();

        mkwcomp.datumEdit.init('#TolEdit');
        mkwcomp.datumEdit.init('#IgEdit');

        $('#StatRefreshButton').button();
        $('#StatRefreshButton').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                url: '/admin/darshanstat',
                data: {
                    tol: mkwcomp.datumEdit.getDate('#TolEdit'),
                    ig: mkwcomp.datumEdit.getDate('#IgEdit'),
                    telszidoszak: $('input[name="telszidoszak"]:checked').val()
                },
                success: function(data) {
                    $('#stateredmeny').html(data);
                }
            })
        });

        $('#CimletezoButton').button();
        $('#CimletezoButton').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/cimletez',
                data: {
                    osszegek: $('#CimletezoEdit').val()
                },
                success: function(data) {
                    $('#CimletezoEredmeny').html(data);
                }
            });

        });

        $('.js-jelenlet').button();
        $('.js-jelenlet').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                url: $(this).data('url'),
                success: function() {
                    window.location.reload(true);
                }
            });
        });

        $('.js-berletlejaratas').button();
        $('.js-berletlejaratas').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                method: 'GET',
                url: $(this).data('url'),
                success: function() {
                    dialogcenter.html('A bérletek lejáratása sikerült.').dialog({
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
            });
        });

        $('#BLKButton').button();
        mkwcomp.datumEdit.init('#BLKVasarlasDatumEdit');
        $('#BLKButton').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                url: '/admin/berletervenyessegkalkulator',
                data: {
                    vasarlasdatum: $('#BLKVasarlasDatumEdit').val(),
                    berlettipus: $('#BLKBerletTipusEdit option:selected').val()
                },
                success: function(data) {
                    $('#BLKEredmeny').html(data);
                }
            })
        });
	}
);