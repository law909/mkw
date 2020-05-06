<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell">
        <input class="maincheckbox" type="checkbox">
    </td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.partnernev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">
        {$_egyed.termeknev} ({$_egyed.elfogyottalkalom + $_egyed.offlineelfogyottalkalom} alkalom lejárva)
    </td>
    <td class="cell">{$_egyed.vasarlasnapja}</td>
    <td class="cell">{$_egyed.lejaratdatum}</td>
    <td class="cell">{if ($_egyed.lejart)}LEJÁRT{/if}</td>
    <td class="cell">{if ($_egyed.nincsfizetve)}NINCS KIFIZETVE{/if}</td>
</tr>