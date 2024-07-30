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
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

            $('.js-refresh')
                .on('click', function () {

                    let fak, fafilter, partnercimkefilter, partnerid;

                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        fafilter = fak;
                    }

                    partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                    if (isPartnerAutocomplete()) {
                        partnerid = $('.js-partnerid').val();
                    } else {
                        partnerid = $('#PartnerEdit option:selected').val();
                    }

                    $.ajax({
                        url: '/admin/termekforgalmilista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: $('select[name="datumtipus"]').val(),
                            tol: $('input[name="tol"]').val(),
                            ig: $('input[name="ig"]').val(),
                            raktar: $('select[name="raktar"]').val(),
                            gyarto: $('select[name="gyarto"]').val(),
                            partner: partnerid,
                            keszletfilter: $('select[name="keszletfilter"]').val(),
                            forgalomfilter: $('select[name="forgalomfilter"]').val(),
                            ertektipus: $('select[name="ertektipus"]').val(),
                            arsav: $('select[name="arsav"]').val(),
                            nevfilter: $('input[name="nevfilter"]').val(),
                            nyelv: $('select[name="nyelv"]').val(),
                            fafilter: fafilter,
                            partnercimkefilter: partnercimkefilter
                        },
                        success: function (d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
            $('.js-exportbutton').on('click', function (e) {
                var fak, fafilter, $ff, partnercimkefilter;
                e.preventDefault();

                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    fafilter = fak;
                }
                $('#FaFilter').val(fafilter);

                partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                $('#PartnerCimkeFilter').val(partnercimkefilter);

                $ff = $('#termekforgalmi');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function (e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
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