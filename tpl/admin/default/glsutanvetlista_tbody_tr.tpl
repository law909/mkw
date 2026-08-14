<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.inaktiv)} class="rontott"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.csomagszam}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        <div class="matt-hseparator"></div>
    </td>
    <td class="cell">{$_egyed.statusz}</td>
    <td class="cell">{$_egyed.statuszdatumstr}</td>
    <td class="cell">{number_format($_egyed.osszeg|default:0, 0, ',', ' ')}</td>
    <td class="cell">
        <div>{$_egyed.nev}</div>
        {if ($_egyed.atvevo)}<div>{at('Átvevő')}: {$_egyed.atvevo}</div>{/if}
    </td>
    <td class="cell">{$_egyed.cim}</td>
    <td class="cell">{$_egyed.bizonylatszamok}</td>
    <td class="cell">
        <div>{$_egyed.ugyfelhivatkozas}</div>
        <div>{$_egyed.utanvethivatkozas}</div>
    </td>
</tr>
