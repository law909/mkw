$(document).ready(
    function() {

        function pleaseWait(msg) {
            if (typeof (msg) !== 'string') {
                msg = 'Kérem várjon...';
            }
            $.blockUI({
                message: msg,
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
        }

        function resetPartnerModal() {
            $('#nevedit').val('');
            $('#emailedit').val('');
            $('#keresoedit').val(null).trigger('change');
        }

        function refreshResztvevoList() {
            $('#oraselect').change();
        }

        $('#buyModal').on('shown.bs.modal', function () {
            $('#aredit').trigger('focus');
        });

        $('#keresoedit').select2({
            theme: 'bootstrap4',
            ajax: {
                url: '/pubadmin/partnerdata',
                delay: 500,
            },
            minimumInputLength: 3,
        });
        $('#keresoedit').on('select2:select', function() {
            $('#nevedit, #emailedit').val('');
        });

        $('#nevedit, #emailedit').change(function() {
            $('#keresoedit').val(null).trigger('change');
        });

        $(document)
            .ajaxStart(pleaseWait)
            .ajaxStop($.unblockUI)
            .ajaxError(function(e, xhr, settings, exception) {
                alert('error in: ' + settings.url + ' \n'+'error:\n' + exception);
            });

        $(document)
            .on('change', '#datumselect', function(e) {
                const datum = $(this).val();
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
                const oraid = $(this).val(),
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
                if (!$('input[name="online-' + $(this).data('id') + '"]:checked').val()) {
                    $('#mustsetOnlineModal')
                        .modal({
                            backdrop: 'static'
                        });
                }
                else {
                    if ($(this).data('mustbuy')) {
                        $('#mustbuyModal')
                            .modal({
                                backdrop: 'static'
                            });
                    } else {
                        const rid = $(this).data('id'),
                            online = $('input[name="online-'+ rid + '"]:checked').val();
                        $.ajax({
                            method: 'POST',
                            url: '/pubadmin/resztvevomegjelent',
                            data: {
                                id: rid,
                                online: online
                            },
                            success: function () {
                                refreshResztvevoList();
                            }
                        });
                    }
                }
            })
            .on('click', '.js-buy', function(e) {
                const $this = $(this);
                e.preventDefault();
                $('#buyModalLabel').text($this.text() + ' vásárlás');
                $('.js-buyok').data('id', $this.data('id'));
                $('#aredit').val($this.data('price'));
                $('#buyModal')
                    .data('type', $this.data('type'))
                    .modal({
                        backdrop: 'static'
                    });
            })
            .on('click', '.js-buyok', function(e) {
                const rid = $(this).data('id');
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
            .on('click', '.js-megjegyzes', function(e) {
                const $this = $(this);
                $('.js-megjegyzesok').data('id', $this.data('id'));
                e.preventDefault();
                $.ajax({
                    method: 'GET',
                    url: '/pubadmin/megjegyzes',
                    data: {
                        id: $this.data('id')
                    },
                    success: function(data) {
                        $('#megjegyzesedit').val(data);
                        $('#megjegyzesModal')
                            .modal({
                                backdrop: 'static'
                            });
                    }
                });
            })
            .on('click', '.js-megjegyzesok', function(e) {
                const $this = $(this);
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/megjegyzes',
                    data: {
                        id: $this.data('id'),
                        megjegyzes: $('#megjegyzesedit').val()
                    },
                    success: function() {
                        $('#megjegyzesModal').modal('hide');
                        $('#megjegyzesedit').text('');
                        refreshResztvevoList();
                    }
                });
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
                const partnerid = $('#keresoedit').find(':selected')[0].value;
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
            .on('click', '.js-partneredit', function(e) {
                const $this = $(this);
                e.preventDefault();
                $('.js-partnereditok').data('id', $this.data('id'));
                $.ajax({
                    method: 'GET',
                    url: '/pubadmin/partner',
                    data: {
                        id: $this.data('id')
                    },
                    success: function(data) {
                        $('#nev2edit').val(data.nev);
                        $('#email2edit').val(data.email);
                        $('#partnerEditModal')
                            .modal({
                                backdrop: 'static'
                            });
                    }
                });
            })
            .on('click', '.js-partnereditok', function(e) {
                const $this = $(this);
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/partner',
                    data: {
                        id: $this.data('id'),
                        nev: $('#nev2edit').val(),
                        email: $('#email2edit').val()
                    },
                    success: function() {
                        $('#partnerEditModal').modal('hide');
                        $this.data('id', '');
                        $('#nev2edit').val('');
                        $('#email2edit').val('');
                        refreshResztvevoList();
                    }
                });
            })
            .on('click', '.js-refresh', function(e) {
                e.preventDefault();
                refreshResztvevoList();
                resetPartnerModal();
            })
            .on('click', '.js-lemond', function(e) {
                const $this = $(this);
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: '/pubadmin/lemond',
                    data: {
                        oraid: $this.data('oraid'),
                        datum: $this.data('oradatum')
                    },
                    success: function() {
                        $('#lemondokModal')
                            .modal({
                                backdrop: 'static'
                            });
                        refreshResztvevoList();
                    }
                });
            })
    }
);