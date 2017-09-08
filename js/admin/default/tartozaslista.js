$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.datumEdit.init('#BefEdit');

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            $('.js-okbutton, .js-exportbutton').on('click', function(e) {
                var $ff, $c, cimkek = [];
                e.preventDefault();
                $ff = $('#tartozas');
                $c = $('input[name="cimkefilter"]');
                if ($c.length == 0) {
                    $ff.append('<input type="hidden" name="cimkefilter">');
                    $c = $('input[name="cimkefilter"]');
                }
                $('.js-cimkefilter').filter('.ui-state-hover').each(function() {
                    cimkek.push($(this).attr('data-id'));
                });
                if (cimkek.length>0) {
                    $c.val(cimkek);
                }
                else {
                    $c.val('');
                }
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    });
});