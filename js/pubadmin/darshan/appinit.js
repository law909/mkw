$(document).ready(
    function() {

        $('#buyModal').on('shown.bs.modal', function () {
            $('#aredit').trigger('focus');
        });

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
                if ($(this).data('mustbuy')) {
                    $('#mustbuyModal')
                        .modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                }
                else {
                    var rid = $(this).data('id');
                    $.ajax({
                        method: 'POST',
                        url: '/pubadmin/resztvevomegjelent',
                        data: {
                            id: rid
                        },
                        success: function () {
                            $('#oraselect').change();
                        }
                    });
                }
            })
            .on('click', '.js-buy', function(e) {
                var $this = $(this);
                e.preventDefault();
                $('#buyModalLabel').text($this.text() + ' vásárlás');
                $('.js-buyok').data('id', $this.data('id'));
                $('#aredit').val($this.data('price'))
                $('#buyModal')
                    .data('type', $this.data('type'))
                    .modal({
                        backdrop: 'static',
                        keyboard: false
                    });
            })
            .on('click', '.js-buyok', function(e) {
                var rid = $(this).data('id');
                $('#buyModal').modal('hide');
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/resztvevoorajegy',
                    data: {
                        id: rid,
                        type: $('#buyModal').data('type'),
                        price: $('#aredit').val()
                    },
                    success: function () {
                        $('#oraselect').change();
                    }
                })
            })
    }
);