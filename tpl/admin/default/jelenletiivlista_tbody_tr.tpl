<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.dolgozonev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div>Be: {$_egyed.beip} Ki: {$_egyed.kiip}</div>
    </td>
    <td class="cell">{$_egyed.datumstr} {$_egyed.belepesstr} - {$_egyed.kilepesstr}</td>
    <td class="cell">{$_egyed.jelenlettipusnev}: {$_egyed.munkaido} {at('óra')}</td>
</tr>