$(document).ready(
    function() {

        $('#oraselect').on('change', function() {
            var oraid = $(this).val();
            $.ajax({
                method: 'GET',
                url: '/pubadmin/resztvevolist',
                data: {
                    oraid: oraid
                },
                success: function(data) {
                    $('.js-resztvevo').remove();
                    $('#resztvevolist').after(data);
                }
            })
        });
    }
);