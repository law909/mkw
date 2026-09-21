<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.oldalid}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
    </td>
    <td class="cell">
        <div>{$_egyed.nev}</div>
        <div>{$_egyed.email}</div>
        {if ($_egyed.jelentkezes)}<div class="mattable-note">{$_egyed.jelentkezes}</div>{/if}
    </td>
    <td class="cell">{$_egyed.megjegyzes}</td>
    <td class="cell">{$_egyed.lejaratstr}{if ($_egyed.honap)} ({$_egyed.honap} {at('hónap')}){/if}</td>
    <td class="cell">{$_egyed.allapot}{if ($_egyed.visszavonva)} ({$_egyed.visszavonvaonstr}{if ($_egyed.visszavonvabynev)}, {$_egyed.visszavonvabynev}{/if}){/if}</td>
    <td class="cell">{$_egyed.createdstr}{if ($_egyed.createdbynev)} ({$_egyed.createdbynev}){/if}</td>
    <td class="cell"><code>{$_egyed.azonosito}</code></td>
</tr>
