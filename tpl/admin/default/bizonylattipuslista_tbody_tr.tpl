<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">{$_egyed.id}<div>{$_egyed.azonosito}</div></td>
    <td class="cell">{if ($_egyed.irany > 0)}{at('bevét')}{elseif ($_egyed.irany < 0)}{at('kivét')}{else}{at('nincs')}{/if}</td>
    <td class="cell">
        {$_egyed.tplname}
        {if ($_egyed.tplname2)}<div>{$_egyed.tplname2} ({$_egyed.tplcaption2})</div>{/if}
    </td>
    <td class="cell">
        {if ($_egyed.mozgat)}<div>{at('készletet mozgat')}</div>{/if}
        {if ($_egyed.foglal)}<div>{at('foglal')}</div>{/if}
        {if ($_egyed.penztmozgat)}<div>{at('pénzt mozgat')}</div>{/if}
        {if ($_egyed.navbekuldendo)}<div>{at('NAV-hoz beküldendő')}</div>{/if}
    </td>
</tr>
