<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.nincsfizetve)} class="redtext"{/if}>
    <td class="cell">
        <input class="maincheckbox" type="checkbox">
    </td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.partnernev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
        {if ($_egyed.partneremail)}
            <div><a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a></div>
        {/if}
        <table>
            <tbody>
            <tr>
                <td>{at('Létrehozva')}:</td>
                <td>{$_egyed.createdby} {$_egyed.createdstr}</td>
            </tr>
            <tr>
                <td>{at('Módosítva')}:</td>
                <td>{$_egyed.updatedby} {$_egyed.lastmodstr}</td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_egyed.termeknev} <span class="mattable-important">({$_egyed.elfogyottalkalom + $_egyed.offlineelfogyottalkalom} alkalom lejárva)</span> ({$_egyed.id}
        )<br>
        {foreach $_egyed.latogatasok as $latogatas}
            <div>{$latogatas.datum} {$latogatas.nap}, {$latogatas.tanar} {$latogatas.oratipus}</div>
        {/foreach}
    </td>
    <td class="cell">{$_egyed.bruttoar}</td>
    <td class="cell">{$_egyed.vasarlasnapja}</td>
    <td class="cell">{$_egyed.lejaratdatum}</td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="lejart" class="js-flagcheckbox{if ($_egyed.lejart)} ui-state-hover{/if}">{at('Lejárt')}</a>
                </td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="nincsfizetve"
                       class="js-flagcheckbox{if ($_egyed.nincsfizetve)} ui-state-hover{/if}">{at('Nincs kifizetve')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_egyed.id}" data-flag="szamlazva"
                       class="js-flagcheckbox{if ($_egyed.szamlazva)} ui-state-hover{/if}">{at('Számlázva')}</a></td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>