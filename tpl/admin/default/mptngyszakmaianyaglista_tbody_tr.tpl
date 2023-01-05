<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell">
        <input class="maincheckbox" type="checkbox">
    </td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.cim}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        <div>Kezdés: {$_egyed.kezdodatum} - {$_egyed.kezdoido}</div>
        <div>Típus: {$_egyed.tipusnev}</div>
        <div>Tulajdonos: {$_egyed.tulajdonosnev}</div>
    </td>
    <td class="cell">
        <div>
        {if ($_egyed.biralatkesz)}
            Bírálat kész
        {else}
            Bírálat nincs kész
        {/if}
        </div>
        <div>
            {if ($_egyed.konferencianszerepelhet)}
                Konferencián szerepelhet
            {else}
                Konferencián nem szerepelhet
            {/if}
        </div>
        {if (!allszerzoregistered)}
        <div class="redtext">
            Nincs minden szerző regisztrálva
        </div>
        {/if}
    </td>
</tr>