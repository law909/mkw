$(document).ready(function () {

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
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

            function getFilterData() {
                let partnerid;
                if (isPartnerAutocomplete()) {
                    partnerid = $('.js-partnerid').val();
                } else {
                    partnerid = $('#PartnerEdit option:selected').val();
                }
                return {
                    datumtol: $('input[name="tol"]').val(),
                    datumig: $('input[name="ig"]').val(),
                    partner: partnerid
                };
            }

            $('.js-refresh')
                .on('click', function () {
                    $.ajax({
                        url: '/admin/rendbevlista/refresh',
                        type: 'GET',
                        data: getFilterData(),
                        success: function (d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();

            $('.js-exportbutton')
                .on('click', function (e) {
                    e.preventDefault();
                    window.location = $(this).attr('href') + '?' + $.param(getFilterData());
                })
                .button();
        }
    });
});
