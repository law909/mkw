<tr id="mattable-row_{$_meretsor.id}" data-egyedid="{$_meretsor.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-meretsorid="{$_meretsor.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{$_meretsor.nev}</a>
        <a class="mattable-dellink" href="#" data-meretsorid="{$_meretsor.id}" data-oper="del" title="{at('Töröl')}"><span
                class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">
        {if ($_meretsor.meretek)}{$_meretsor.meretek}{/if}
    </td>
</tr>