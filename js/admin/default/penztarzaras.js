$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#DatumEdit');

            $('.js-okbutton').on('click', function(e) {
                var datumedit = $('#DatumEdit'),
                    datum = datumedit.datepicker('getDate');
                e.preventDefault();
                datum = datum.getFullYear() + '.' + (datum.getMonth() + 1) + '.' + datum.getDate();
                $.ajax({
                    method: 'POST',
                    url: '/admin/penztarzaras/zar',
                    data: {
                        datum: datum,
                        penztar: $('#PenztarEdit option:selected').val()
                    }
                });
            }).button();

        }
    });
});