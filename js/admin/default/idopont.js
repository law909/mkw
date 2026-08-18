$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopont',
        beforeShow: function () {
            // a vég a kezdetet követi, amíg a felhasználó nem nyúl hozzá
            $('#KezdetEdit').on('change', function () {
                const $veg = $('#VegEdit');
                if (!$veg.val()) {
                    $veg.val($(this).val());
                }
            });

            // az egyszeri és az ismétlődő megadás kizárja egymást
            function toggleIsmetlodo() {
                const ismetlodo = $('#IsmetlodoCheck').is(':checked');
                $('.js-egyszeriblokk').toggle(!ismetlodo);
                $('.js-ismetlodoblokk').toggle(ismetlodo);
                $('#KezdetEdit, #VegEdit').prop('required', !ismetlodo);
                $('#NapEdit, #KezdetidoEdit, #VegidoEdit').prop('required', ismetlodo);
            }

            $('#IsmetlodoCheck').on('change', toggleIsmetlodo);
            toggleIsmetlodo();
        }
    });

    if ($.fn.mattable) {
        const datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter');

        datumtolfilter.datepicker($.datepicker.regional['hu']);
        datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        datumigfilter.datepicker($.datepicker.regional['hu']);
        datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter',
                    '#jogahelyszinfilter',
                    '#inaktivfilter',
                    '#ismetlodofilter'
                ]
            },
            tablebody: {
                url: '/admin/idopont/getlistbody'
            },
            karb: mattkarbconfig
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            e.preventDefault();
            const $this = $(this);
            $.ajax({
                url: '/admin/idopont/setflag',
                type: 'POST',
                data: {
                    id: $this.attr('data-id'),
                    flag: $this.attr('data-flag'),
                    kibe: !$this.is('.ui-state-hover')
                },
                success: function () {
                    $this.toggleClass('ui-state-hover');
                }
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
