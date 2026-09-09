<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.dolgozonev}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">{$_egyed.datumtolstr} - {$_egyed.datumigstr}</td>
    <td class="cell">{$_egyed.tipusnev}</td>
    <td class="cell">{$_egyed.megjegyzes}</td>
</tr>
