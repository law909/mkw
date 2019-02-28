$(document).ready(
	function(){

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