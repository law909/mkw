$(document).ready(function () {

    const $form = $('#glscsomagpontletoltes'),
        $gomb = $form.find('button[type=submit]'),
        $folyamat = $('.js-glsfolyamat'),
        $eredmeny = $('.js-glseredmeny');

    $('#mattkarb').mattkarb(new MattkarbConfig({}));

    const uzenet = (szoveg, hiba) => $('<div>')
        .addClass('matt-messagecenter ui-widget ' + (hiba ? 'ui-state-error' : 'ui-state-highlight'))
        .css({padding: '5px', margin: '5px 0'})
        .text(szoveg);

    $form.on('submit', (e) => {
        e.preventDefault();
        $eredmeny.empty();
        $folyamat.show();
        $gomb.prop('disabled', true);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            dataType: 'json'
        })
            .done((data) => {
                if (data && data.ok) {
                    $('.js-glsaktiv').text(data.aktiv);
                    $('.js-glsinaktiv').text(data.inaktiv);
                    $eredmeny.html(uzenet($form.attr('data-kesz')
                        .replace('%1', data.letoltve)
                        .replace('%2', data.inaktivalt)));
                } else {
                    $eredmeny.html(uzenet((data && data.error) || $form.attr('data-hibauzenet'), true));
                }
            })
            .fail(() => {
                $eredmeny.html(uzenet($form.attr('data-hibauzenet'), true));
            })
            .always(() => {
                $folyamat.hide();
                $gomb.prop('disabled', false);
            });
    });
});
