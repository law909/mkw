$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo(ul);
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

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function () {

            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            $('.js-exportbutton').on('click', function (e) {
                let $ff, $c, cimkek = [], fak, fafilter;
                e.preventDefault();
                $ff = $('#arlista');

                $c = $('input[name="cimkefilter"]');
                if ($c.length === 0) {
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

                $c = $('input[name="fafilter"]');
                if ($c.length === 0) {
                    $ff.append('<input type="hidden" name="fafilter">');
                    $c = $('input[name="fafilter"]');
                }
                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    $c.val(fak);
                } else {
                    $c.val('');
                }

                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    });
});