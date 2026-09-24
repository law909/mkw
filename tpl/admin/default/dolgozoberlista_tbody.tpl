{foreach $egyedlista as $_egyed}
    {include 'dolgozoberlista_tbody_tr.tpl'}
{/foreach}
<tr class="dolgozober-osszesen">
    <td class="cell"></td>
    <td class="cell" colspan="3">{at('Összesen a szűrt sorokból, rontottak nélkül, minden oldalon')}:</td>
    <td class="cell textalignright">{bizformat($osszeg, 0)}</td>
    <td class="cell" colspan="2"></td>
</tr>
