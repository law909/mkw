$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            $('.js-okbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                dialogcenter.html('Biztosan összefűzi a partnereket? Az első partner törölve lesz!').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var dia = $(this);
                            $.ajax({
                                url: '/admin/partnermerge',
                                type: 'POST',
                                data: {
                                    partnerrol: $('#PartnerRolEdit').val(),
                                    partnerre: $('#PartnerReEdit').val(),
                                    roldel: $('input[name="roldel"]').prop('checked'),
                                    nevcsere: $('input[name="nevcsere"]').prop('checked'),
                                    cimcsere: $('input[name="cimcsere"]').prop('checked'),
                                    emailcsere: $('input[name="emailcsere"]').prop('checked')
                                },
                                success: function () {
                                    dia.dialog('close');
                                    location.reload(true);
                                }
                            });
                        },
                        'Mégsem': function () {
                            $(this).dialog('close');
                        }
                    }

                });
            }).button();

        }
    });
});