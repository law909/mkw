$(document).ready(function () {

    var form = $('#unaskepcleanup'),
        folyamat = $('#unaskepcleanupfolyamat'),
        eredmeny = $('#unaskepcleanuperedmeny'),
        torles = false;

    $('#mattkarb').mattkarb(new MattkarbConfig({}));

    var hibaDoboz = function (uzenet) {
        return $('<div>')
            .addClass('matt-messagecenter ui-widget ui-state-error')
            .css({padding: '5px', margin: '5px 0'})
            .text(uzenet);
    };

    // a küldő gomb dönti el, számolunk vagy törlünk – a form.serialize() ezt nem viszi magával
    form.find('button[type=submit]').on('click', function () {
        torles = $(this).val() === '1';
    });

    form.on('submit', function (e) {
        e.preventDefault();

        if (torles && !window.confirm(form.attr('data-kerdes'))) {
            return false;
        }

        eredmeny.empty();
        folyamat.show();
        form.find('button[type=submit]').prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize() + (torles ? '&torles=1' : ''),
            dataType: 'json'
        })
            .done(function (data) {
                if (data.ok) {
                    eredmeny.html(data.html);
                } else {
                    eredmeny.html(hibaDoboz(data.hiba));
                }
            })
            .fail(function () {
                eredmeny.html(hibaDoboz(form.attr('data-hibauzenet')));
            })
            .always(function () {
                folyamat.hide();
                form.find('button[type=submit]').prop('disabled', false);
            });

        return false;
    });
});
