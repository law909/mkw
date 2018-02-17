{extends "biz_base.tpl"}

{block "body"}
    {$summennyiseg = 0}
    {$tetelperpage = 18}
    {$utolsooldalmaxtetel = 14}
    {$maxoldalszam = floor(count($egyed.tetellista) / 17) + 1}
    {if (count($egyed.tetellista) % $tetelperpage > $utolsooldalmaxtetel)}
        {$maxoldalszam = $maxoldalszam + 1}
    {/if}
    {for $oldal = 1 to $maxoldalszam}
        <div class="fullwidth">
            <div class="biznev pull-left">{$egyed.bizonylatnev}</div>
            <div class="pull-right">{$oldal}/{$maxoldalszam} oldal</div>
        </div>
        <div class="topline topbottommargin clear"></div>
        {include "biz_headboxki.tpl"}
        <div class="topline topbottommargin clear"></div>
        <table class="fullwidth">
            <tbody>
                <tr>
                    <td class="textaligncenter bold">Kelt</td>
                    <td class="textaligncenter bold">Teljesítés</td>
                    <td class="textaligncenter bold">Biz. száma</td>
                </tr>
                <tr>
                    <td class="textaligncenter">{$egyed.keltstr|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.teljesitesstr|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.id}</td>
                </tr>
            </tbody>
        </table>
        <div class="topline topbottommargin"></div>
        {if ($egyed.megjegyzes)}
        <div class="fullwidth pull-left">
            <div class="row-inner">
                {if ($egyed.megjegyzes|default)}
                    Közlemény: {$egyed.megjegyzes}
                {/if}
            </div>
        </div>
        <div class="topline topbottommargin clear"></div>
        {/if}
        {$kezdosorszam = ($oldal - 1) * $tetelperpage}
        {$vegsorszam = min($kezdosorszam + $tetelperpage - 1, count($egyed.tetellista) - 1)}
        {if ($kezdosorszam <= $vegsorszam)}
        <table class="fullwidth pull-left">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="bold">Termék</th>
                    <th class="textalignright bold">Mennyiség</th>
                    <th class="bold">ME</th>
                </tr>
            </thead>
            <tbody>
                {for $teteldb = $kezdosorszam to $vegsorszam}
                    {$tetel = $egyed.tetellista[$teteldb]}
                    {$summennyiseg = $summennyiseg + $tetel.mennyiseg}
                    <tr class="tetelsor">
                        <td>{$teteldb + 1}</td>
                        <td></td>
                        <td class="textalignright">{bizformat($tetel.mennyiseg)}</td>
                        <td>{$tetel.me}</td>
                    </tr>
                    <tr class="tetelsor">
                        <td class="dashedline"></td>
                        <td colspan="8" class="dashedline bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach} ({$tetel.vtszszam})</td>
                    </tr>
                {/for}
            </tbody>
        </table>
        {/if}
        {if (($maxoldalszam > 1 && $oldal < $maxoldalszam)) }
            <div class="page-break"></div>
        {/if}
    {/for}
    <div class="halfwidth pull-left topmargin10">
        <p>Összes mennyiség: {bizformat($summennyiseg)}</p>
    </div>
{/block}