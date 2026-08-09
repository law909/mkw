$(document).ready(function () {

    var folyamat = $('#unasrendelesfolyamat'),
        eredmeny = $('#unasrendeleseredmeny'),
        outbox = $('#unasoutboxtartalom');

    $('#mattkarb').mattkarb(new MattkarbConfig({}));

    var hibaDoboz = function (uzenet) {
        return $('<div>')
            .addClass('matt-messagecenter ui-widget ui-state-error')
            .css({padding: '5px', margin: '5px 0'})
            .text(uzenet);
    };

    // Állapot fül: lehúzás, kézi import, kurzor
    var kuldForm = function (form, utan) {
        form.on('submit', function (e) {
            e.preventDefault();
            var kerdes = form.attr('data-kerdes');
            if (kerdes && !window.confirm(kerdes)) {
                return false;
            }
            eredmeny.empty();
            folyamat.show();
            form.find('button[type=submit]').prop('disabled', true);

            $.ajax({url: form.attr('action'), type: 'POST', data: form.serialize(), dataType: 'json'})
                .done(function (data) {
                    if (data.ok) {
                        utan(data);
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
    };

    kuldForm($('#unasrendelespoll'), function (data) {
        eredmeny.html(data.html);
        $('#unaskurzor').text(data.kurzor);
    });

    kuldForm($('#unasrendelesimport'), function (data) {
        eredmeny.html($('<div>')
            .addClass('matt-messagecenter ui-widget ui-state-highlight')
            .css({padding: '5px', margin: '5px 0'})
            .text(data.unaskey + ': ' + data.statusz + (data.bizonylat ? ' → ' + data.bizonylat : '')));
    });

    kuldForm($('#unasrendeleskurzor'), function (data) {
        $('#unaskurzor').text(data.kurzor);
        $('#UnasKurzorEdit').val(data.kurzorinput);
    });

    // Leképezés fül
    var lekepezesForm = $('#unasrendeleslekepezes'),
        lekepezesTartalom = $('#unaslekepezestartalom');

    $('#unaslekepezesbetolt').on('click', function () {
        var gomb = $(this).prop('disabled', true);
        lekepezesTartalom.html('...');
        $.ajax({url: lekepezesForm.attr('data-betoltes'), type: 'POST', dataType: 'json'})
            .done(function (data) {
                if (data.ok) {
                    lekepezesTartalom.html(data.html);
                    if (data.figyelmeztetes) {
                        lekepezesTartalom.prepend(hibaDoboz(data.figyelmeztetes));
                    }
                    $('#unaslekepezesmentes').show();
                } else {
                    lekepezesTartalom.html(hibaDoboz(data.hiba));
                }
            })
            .fail(function () {
                lekepezesTartalom.html(hibaDoboz(lekepezesForm.attr('data-hibauzenet')));
            })
            .always(function () {
                gomb.prop('disabled', false);
            });
    });

    lekepezesForm.on('submit', function (e) {
        e.preventDefault();
        var mentes = $('#unaslekepezesmentes').prop('disabled', true);
        $.ajax({
            url: lekepezesForm.attr('action'),
            type: 'POST',
            data: lekepezesForm.serialize(),
            dataType: 'json'
        })
            .done(function (data) {
                var figy = $('#unaskezelesiktgfigyelmeztetes');
                if (data.figyelmeztetes) {
                    figy.text(data.figyelmeztetes).show();
                } else {
                    figy.empty().hide();
                }
            })
            .fail(function () {
                lekepezesTartalom.prepend(hibaDoboz(lekepezesForm.attr('data-hibauzenet')));
            })
            .always(function () {
                mentes.prop('disabled', false);
            });
        return false;
    });

    // Visszaírás fül
    var outboxBetolt = function () {
        $.get(outbox.attr('data-href'), function (html) {
            outbox.html(html);
        });
    };

    $('#unasoutboxfrissit').on('click', outboxBetolt);

    $('#unasoutboxfuttat').on('click', function () {
        var gomb = $(this).prop('disabled', true);
        $.ajax({url: gomb.attr('data-href'), type: 'POST', dataType: 'json'})
            .done(function (data) {
                if (!data.ok) {
                    outbox.prepend(hibaDoboz(data.hiba));
                }
                outboxBetolt();
            })
            .fail(function () {
                outbox.prepend(hibaDoboz(gomb.attr('data-hibauzenet')));
            })
            .always(function () {
                gomb.prop('disabled', false);
            });
    });

    outbox.on('click', '.js-unasoutboxujra', function (e) {
        e.preventDefault();
        $.ajax({
            url: outbox.attr('data-ujrahref'),
            type: 'POST',
            data: {id: $(this).attr('data-id')},
            dataType: 'json'
        }).always(outboxBetolt);
    });

    outboxBetolt();
});
