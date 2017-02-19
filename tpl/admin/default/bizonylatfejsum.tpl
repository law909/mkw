<table>
    <caption>Összesen</caption>
    <tbody>
    <tr title="{at('A bal oldali számok valutanemtől független összesenek. Több valuta esetén nincs értelmük.')}">
        <td></td>
        <td class="mattable-rightaligned"></td>
        {if ($showvalutanem)}
            <td class="mattable-rightaligned hufprice">HUF</td>
        {/if}
    </tr>
    <tr title="{at('A bal oldali számok valutanemtől független összesenek. Több valuta esetén nincs értelmük.')}">
        <td>{at('Nettó')}:</td><td class="mattable-rightaligned pricenowrap">{bizformat($sum.netto)}</td>
        {if ($showvalutanem)}
            <td class="mattable-rightaligned pricenowrap hufprice">{bizformat($sum.nettohuf)}</td>
        {/if}
    </tr>
    <tr title="{at('A bal oldali számok valutanemtől független összesenek. Több valuta esetén nincs értelmük.')}">
        <td>{at('ÁFA')}:</td>
        <td class="mattable-rightaligned pricenowrap">{bizformat($sum.afa)}</td>
        {if ($showvalutanem)}
            <td class="mattable-rightaligned pricenowrap hufprice">{bizformat($sum.afahuf)}
            </td>
        {/if}
    </tr>
    <tr class="mattable-important" title="{at('A bal oldali számok valutanemtől független összesenek. Több valuta esetén nincs értelmük.')}">
        <td>{at('Bruttó')}:</td>
        <td class="mattable-rightaligned pricenowrap">{bizformat($sum.brutto)}</td>
        {if ($showvalutanem)}
            <td class="mattable-rightaligned pricenowrap hufprice">{bizformat($sum.bruttohuf)}</td>
        {/if}
    </tr>
    </tbody>
</table>
