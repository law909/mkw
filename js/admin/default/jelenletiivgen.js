$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            // a dolgozó lenyíló csak a kipipált munkakörök dolgozóit kínálja
            const $dolgozo = $('#DolgozoEdit');
            $('.js-munkakorok').on('change', 'input', function () {
                const munkakorok = $('.js-munkakorok input:checked').map((i, el) => el.value).get();
                $dolgozo.find('option[value!=""]').each(function () {
                    const lathato = !munkakorok.length || munkakorok.includes(String($(this).data('munkakor')));
                    $(this).prop('hidden', !lathato).prop('disabled', !lathato);
                });
                if ($dolgozo.find('option:selected').prop('disabled')) {
                    $dolgozo.val('');
                }
            });

            $('.js-okbutton').on('click', function (e) {
                e.preventDefault();
                const $ff = $('#jelenletiivgen');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            // a letöltés nem új lapon indul, különben üres fül marad utána
            $('.js-exportbutton').on('click', function (e) {
                e.preventDefault();
                window.location = $(this).attr('href') + '?' + $('#jelenletiivgen').serialize();
            }).button();
        }
    }));
});
