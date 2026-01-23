<tr id="mattable-row_{$_blokk.id}">
    <td class="cell">
        <a class="mattable-editlink" href="#" data-blokkid="{$_blokk.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_blokk.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-blokkid="{$_blokk.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_blokk.sorrend}</td>
    <td class="cell">
        {at($_blokk.tipusnev)}
    </td>
    <td class="cell">
        {at($_blokk.blokkmagassagnev)}
    </td>
    <td class="cell"><a href="#" data-id="{$_blokk.id}" data-flag="lathato"
                        class="js-flagcheckbox{if ($_blokk.lathato)} ui-state-hover{/if}">{at('Látható')}</a></td>
</tr>
