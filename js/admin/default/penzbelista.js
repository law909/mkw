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

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

            const $toledit = $('#TolEdit');
            if ($toledit) {
                $toledit.datepicker($.datepicker.regional['hu']);
                $toledit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                $toledit.datepicker('setDate', $toledit.attr('data-datum'));
            }

            const $igedit = $('#IgEdit');
            if ($igedit) {
                $igedit.datepicker($.datepicker.regional['hu']);
                $igedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                $igedit.datepicker('setDate', $igedit.attr('data-datum'));
            }

            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });

            $('.js-okbutton').on('click', function (e) {
                var $ff, $c, cimkek = [];
                e.preventDefault();
                $ff = $('#penzbe');
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

        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    });
});