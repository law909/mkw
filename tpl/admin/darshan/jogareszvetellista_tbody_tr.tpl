<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">{if ($_egyed.tisztaznikell)}Igen{/if}</td>
    <td class="cell">
        <span>{$_egyed.datum} {$_egyed.napnev}</span>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_egyed.tanarnev}</td>
    <td class="cell">{$_egyed.jogaoratipusnev}</td>
    <td class="cell">{$_egyed.partnernev}</td>
    <td class="cell">{$_egyed.termeknev}</td>
    <td class="cell">{$_egyed.bruttoegysar}</td>
    <td class="cell">{$_egyed.jutalek}</td>
</tr>