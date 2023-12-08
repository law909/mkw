<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($_egyed.bankbizonylatkesz || $_egyed.inaktiv)}
            {$_egyed.azonosito}
        {else}
            <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.azonosito}</a>
        {/if}
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div class="matt-hseparator"></div>
    </td>
    <td class="cell">
        {$_egyed.konyvelesdatumstr}
    </td>
    <td class="cell">
        {$_egyed.erteknapstr}
    </td>
    <td class="cell">
        {$_egyed.osszeg}
    </td>
    <td class="cell">
        <div>{$_egyed.kozlemeny3}</div>
    </td>
    <td class="cell">
        {$_egyed.bizonylatszamok}
    </td>
    <td class="cell">
        <div>{$_egyed.kozlemeny1}</div>
        <div>{$_egyed.kozlemeny2}</div>
    </td>
</tr>