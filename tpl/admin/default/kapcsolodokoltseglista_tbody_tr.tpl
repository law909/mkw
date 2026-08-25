<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">{$_egyed.csoportnev}</td>
    <td class="cell">{$_egyed.szamitasalapnev}</td>
    <td class="cell mattable-rightaligned">{number_format($_egyed.ar|default:0, 4, '.', ' ')}</td>
    <td class="cell">{if ($_egyed.navfeladando)}{at('igen')}{else}{at('nem')}{/if}</td>
</tr>
