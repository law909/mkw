// a partner karbantartó "Árlista" füle: sávok (oszlopok) és kategóriák (sorok) kedvezmény mátrixa
$(document).ready(function () {
    let counter = 0;
    const newId = () => `n${Date.now()}${counter++}`;
    const kedvezmenyCell = (sorid, savid) =>
        `<td data-sav="${savid}"><input name="arlistakedvezmeny_${sorid}_${savid}" type="text" size="6"> %</td>`;

    $(document)
        .on('click', '#ArlistaTab .js-arlistasavnewbutton', function (e) {
            e.preventDefault();
            const savid = newId();
            $(this).closest('th').before(
                `<th class="js-arlistasav" data-sav="${savid}">
                    <input type="hidden" name="arlistasavid[]" value="${savid}">
                    <input name="arlistasavtol_${savid}" type="text" size="9"> -
                    <input name="arlistasavig_${savid}" type="text" size="9">
                    <a class="js-arlistasavdelbutton" href="#" title="Töröl"><span class="ui-icon ui-icon-circle-minus"></span></a>
                </th>`
            );
            $('#ArlistaTab tr.js-arlistasor').each(function () {
                $(this).children('td').last().before(kedvezmenyCell($(this).attr('data-sor'), savid));
            });
        })
        .on('click', '#ArlistaTab .js-arlistasavdelbutton', function (e) {
            e.preventDefault();
            const savid = $(this).closest('th').attr('data-sav');
            $(`#ArlistaTab [data-sav="${savid}"]`).remove();
        })
        .on('click', '#ArlistaTab .js-arlistasornewbutton', function (e) {
            e.preventDefault();
            const sorid = newId();
            const cells = $('#ArlistaTab th.js-arlistasav')
                .map((i, th) => kedvezmenyCell(sorid, $(th).attr('data-sav')))
                .get()
                .join('');
            $('#ArlistaTab tbody.js-arlistasorok').append(
                `<tr class="js-arlistasor" data-sor="${sorid}">
                    <td>
                        <input type="hidden" name="arlistasor[]" value="${sorid}">
                        <span><input type="hidden" name="arlistatermekfa_${sorid}" value=""><a class="js-termekkategoriafabutton" href="#" data-text="válasszon">válasszon</a></span>
                        <a class="js-arlistasordelbutton" href="#" title="Töröl"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                    ${cells}<td></td>
                </tr>`
            );
        })
        .on('click', '#ArlistaTab .js-arlistasordelbutton', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });
});
