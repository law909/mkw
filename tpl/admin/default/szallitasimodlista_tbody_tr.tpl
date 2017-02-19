<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div class="matt-hseparator"></div>
        <div>{if ($_egyed.webes)}{at('Webes')}{else}{at('Nem webes')}{/if}</div>
        <div>{if ($_egyed.vanszallitasiktg)}{at('Van szállítási költség')}{else}{at('Nincs szállítási költség')}{/if}</div>
    </td>
    <td class="cell">
        {$_egyed.sorrend}
    </td>
</tr>