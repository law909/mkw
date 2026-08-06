$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-exportbutton')
                .on('click', function (e) {
                    var $ff, fafi, inp;
                    e.preventDefault();
                    $ff = $('#leltariv');

                    inp = $('input[name="fafilter"]');
                    fafi = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fafi.length > 0) {
                        inp.val(fafi);
                    } else {
                        inp.val('');
                    }

                    $ff.attr('action', $(this).attr('href'));
                    $ff.submit();
                })
                .button();
        }
    }));
});