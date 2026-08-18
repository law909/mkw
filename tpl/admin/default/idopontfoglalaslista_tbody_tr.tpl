<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit"
           title="{at('Szerkeszt')}">{$_egyed.datum} {$_egyed.idopontkezdet}</a>
        <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        <div>{$_egyed.napnev}</div>
        <div>{$_egyed.idoponttemanev}</div>
        <div>{$_egyed.idopontdolgozonev}</div>
        <div>{$_egyed.idoponthelyszinnev}</div>
    </td>
    <td class="cell">
        <div>{$_egyed.partnernev}</div>
        <div>{$_egyed.partneremail}</div>
        <div>{$_egyed.partnertelefon}</div>
    </td>
    <td class="cell">{$_egyed.foglalasido}</td>
    <td class="cell">{if ($_egyed.online)}{at('online')}{else}{at('élő')}{/if}</td>
    <td class="cell">
        {if ($emlekeztetosablonvan && !$_egyed.lemondva)}
            {if ($_egyed.emailemlekezteto)}
                <div>{at('Utolsó emlékeztető')}: {$_egyed.emailemlekeztetodatum}</div>
            {/if}
            <div><a class="js-emailemlekezteto" href="#" data-id="{$_egyed.id}">{at('Emlékeztető email')}</a></div>
        {/if}
        {if ($_egyed.lemondva)}
            <div><a class="js-visszaallit" href="#" data-id="{$_egyed.id}">{at('Visszaállít')}</a></div>
        {else}
            <div><a class="js-lemond" href="#" data-id="{$_egyed.id}">{at('Lemond')}</a></div>
        {/if}
    </td>
</tr>
