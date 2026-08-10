<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Megnéz')}">{$_egyed.kezdet}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">{$_egyed.feladat}</td>
    <td class="cell{if ($_egyed.allapot == 'hiba')} ui-state-error{elseif ($_egyed.allapot == 'figyelem')} ui-state-highlight{/if}">{$_egyed.allapot}</td>
    <td class="cell">{$_egyed.idotartam}</td>
    <td class="cell">{$_egyed.uzenet|truncate:160:'…'}</td>
</tr>
