<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div class="matt-hseparator"></div>
        <div>{if ($_egyed.webes)}{at('Webes')}{else}{at('Nem webes')}{/if}</div>
        <div>{if ($_egyed.rugalmas)}{at('Rugalmas')}{else}{at('Nem rugalmas')}{/if}</div>
        <div>{if ($_egyed.nincspenzmozgas)}{at('Nincs pénzmozgás')}{else}{at('Van pénzmozgás')}{/if}</div>
        <div>{if ($_egyed.tipus == 'P')}{at('Pénztár')}{elseif ($_egyed.tipus == 'B')}{at('Bank')}{else}{at('Ismeretlen típus')}{/if}</div>
    </td>
    <td class="cell">
        {$_egyed.sorrend}
    </td>
</tr>