<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
<td class="cell">
    <input class="maincheckbox" type="checkbox">
</td>
<td class="cell">
    <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_egyed.nev}</a>
    <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
</td>
<td class="cell">{$_egyed.csoport}</td>
<td class="cell">{$_egyed.sorrend}</td>
</tr>