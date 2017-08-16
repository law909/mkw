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

        })
	}
);