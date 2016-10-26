{extends "../rep_base.tpl"}

{block "body"}
{$osszesen = 0}
{$cnt = count($tetelek)}
{$i = 0}
{while $i < $cnt}
    {$tetel = $tetelek[$i]}
    {$ukid = $tetel.uzletkoto_id}
    {$ukertek = 0}
    <div class="pagebreakbefore"></div>
    <h4 xmlns="http://www.w3.org/1999/html">Bizonylatétel lista</h4>
    <h5>{$datumnev} {$tolstr} - {$igstr}</h5>
    <h5>{$cimkenevek}</h5>
    <table>
        <thead>
            <tr>
                <th class="headercell">Partner</th>
                <th class="headercell"></th>
                {if ($ertektipus)}
                    <th class="headercell textalignright">Érték</th>
                {/if}
            </tr>
            <tr>
                <th class="headercell">Partner</th>
                <th class="headercell"></th>
                {if ($ertektipus)}
                    <th class="headercell textalignright">Value</th>
                {/if}
            </tr>
        </thead>
        <tbody>
            <tr class="italic bold">
                <td colspan="3" class="cell">
                    {$tetel.uzletkotonev}
                </td>
            </tr>
            {while ($ukid == $tetelek[$i].uzletkoto_id) && ($i < $cnt)}
                {$tetel = $tetelek[$i]}
                {$osszesen = $osszesen + $tetel.ertek}
                {$ukertek = $ukertek + $tetel.ertek}
                <tr>
                    <td class="datacell">{$tetel.partnernev}</td>
                    <td class="datacell">{$tetel.partnerirszam} {$tetel.partnervaros} {$tetel.partnerutca}</td>
                    {if ($ertektipus)}
                        <td class="datacell textalignright">{bizformat($tetel.ertek)}</td>
                    {/if}
                </tr>
                {$i = $i + 1}
            {/while}
        </tbody>
        <tfoot>
            <tr class="italic bold">
                <td colspan="2" class="cell">{$tetel.uzletkotonev} total</td>
                {if ($ertektipus)}
                    <td class="datacell textalignright">{bizformat($ukertek)}</td>
                {/if}
            </tr>
        </tfoot>
    </table>
{/while}
{/block}