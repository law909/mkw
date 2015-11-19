{extends "rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Kintlevőség</h4>
    <h5>{$datumnev} {$tolstr} - {$igstr}</h5>
    <h5>Befizetések {$befdatumstr}-ig</h5>
    <h5>{$partnernev}</h5>
    <h5>{$cimkenevek}</h5>
    <h5>{$uknev}</h5>
    <table>
        <thead>
        <tr>
            <th>Bizonylatszám</th>
            <th>Kelt</th>
            <th>Teljesítés</th>
            <th>Esedékesség</th>
            <th>Lejárat</th>
            <th class="textalignright">Fizetendő</th>
            <th class="textalignright">Tartozás</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {$bsum = 0}
        {$tsum = 0}
        {$cnt = count($lista)}
        {$cikl = 0}
        {while ($cikl < $cnt)}
            {$partner = $lista[$cikl]}
            {$partnerid = $partner.partner_id}
            {$pbsum = 0}
            {$ptsum = 0}
            {$plejartsum = 0}
            {$pnemlejartsum = 0}
            <tr class="italic">
                <td colspan="8" class="cell">
                    {$partner.nev} {$partner.irszam} {$partner.varos} {$partner.utca}
                </td>
            </tr>
            {while (($cikl < $cnt) && ($partnerid == $lista[$cikl].partner_id))}
                {$elem = $lista[$cikl]}
                <tr>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.bizonylatfej_id}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.kelt}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.teljesites}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.hivatkozottdatum}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.lejartnap} nap</td>
                    <td class="cell textalignright nowrap{if ($elem.lejart)} lejart{/if}">{bizformat($elem.brutto)}</td>
                    <td class="cell textalignright nowrap{if ($elem.lejart)} lejart{/if}">{bizformat($elem.tartozas)}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.valutanemnev}</td>
                </tr>
                {$bsum = $bsum + $elem.brutto}
                {$tsum = $tsum + $elem.tartozas}
                {$pbsum = $pbsum + $elem.brutto}
                {$ptsum = $ptsum + $elem.tartozas}
                {if ($elem.lejart)}
                    {$plejartsum = $plejartsum + $elem.tartozas}
                {else}
                    {$pnemlejartsum = $pnemlejartsum + $elem.tartozas}
                {/if}
                {$cikl = $cikl + 1}
            {/while}
            {if ($reszletessum)}
                <tr class="italic bold">
                    <td colspan="6" class="cell">{$partner.nev} összesen nem lejárt</td>
                    <td class="cell textalignright">{bizformat($pnemlejartsum)}</td>
                    <td class="cell"></td>
                </tr>
                <tr class="italic bold">
                    <td colspan="6" class="cell">{$partner.nev} összesen lejárt</td>
                    <td class="cell textalignright">{bizformat($plejartsum)}</td>
                    <td class="cell"></td>
                </tr>
            {/if}
            <tr class="italic bold">
                <td colspan="5" class="cell">{$partner.nev} összesen</td>
                <td class="cell textalignright">{bizformat($pbsum)}</td>
                <td class="cell textalignright">{bizformat($ptsum)}</td>
                <td class="cell"></td>
            </tr>
        {/while}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td class="textalignright">{bizformat($bsum)}</td>
            <td class="textalignright">{bizformat($tsum)}</td>
            <td></td>
        </tr>
        </tfoot>
    </table>
{/block}