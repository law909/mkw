<table>
    <thead>
    <tr>
        {if ($idoszak)}<th class="headercell">{at('Időszak')}</th>{/if}
        {if ($gyarto)}<th class="headercell">{at('Gyártó')}</th>{/if}
        {if ($webshop)}<th class="headercell">{at('Webshop')}</th>{/if}
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell">{at('Változat')}</th>
        <th class="headercell textalignright">{at('Mennyiség')}</th>
        <th class="headercell">{at('ME')}</th>
        <th class="headercell textalignright">{if ($brutto)}{at('Bruttó HUF')}{else}{at('Nettó HUF')}{/if}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $rows as $row}
        <tr>
            {if ($idoszak)}<td class="datacell">{$row.idoszak}</td>{/if}
            {if ($gyarto)}<td class="datacell">{$row.gyartonev|escape}</td>{/if}
            {if ($webshop)}<td class="datacell">{$row.webshopnev|escape}</td>{/if}
            <td class="datacell">{$row.cikkszam|escape}</td>
            <td class="datacell">{$row.nev|escape}</td>
            <td class="datacell">{$row.ertek1|escape} {$row.ertek2|escape}</td>
            <td class="datacell textalignright">{$row.mennyiseg}</td>
            <td class="datacell">{$row.me|escape}</td>
            <td class="datacell textalignright">{bizformat($row.ertek)}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        {if ($idoszak)}<td class="datacell"></td>{/if}
        {if ($gyarto)}<td class="datacell"></td>{/if}
        {if ($webshop)}<td class="datacell"></td>{/if}
        <td class="datacell" colspan="3">{at('Összesen')}:</td>
        <td class="datacell textalignright">{$osszesenmennyiseg}</td>
        <td class="datacell"></td>
        <td class="datacell textalignright">{bizformat($osszesen)}</td>
    </tr>
    </tfoot>
</table>
