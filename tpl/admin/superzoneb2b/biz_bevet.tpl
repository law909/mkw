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
        <div class="halfwidth pull-left">
            <div class="headboxinner">
                <p class="bottommargin">Vevő</p>
                <p class="nev bold">{$egyed.tulajnev}</p>
                <p>{$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}</p>
                <p>Adószám: {$egyed.tulajadoszam}</p>
                <p>Bank: {$egyed.tulajbanknev}</p>
                <p>Swift: {$egyed.tulajswift}</p>
                <p>IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}</p>
            </div>
        </div>
        <div class="halfwidth pull-left">
            <div class="headboxinner">
                <p class="bottommargin">Szállító</p>
                <p class="nev bold">{$egyed.szamlanev}</p>
                <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
                <p>{$egyed.szamlautca}</p>
                {if ($egyed.partneradoszam)}
                    <p>Adószám: {$egyed.partneradoszam}</p>
                {/if}
                {if ($egyed.partnereuadoszam)}
                    <p>EU adószám: {$egyed.partnereuadoszam}</p>
                {/if}
            </div>
        </div>
        <div class="topline topbottommargin clear"></div>
        <table class="fullwidth">
            <tbody>
            <tr>
                <td class="textaligncenter bold">Raktár</td>
                <td class="textaligncenter bold">Kelt</td>
                <td class="textaligncenter bold">Teljesítés</td>
                <td class="textaligncenter bold">Fiz.határidő</td>
                <td class="textaligncenter bold">Fizetési mód</td>
                <td class="textaligncenter bold">Pénznem</td>
                <td class="textaligncenter bold">Eredeti biz.szám</td>
                <td class="textaligncenter bold">Biz. száma</td>
            </tr>
            <tr>
                <td class="textaligncenter">{$egyed.raktarnev|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.keltstr|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.teljesitesstr|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.esedekessegstr|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.fizmodnev|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.valutanemnev|default:"&nbsp;"}</td>
                <td class="textaligncenter">{$egyed.erbizonylatszam|default:"&nbsp;"}</td>
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
                        <td colspan="8"
                            class="dashedline bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}
                            ({$tetel.vtszszam})
                        </td>
                    </tr>
                {/for}
                </tbody>
            </table>
        {/if}
        {if (($maxoldalszam > 1 && $oldal < $maxoldalszam)) }
            <div class="page-break"></div>
        {/if}
    {/for}
    {include "biz_summary.tpl"}
{/block}