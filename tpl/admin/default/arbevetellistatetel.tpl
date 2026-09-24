<table>
    <thead>
    <tr>
        {if ($idoszak)}<th class="headercell">{at('Időszak')}</th>{/if}
        {if ($kategoria)}<th class="headercell">{at('Kategória')}</th>{/if}
        {if ($gyarto)}<th class="headercell">{at('Gyártó')}</th>{/if}
        {if ($webshop)}<th class="headercell">{at('Webshop')}</th>{/if}
        <th class="headercell textalignright">{$valueheader|escape}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $rows as $row}
        <tr>
            {if ($idoszak)}<td class="datacell">{$row.idoszak}</td>{/if}
            {if ($kategoria)}<td class="datacell">{$row.kategorianev|escape}</td>{/if}
            {if ($gyarto)}<td class="datacell">{$row.gyartonev|escape}</td>{/if}
            {if ($webshop)}<td class="datacell">{$row.webshopnev|escape}</td>{/if}
            <td class="datacell textalignright">{bizformat($row.ertek)}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        {if ($idoszak)}<td class="datacell"></td>{/if}
        {if ($kategoria)}<td class="datacell"></td>{/if}
        {if ($gyarto)}<td class="datacell"></td>{/if}
        {if ($webshop)}<td class="datacell"></td>{/if}
        <td class="datacell textalignright">{at('Összesen')}: {bizformat($osszesen)}</td>
    </tr>
    </tfoot>
</table>
