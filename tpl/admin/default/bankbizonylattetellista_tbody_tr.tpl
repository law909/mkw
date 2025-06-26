<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox"></td>
    <td class="cell">
        <a class="mattable-editlink" href="#" data-egyedid="{$_egyed.fejid}" data-oper="edit"
           title="{at('Bizonylat megtekintése')}">{$_egyed.fejid}</a>
        {if ($_egyed.erbizonylatszam)}
            <div>{at('Er.biz.szám')}: {$_egyed.erbizonylatszam}</div>
        {/if}
        <div>{at('Kelt')}: {$_egyed.keltstr}</div>
    </td>
    <td class="cell">
        {$_egyed.partnernev}
    </td>
    <td class="cell">
        {$_egyed.datumstr}
    </td>
    <td class="cell">
        {$_egyed.jogcimnev}
    </td>
    <td class="cell">
        {$_egyed.hivatkozottbizonylat}
        {if ($_egyed.hivatkozottdatumstr)}
            <div>{at('Esedékesség')}: {$_egyed.hivatkozottdatumstr}</div>
        {/if}
    </td>
    <td class="cell textalignright">
        {number_format($_egyed.brutto, 2, '.', ' ')} {$_egyed.valutanemnev}
    </td>
</tr>