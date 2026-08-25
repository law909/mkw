<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.inaktiv)} class="rontott"{/if}>
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        {if ($_egyed.bankbizonylatkesz || $_egyed.inaktiv)}
            {$_egyed.csomagszam}
        {else}
            <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.csomagszam}</a>
        {/if}
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
    <td class="cell">
        {foreach $_egyed.bizonylatszamlinkek as $_link}
            <div>{if ($_link.url)}<a href="{$_link.url}" target="_blank">{$_link.szam}</a>{else}{$_link.szam}{/if}</div>
        {/foreach}
        {if ($_egyed.bankbizonylatkesz)}
            <div>{at('Bankbizonylat')}:
                {if ($_egyed.bankbizonylaturl)}
                    <a href="{$_egyed.bankbizonylaturl}" target="_blank">{$_egyed.bankbizonylatszam}</a>
                {else}
                    {$_egyed.bankbizonylatszam|default:at('kész')}
                {/if}
            </div>
        {/if}
    </td>
    <td class="cell">
        {if ($_egyed.ugyfelhivatkozaslink)}
            <div>{if ($_egyed.ugyfelhivatkozaslink.url)}<a href="{$_egyed.ugyfelhivatkozaslink.url}"
                                                           target="_blank">{$_egyed.ugyfelhivatkozaslink.szam}</a>{else}{$_egyed.ugyfelhivatkozaslink.szam}{/if}</div>
        {/if}
        {if ($_egyed.utanvethivatkozaslink)}
            <div>{if ($_egyed.utanvethivatkozaslink.url)}<a href="{$_egyed.utanvethivatkozaslink.url}"
                                                            target="_blank">{$_egyed.utanvethivatkozaslink.szam}</a>{else}{$_egyed.utanvethivatkozaslink.szam}{/if}</div>
        {/if}
    </td>
</tr>
