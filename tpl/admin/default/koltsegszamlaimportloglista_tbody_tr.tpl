<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.hibas)} class="rontott"{/if}>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Megnéz')}">{$_egyed.createdstr}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        <div class="matt-hseparator"></div>
        <div>{$_egyed.idoszaktolstr} - {$_egyed.idoszakigstr}</div>
    </td>
    <td class="cell">{$_egyed.szamlaszam}</td>
    <td class="cell">{$_egyed.szallito}</td>
    <td class="cell">{$_egyed.statusz}</td>
    <td class="cell">{$_egyed.bizonylatszam}</td>
    <td class="cell">{$_egyed.fejhiba|nl2br}</td>
    <td class="cell">{$_egyed.tetelhiba|nl2br}</td>
</tr>
