{foreach $egyedlista as $_egyed}
    {include 'dolgozoberlista_tbody_tr.tpl'}
{/foreach}
<tr class="dolgozober-osszesen">
    <td class="cell"></td>
    <td class="cell" colspan="2" data-oszlop="{at('Összesen')}">{at('Összesen a szűrt sorokból, rontottak nélkül, minden oldalon')}:</td>
    <td class="cell textalignright mattable-important">{bizformat($osszeg, 0)}</td>
</tr>
