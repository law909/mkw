<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_egyed.iso3166}</td>
    <td class="cell">{$_egyed.valutanemnev}</td>
    <td class="cell">{$_egyed.afanev}</td>
    <td class="cell">{if ($_egyed.eu)}{at('EU-n belüli')}{else}{at('EU-n kívüli')}{/if}</td>
</tr>
