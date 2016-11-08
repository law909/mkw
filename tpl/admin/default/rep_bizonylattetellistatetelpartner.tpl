{extends "../rep_base.tpl"}

{block "body"}
<h4 xmlns="http://www.w3.org/1999/html">Bizonylatétel lista</h4>
<h5>{$datumnev} {$tolstr} - {$igstr}</h5>
{if ($partnernev)}<h5>{$partnernev}</h5>{/if}
{if ($raktarnev)}<h5>{$raktarnev}</h5>{/if}
{if ($uknev)}<h5>{$uknev}</h5>{/if}
{if ($fizmodnev)}<h5>{$fizmodnev}</h5>{/if}
{if ($cimkenevek)}<h5>{$cimkenevek}</h5>{/if}
<table>
    <thead>
    <tr>
        <th class="headercell">Cikkszám</th>
        <th class="headercell">Név</th>
        <th class="headercell textalignright">Mennyiség</th>
        {if ($ertektipus)}
            <th class="headercell textalignright">Érték</th>
        {/if}
        {if ($keszletkell)}
            {foreach $raktarlista as $raktar}
                <th class="headercell">{$raktar}</th>
            {/foreach}
        {/if}
    </tr>
    <tr>
        <th class="headercell">Item no.</th>
        <th class="headercell">Name</th>
        <th class="headercell textalignright">Quantity</th>
        {if ($ertektipus)}
            <th class="headercell textalignright">Value</th>
        {/if}
        {if ($keszletkell)}
            {foreach $raktarlista as $raktar}
                <th class="headercell">{$raktar}</th>
            {/foreach}
        {/if}
    </tr>
    </thead>
    <tbody>
    {$snyito = 0}
    {$snyitoe = 0}
    {$cnt = count($tetelek)}
    {$i = 0}
    {while $i < $cnt}
        {$tetel = $tetelek[$i]}
        {$partnerid = $tetel.partner_id}
        {$pmenny = 0}
        {$pertek = 0}
        <tr class="italic bold">
            <td colspan="{4+count($raktarlista)}" class="cell">
                {$tetel.partnernev} {$tetel.partnerirszam} {$tetel.partnervaros} {$tetel.partnerutca}
            </td>
        </tr>
        {while ($partnerid == $tetelek[$i].partner_id) && ($i < $cnt)}
            {$tetel = $tetelek[$i]}
            {$snyito = $snyito + $tetel.mennyiseg}
            {$snyitoe = $snyitoe + $tetel.ertek}
            {$pmenny = $pmenny + $tetel.mennyiseg}
            {$pertek = $pertek + $tetel.ertek}
            <tr>
                <td class="datacell">{$tetel.cikkszam}</td>
                <td class="datacell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
                <td class="datacell textalignright nowrap">{bizformat($tetel.mennyiseg)}</td>
                {if ($ertektipus)}
                    <td class="datacell textalignright nowrap">{bizformat($tetel.ertek)}</td>
                {/if}
                {if ($keszletkell)}
                    {foreach $tetel.keszletinfo as $kkey => $keszlet}
                        <td class="datacell textalignright nowrap">{$keszlet}</td>
                    {/foreach}
                {/if}
            </tr>
            {$i = $i + 1}
        {/while}
        <tr class="italic bold">
            <td colspan="2" class="cell">{$tetel.partnernev} összesen / total</td>
            <td class="datacell textalignright nowrap">{bizformat($pmenny)}</td>
            {if ($ertektipus)}
                <td class="datacell textalignright nowrap">{bizformat($pertek)}</td>
            {/if}
        </tr>
    {/while}
    </tbody>
    <tfoot>
    <tr>
        <td class="datacell">Összesen / Total</td>
        <td class="datacell"></td>
        <td class="datacell textalignright nowrap">{bizformat($snyito)}</td>
        {if ($ertektipus)}
            <td class="datacell textalignright nowrap">{bizformat($snyitoe)}</td>
        {/if}
        {if ($keszletkell)}
            {foreach $raktarlista as $raktar}
                <td class="cell"></td>
            {/foreach}
        {/if}
    </tr>
    </tfoot>
</table>
{/block}