/**
 * The grouping levels (.js-szint), the display (megjelenites, pivotertek) and the saved views (#NezetEdit) of the
 * revenue, sales and commission reports. A chosen view clicks .js-refresh. Returns {getSzintek}.
 */
function initReportGrouping(baseUrl) {

    // the levels count up to the first empty one; a dimension can be chosen once
    function syncSzintek() {
        const $szintek = $('.js-szint');
        let ures = false;
        $szintek.each(function () {
            $(this).prop('disabled', ures);
            if (!$(this).val()) {
                ures = true;
            }
        });
        const hasznalt = $szintek.filter(':enabled').map(function () {
            return $(this).find('option:selected').data('dim');
        }).get();
        $szintek.each(function () {
            const sajat = $(this).find('option:selected').data('dim');
            $(this).find('option[data-dim]').each(function () {
                const dim = $(this).data('dim');
                $(this).prop('disabled', dim !== sajat && hasznalt.includes(dim));
            });
        });
        // the cross table puts the periods into the columns, so it needs a period level
        const $megjelenites = $('select[name="megjelenites"]');
        if (!hasznalt.includes('idoszak')) {
            $megjelenites.val('lista');
        }
        $megjelenites.prop('disabled', !hasznalt.includes('idoszak'));
        $('select[name="pivotertek"]').toggle($megjelenites.val() === 'kereszttabla');
    }

    function getSzintek() {
        return $('.js-szint:enabled').map(function () {
            return $(this).val();
        }).get().filter((szint) => szint);
    }

    function applyBeallitas(beallitas) {
        const szintek = beallitas.szint || [];
        $('.js-szint').each(function (i) {
            $(this).prop('disabled', false).val(szintek[i] || '');
        });
        $('select[name="megjelenites"]').prop('disabled', false).val(beallitas.megjelenites || 'lista');
        $('select[name="pivotertek"]').val(beallitas.pivotertek || 'mennyiseg');
        syncSzintek();
    }

    function fillNezetek(d) {
        const $select = $('#NezetEdit');
        $select.find('option[value!=""]').remove();
        for (const nezet of d.nezetek) {
            $('<option>').val(nezet.id).text(nezet.nev).data('beallitas', nezet.beallitas)
                .attr('title', nezet.createdby || '').appendTo($select);
        }
        $select.val(d.selected || '');
    }

    function showMessage(text) {
        $('#dialogcenter').text(text).dialog({
            title: 'Mentett nézet',
            modal: true,
            resizable: false,
            buttons: {
                'OK': function () {
                    $(this).dialog('close');
                }
            }
        });
    }

    $('.js-szint, select[name="megjelenites"]').on('change', syncSzintek);
    syncSzintek();

    $.getJSON(`${baseUrl}/nezetlista`, fillNezetek);

    $('#NezetEdit').on('change', function () {
        const beallitas = $(this).find('option:selected').data('beallitas');
        if (beallitas) {
            applyBeallitas(beallitas);
            $('.js-refresh').click();
        }
    });

    // no browser prompt()/confirm(): they block the page, the dialogs are jQuery UI
    $('.js-nezetsave').on('click', function (e) {
        e.preventDefault();
        const $input = $('<input type="text" size="40" maxlength="100">').val($('#NezetEdit option:selected').filter('[value!=""]').text());
        $('#dialogcenter').empty()
            .append($('<p>').text('A mostani csoportosítás és megjelenítés mentése. Azonos névvel a meglévő nézet felülíródik.'))
            .append($input)
            .dialog({
                title: 'Nézet mentése',
                modal: true,
                resizable: false,
                width: 420,
                buttons: {
                    'Mentés': function () {
                        const $dialog = $(this);
                        $.ajax({
                            url: `${baseUrl}/nezetsave`,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                nev: $input.val(),
                                szint: getSzintek(),
                                megjelenites: $('select[name="megjelenites"]').val(),
                                pivotertek: $('select[name="pivotertek"]').val()
                            },
                            success: (d) => {
                                $dialog.dialog('close');
                                fillNezetek(d);
                            },
                            error: (xhr) => showMessage(xhr.responseJSON?.error || 'A mentés nem sikerült.')
                        });
                    },
                    'Mégsem': function () {
                        $(this).dialog('close');
                    }
                }
            });
        $input.focus();
    }).button();

    $('.js-nezetdelete').on('click', function (e) {
        e.preventDefault();
        const $selected = $('#NezetEdit option:selected').filter('[value!=""]');
        if (!$selected.length) {
            showMessage('Válassza ki a törlendő nézetet.');
            return;
        }
        $('#dialogcenter').text(`A(z) „${$selected.text()}” nézet mindenkinél törlődik.`).dialog({
            title: 'Nézet törlése',
            modal: true,
            resizable: false,
            buttons: {
                'Törlés': function () {
                    const $dialog = $(this);
                    $.post(`${baseUrl}/nezetdelete`, {id: $selected.val()}, (d) => {
                        $dialog.dialog('close');
                        fillNezetek(d);
                    }, 'json');
                },
                'Mégsem': function () {
                    $(this).dialog('close');
                }
            }
        });
    }).button();

    return {getSzintek};
}
