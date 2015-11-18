$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#DatumEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-okbutton').on('click', function(e) {
                var $ff, fafi, inp;
                e.preventDefault();
                $ff = $('#keszlet');

                inp = $('input[name="fafilter"]');
                fafi = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fafi.length > 0) {
                    inp.val(fafi);
                }
                else {
                    inp.val('');
                }

                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-exportbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#keszlet');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    });
});