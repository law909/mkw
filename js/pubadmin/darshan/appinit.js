$(document).ready(
    function() {

        function resetPartnerModal() {
            $('#nevedit').val('');
            $('#emailedit').val('');
            $('#keresoedit').autoComplete('clear');
        }

        function refreshResztvevoList() {
            $('#oraselect').change();
        }

        $('#buyModal').on('shown.bs.modal', function () {
            $('#aredit').trigger('focus');
        });

        $('#keresoedit').autoComplete({
            resolverSettings: {
                url: '/pubadmin/partnerdata'
            }
        });
        $('#keresoedit').on('autocomplete.select', function() {
            $('#nevedit, #emailedit').val('');
        });

        $('#nevedit, #emailedit').change(function() {
            $('#keresoedit').autoComplete('clear');
        });

        $(document)
            .on('change', '#datumselect', function(e) {
                var datum = $(this).val();
                $('#resztvevolist').html('');
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
                            backdrop: 'static'
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
                            refreshResztvevoList();
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
                        backdrop: 'static'
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
                        price: $('#aredit').val(),
                        later: $('#lateredit').prop('checked')
                    },
                    success: function () {
                        refreshResztvevoList();
                    }
                })
            })
            .on('click', '.js-newpartner', function(e) {
                e.preventDefault();
                $('#partnerModal').modal({
                    backdrop: 'static'
                });
            })
            .on('click', '.js-partnerok', function(e) {
                e.preventDefault();
                $('#partnerModal').modal('hide');
                var partnerid = $('input[name="kereso"]').val();
                if (partnerid) {
                    $.ajax({
                        method: 'POST',
                        url: '/pubadmin/newbejelentkezes',
                        data: {
                            datum: $('#datumselect').val(),
                            oraid: $('#oraselect').val(),
                            partnerid: partnerid
                        },
                        success: function() {
                            refreshResztvevoList();
                            resetPartnerModal();
                        }
                    });
                }
                else {
                    $.ajax({
                        method: 'POST',
                        url: '/pubadmin/newpartnernewbejelentkezes',
                        data: {
                            datum: $('#datumselect').val(),
                            oraid: $('#oraselect').val(),
                            nev: $('#nevedit').val(),
                            email: $('#emailedit').val()
                        },
                        success: function() {
                            refreshResztvevoList();
                            resetPartnerModal();
                        }
                    });
                }
            })
            .on('click', '.js-refresh', function(e) {
                e.preventDefault();
                refreshResztvevoList();
                resetPartnerModal();
            })
    }
);