<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.bejegyzes}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_egyed.partnernev}</td>
    <td class="cell">{$_egyed.esedekesstr}</td>
    <td class="cell"><a href="#" data-id="{$_egyed.id}" data-flag="elvegezve" class="js-flagcheckbox{if ($_egyed.elvegezve)} ui-state-hover{/if}">{at('Elvégezve')}</a>
</tr>