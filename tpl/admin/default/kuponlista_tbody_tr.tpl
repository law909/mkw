<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">{$_egyed.createdstr}</td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.id}</a>
        <a class="js-printkupon" href="#" data-egyedid="{$_egyed.id}" data-oper="print" title="{at('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div>{$_egyed.id}</div>
    </td>
    <td class="cell">
        <div>{$_egyed.tipusstr}</div>
        <div>{bizformat($_egyed.osszeg)}</div>
        <div>Minimum kosárérték: {bizformat($_egyed.minimumosszeg)}</div>
    </td>
    <td class="cell">
        {$_egyed.lejartstr}
    </td>
</tr>