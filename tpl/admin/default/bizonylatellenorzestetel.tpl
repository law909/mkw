<table class="js-elltabla">
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell">{at('Változat')}</th>
        <th class="headercell">{at('Vonalkód')}</th>
        <th class="headercell textalignright">{at('Bizonylaton')}</th>
        <th class="headercell textalignright">{at('Ellenőrzött')}</th>
        <th class="headercell textalignright">{at('Eltérés')}</th>
        <th class="headercell"></th>
    </tr>
    </thead>
    <tbody>
    {foreach $tetelek as $tetel}
        <tr class="js-ellsor" data-termekid="{$tetel.termekid}" data-valtozatid="{$tetel.valtozatid}"
            data-elvart="{$tetel.mennyiseg}">
            <td class="datacell">{$tetel.cikkszam|escape}</td>
            <td class="datacell">{$tetel.nev|escape}</td>
            <td class="datacell">{$tetel.valtozatnev|escape}</td>
            <td class="datacell">{$tetel.vonalkod|escape}</td>
            <td class="datacell textalignright">{$tetel.mennyiseg|string_format:"%g"}</td>
            <td class="datacell textalignright"><input class="js-ellszamolt" type="number" step="any" value="0" size="6"></td>
            <td class="datacell textalignright js-ellelteres"></td>
            <td class="datacell"></td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td class="datacell" colspan="4">{at('Összesen')}</td>
        <td class="datacell textalignright js-ellosszelvart"></td>
        <td class="datacell textalignright js-ellosszszamolt"></td>
        <td class="datacell textalignright js-ellosszelteres"></td>
        <td class="datacell"></td>
    </tr>
    </tfoot>
</table>
<div class="js-ellosszegzes"></div>
