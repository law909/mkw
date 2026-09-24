<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.rontott)} class="rontott"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{if ($_egyed.rontott)}{at('Megtekint')}{else}{at('Szerkeszt')}{/if}">{$_egyed.dolgozonev|escape}</a>
        {if (!$_egyed.rontott)}
            <a class="js-rontber" href="#" data-egyedid="{$_egyed.id}" title="{at('Ront')}"><span class="ui-icon ui-icon-cancel"></span></a>
        {/if}
    </td>
    <td class="cell">{$_egyed.datumstr}</td>
    <td class="cell">{$_egyed.berjogcimnev|escape}</td>
    <td class="cell textalignright">{if ($_egyed.rontott)}<s>{bizformat($_egyed.osszeg, 0)}</s>{else}{bizformat($_egyed.osszeg, 0)}{/if}</td>
    <td class="cell">{$_egyed.megjegyzes|escape}</td>
    <td class="cell">
        {$_egyed.createdby|escape} {$_egyed.createdstr}
        {if ($_egyed.rontott)}<br>{at('Rontotta')}: {$_egyed.rontottby|escape} {$_egyed.rontottonstr}{/if}
    </td>
</tr>
