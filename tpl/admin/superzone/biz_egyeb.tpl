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
                    <td class="textaligncenter bold">Fiz.határidő</td>
                    <td class="textaligncenter bold">Fizetési mód</td>
                    <td class="textaligncenter bold">Pénznem</td>
                    <td class="textaligncenter bold">Biz. száma</td>
                </tr>
                <tr>
                    <td class="textaligncenter">{$egyed.keltstr|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.teljesitesstr|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.esedekessegstr|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.fizmodnev|default:"&nbsp;"}</td>
                    <td class="textaligncenter">{$egyed.valutanemnev|default:"&nbsp;"}</td>
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
                    <th class="textalignright bold">Egységár</th>
                    <th class="textalignright bold">Nettó érték</th>
                    <th class="textalignright bold">ÁFA</th>
                    <th class="textalignright bold">ÁFA érték</th>
                    <th class="textalignright bold">Bruttó érték</th>
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
                        <td class="textalignright">{bizformat($tetel.nettoegysar)}</td>
                        <td class="textalignright">{bizformat($tetel.netto)}</td>
                        <td class="textalignright">{$tetel.afanev}</td>
                        <td class="textalignright">{bizformat($tetel.afa)}</td>
                        <td class="textalignright">{bizformat($tetel.brutto)}</td>
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
    <div class="fullwidth pull-left topmargin osszesen">
        <div class="halfwidth bold pull-left">Összesen</div>
        <div class="halfwidth bold pull-left textalignright">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</div>
    </div>
    <div class="halfwidth pull-left topmargin10">
        <p>Összes mennyiség: {bizformat($summennyiseg)}</p>
    </div>
    <table class="halfwidth pull-right topmargin10">
        <tbody>
            <tr>
                <td></td>
                <td class="textalignright bold">Nettó</td>
                <td class="textalignright bold">ÁFA</td>
                <td class="textalignright bold">Bruttó</td>
            </tr>
            {foreach $afaosszesito as $a}
                <tr>
                    <td>{$a.caption}</td>
                    <td class="textalignright">{bizformat($a.netto)}</td>
                    <td class="textalignright">{bizformat($a.afa)}</td>
                    <td class="textalignright">{bizformat($a.brutto)}</td>
                </tr>
            {/foreach}
            <tr>
                <td class="topline" colspan="5"></td>
            </tr>
            <tr class="bold">
                <td>Összesen</td>
                <td class="textalignright">{bizformat($egyed.netto)}</td>
                <td class="textalignright">{bizformat($egyed.afa)}</td>
                <td class="textalignright">{bizformat($egyed.brutto)}</td>
            </tr>
        </tbody>
    </table>
    <div class="clear toppadding10">
        <div class="textalignright osszesen bold">
            azaz {$egyed.fizetendokiirva} {$egyed.valutanemnev}
        </div>
        <div class="textalignright osszesen bold">
            Fizetendő végösszeg: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
        </div>
    </div>
    <div class="topmargin">
        <p class="keszult">Készült az MKW Webshop számlázó moduljával.</p>
    </div>
{/block}