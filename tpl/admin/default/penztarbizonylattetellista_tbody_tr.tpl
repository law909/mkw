<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if (!$_egyed.nemrossz)} class="rontott"{/if}>
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.fejid}" data-oper="edit"
           title="{at('Bizonylat megtekintése')}">{$_egyed.fejid}</a>
        {if ($_egyed.fejid)}
            <a class="js-printbizonylat" href="/admin/penztarbizonylatfej/print?id={$_egyed.fejid|escape:'url'}"
               title="{at('Nyomtat')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
        {/if}
        {if ($showerbizonylatszam && $_egyed.erbizonylatszam)}
            <div>{at('Er.biz.szám')}: {$_egyed.erbizonylatszam}</div>
        {/if}
        <div>{$_egyed.penztarnev}</div>
        <div>{if ($_egyed.irany > 0)}{at('befizetés')}{else}{at('kifizetés')}{/if}</div>
    </td>
    <td class="cell">
        {$_egyed.partnernev}
    </td>
    <td class="cell">
        {$_egyed.keltstr}
    </td>
    <td class="cell">
        {$_egyed.jogcimnev}
        {if ($_egyed.szoveg)}
            <div>{$_egyed.szoveg}</div>
        {/if}
        {if ($_egyed.hivatkozottbizonylat)}
            <div>
                {at('Hivatkozott bizonylat')}:
                {if ($_egyed.hivatkozottbizonylatlink)}
                    <a href="{$_egyed.hivatkozottbizonylatlink}" target="_blank"
                       title="{at('Ugrás a bizonylathoz')}">{$_egyed.hivatkozottbizonylat}</a>
                {else}
                    {$_egyed.hivatkozottbizonylat}
                {/if}
            </div>
        {/if}
        {if ($_egyed.hivatkozottdatumstr)}
            <div>{at('Esedékesség')}: {$_egyed.hivatkozottdatumstr}</div>
        {/if}
    </td>
    <td class="cell textalignright">
        {number_format($_egyed.brutto, 2, '.', ' ')} {$_egyed.valutanemnev}
    </td>
</tr>
