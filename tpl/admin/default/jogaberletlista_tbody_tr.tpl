<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.nincsfizetve)} class="redtext"{/if}>
    <td class="cell">
        <input class="maincheckbox" type="checkbox">
    </td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.partnernev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        <table>
            <tbody>
                <tr><td>{at('Létrehozva')}:</td><td>{$_egyed.createdby} {$_egyed.createdstr}</td></tr>
                <tr><td>{at('Módosítva')}:</td><td>{$_egyed.updatedby} {$_egyed.lastmodstr}</td></tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_egyed.termeknev} <span class="mattable-important">({$_egyed.elfogyottalkalom + $_egyed.offlineelfogyottalkalom} alkalom lejárva)</span> ({$_egyed.id})
        {foreach $_egyed.latogatasok as $latogatas}
        <div>{$latogatas.datum} {$latogatas.nap}, {$latogatas.tanar} {$latogatas.oratipus}</div>
        {/foreach}
    </td>
    <td class="cell">{$_egyed.bruttoar}</td>
    <td class="cell">{$_egyed.vasarlasnapja}</td>
    <td class="cell">{$_egyed.lejaratdatum}</td>
    <td class="cell">{if ($_egyed.lejart)}LEJÁRT{/if}</td>
    <td class="cell">{if ($_egyed.nincsfizetve)}NINCS KIFIZETVE{/if}</td>
</tr>