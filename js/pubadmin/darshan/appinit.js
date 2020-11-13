$(document).ready(
    function() {

        $(document)
            .on('change', '#datumselect', function(e) {
                var datum = $(this).val();
                $.ajax({
                    method: 'GET',
                    url: '/pubadmin/oralist',
                    data: {
                        datum: datum
                    },
                    success: function(data) {
                        $('#oralist').html(data);
                    }
                });
            })
            .on('change', '#oraselect', function(e) {
                var oraid = $(this).val(),
                    datum = $('#datumselect').val();
                $.ajax({
                    method: 'GET',
                    url: '/pubadmin/resztvevolist',
                    data: {
                        oraid: oraid,
                        datum: datum
                    },
                    success: function(data) {
                        $('#resztvevolist').html(data);
                    }
                });
            })
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
                $('.js-buyok').data('id', $(this).data('id'));
                $('#buyModal')
                    .data('type', $(this).data('type'))
                    .modal({
                        backdrop: 'static',
                        keyboard: false
                    });
            })
            .on('click', '.js-buyok', function(e) {
                var rid = $(this).data('id');
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/resztvevoorajegy',
                    data: {
                        id: rid,
                        type: $('#buyModal').data('type'),
                        price: $('#aredit').val()
                    }
                })
            })
    }
);