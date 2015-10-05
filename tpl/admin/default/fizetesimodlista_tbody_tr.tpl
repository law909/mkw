<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div class="matt-hseparator"></div>
        <div>{if ($_egyed.webes)}Webes{else}Nem webes{/if}</div>
        <div>{if ($_egyed.rugalmas)}Rugalmas{else}Nem rugalmas{/if}</div>
        <div>{if ($_egyed.tipus == 'P')}Pénztár{elseif ($_egyed.tipus == 'B')}Bank{else}Ismeretlen típus{/if}</div>
    </td>
    <td class="cell">
        {$_egyed.sorrend}
    </td>
</tr>