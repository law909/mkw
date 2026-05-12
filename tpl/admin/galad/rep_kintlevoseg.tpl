{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Kintlevőség/Assets</h4>
    <h5>{$datumnev} {$tolstr} - {$igstr}</h5>
    <h5>Befizetések/payments before {$befdatumstr}-ig</h5>
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
        <tr>
            <th>Invoice no.</th>
            <th>Issue</th>
            <th>Fulfillment</th>
            <th>Payment due</th>
            <th>Expiry</th>
            <th class="textalignright">Total</th>
            <th class="textalignright">Debit</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {$bsum = array()}
        {$nemlejartsum = array()}
        {$lejartsum = array()}
        {$cnt = count($lista)}
        {$cikl = 0}
        {while ($cikl < $cnt)}
            {$partner = $lista[$cikl]}
            {$partnerid = $partner.partner_id}
            {$pbsum = array()}
            {$plejartsum = array()}
            {$pnemlejartsum = array()}
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
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.lejartnap} nap/day</td>
                    <td class="cell textalignright nowrap{if ($elem.lejart)} lejart{/if}">{bizformat($elem.brutto)}</td>
                    <td class="cell textalignright nowrap{if ($elem.lejart)} lejart{/if}">{bizformat($elem.tartozas)}</td>
                    <td class="cell{if ($elem.lejart)} lejart{/if}">{$elem.valutanemnev}</td>
                </tr>
                {$bsum[$elem.valutanemnev]['brutto'] = $bsum[$elem.valutanemnev]['brutto'] + $elem.brutto}
                {$bsum[$elem.valutanemnev]['tartozas'] = $bsum[$elem.valutanemnev]['tartozas'] + $elem.tartozas}
                {$pbsum[$elem.valutanemnev]['brutto'] = $pbsum[$elem.valutanemnev]['brutto'] + $elem.brutto}
                {$pbsum[$elem.valutanemnev]['tartozas'] = $pbsum[$elem.valutanemnev]['tartozas'] + $elem.tartozas}
                {if ($elem.lejart)}
                    {$plejartsum[$elem.valutanemnev] = $plejartsum[$elem.valutanemnev] + $elem.tartozas}
                    {$lejartsum[$elem.valutanemnev] = $lejartsum[$elem.valutanemnev] + $elem.tartozas}
                {else}
                    {$pnemlejartsum[$elem.valutanemnev] = $pnemlejartsum[$elem.valutanemnev] + $elem.tartozas}
                    {$nemlejartsum[$elem.valutanemnev] = $nemlejartsum[$elem.valutanemnev] + $elem.tartozas}
                {/if}
                {$cikl = $cikl + 1}
            {/while}
            {if ($reszletessum)}
                {foreach $pnemlejartsum as $k=>$pn}
                <tr class="italic bold">
                    <td colspan="6" class="cell">{$partner.nev} összesen nem lejárt/total not expired</td>
                    <td class="cell textalignright">{bizformat($pn)}</td>
                    <td class="cell">{$k}</td>
                </tr>
                {/foreach}
                {foreach $plejartsum as $k=>$pn}
                <tr class="italic bold">
                    <td colspan="6" class="cell">{$partner.nev} összesen lejárt/total expired</td>
                    <td class="cell textalignright">{bizformat($pn)}</td>
                    <td class="cell">{$k}</td>
                </tr>
                {/foreach}
            {/if}
            {foreach $pbsum as $k=>$bs}
                <tr class="italic bold">
                    <td colspan="5" class="cell">{$partner.nev} összesen/total</td>
                    <td class="textalignright">{bizformat($bs['brutto'])}</td>
                    <td class="textalignright">{bizformat($bs['tartozas'])}</td>
                    <td>{$k}</td>
                </tr>
            {/foreach}
        {/while}
        </tbody>
        <tfoot>
        {if ($reszletessum)}
            {foreach $nemlejartsum as $k=>$pn}
            <tr>
                <td colspan="6">Összesen nem lejárt/Total not expired:</td>
                <td class="textalignright">{bizformat($pn)}</td>
                <td>{$k}</td>
            </tr>
            {/foreach}
            {foreach $lejartsum as $k=>$pn}
            <tr>
                <td colspan="6">Összesen lejárt/Total expired:</td>
                <td class="textalignright">{bizformat($pn)}</td>
                <td>{$k}</td>
            </tr>
            {/foreach}
        {/if}
        {foreach $bsum as $k=>$bs}
            <tr>
                <td colspan="5">{$k} összesen/total:</td>
                <td class="textalignright">{bizformat($bs['brutto'])}</td>
                <td class="textalignright">{bizformat($bs['tartozas'])}</td>
                <td>{$k}</td>
            </tr>
        {/foreach}
        </tfoot>
    </table>
{/block}