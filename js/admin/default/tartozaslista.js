$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    pi.change();
                }
            }
        };
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.datumEdit.init('#BefEdit');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocompleteRenderer(partnerAutocompleteRenderer);

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                var $ff, $c, cimkek = [];
                e.preventDefault();
                $ff = $('#tartozas');
                $c = $('input[name="cimkefilter"]');
                if ($c.length == 0) {
                    $ff.append('<input type="hidden" name="cimkefilter">');
                    $c = $('input[name="cimkefilter"]');
                }
                $('.js-cimkefilter').filter('.ui-state-hover').each(function () {
                    cimkek.push($(this).attr('data-id'));
                });
                if (cimkek.length > 0) {
                    $c.val(cimkek);
                } else {
                    $c.val('');
                }
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});