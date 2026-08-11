{* Egy sor a leltár felvételi listáján. A mentés soronként, azonnal történik – nincs form. *}
<tr class="js-leltartetel leltarfelvetel-tetel" data-tetelid="{$tetelid}">
    <td>{$cikkszam|escape}</td>
    <td>{$nev|escape}</td>
    <td class="leltarfelvetel-num">{$gepimennyiseg|string_format:"%g"}</td>
    <td>
        <input class="js-leltartenymennyiseg leltarfelvetel-num" type="number" step="any" value="{$tenymennyiseg|string_format:"%g"}">
    </td>
    <td class="leltarfelvetel-num {if ($tenymennyiseg < $gepimennyiseg)}redtext{elseif ($tenymennyiseg > $gepimennyiseg)}greentext{/if}">
        {($tenymennyiseg - $gepimennyiseg)|string_format:"%+g"}
    </td>
    <td>
        <a class="js-leltarteteldel leltarfelvetel-del ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
           href="#" title="{at('Töröl')}"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></a>
    </td>
</tr>
