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
                    $('#resztvevolist').html(data);
                }
            })
        });

        $(document)
            .on('click', '.js-setmegjelent', function(e) {
                e.preventDefault();
                var rid = $(this).data('id');
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/resztvevomegjelent',
                    data: {
                        id: rid
                    },
                    success: function(data) {
                        $('#oraselect').change();
                    }
                });
            })
            .on('click', '.js-buy', function(e) {
                e.preventDefault();
                $('#buyModalLabel').text($(this).text());
                $('#buyModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            })
    }
);