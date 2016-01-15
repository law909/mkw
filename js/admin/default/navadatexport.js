$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-okbutton').on('click', function(e) {
                var tol, ig;
                e.preventDefault();

                tol = $('#TolEdit').datepicker('getDate');
                ig = $('#IgEdit').datepicker('getDate');

                $.ajax({
                    url: '/admin/navadatexport/check',
                    type: 'GET',
                    data: {
                        tol: tol.getFullYear() + '.' + (tol.getMonth() + 1) + '.' + tol.getDate(),
                        ig: ig.getFullYear() + '.' + (ig.getMonth() + 1) + '.' + ig.getDate(),
                        szlasztol: $('#SzamlaszamTolEdit').val(),
                        szlaszig: $('#SzamlaszamIgEdit').val()
                    },
                    success: function(d) {
                        var res = JSON.parse(d);
                        if (res.result == 'ok') {
                            dialogcenter.html('<a href="' + res.href + '" target="_blank">Letöltés</a>').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Bezár': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                        else {
                            dialogcenter.html(res.msg).dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    }
                });
            }).button();

        }
    });
});