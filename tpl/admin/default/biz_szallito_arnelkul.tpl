{extends "biz_base.tpl"}

{block "body"}
    <div class="teto">
    <div>
        <div class="biznev">{$egyed.bizonylatnev}</div>
        <div class="bizszam textalignright">{$egyed.id}</div>
    </div>
    <div class="headbox pull-left">
        <div class="headboxborder border">
            <div class="headboxinner">
                <p class="bold">Szállító:</p>
                <p class="nev bold">{$egyed.tulajnev}</p>
                <p>{$egyed.tulajirszam} {$egyed.tulajvaros}</p>
                <p>{$egyed.tulajutca}</p>
                <p>Adószám: {$egyed.tulajadoszam}</p>
                <p>Bankszámla: {$egyed.bankszamlanev}</p>
            </div>
        </div>
    </div>
    <div class="headbox pull-left">
        <div class="headboxborder border">
            <div class="headboxinner">
                <p class="bold">Vevő:</p>
                <p class="nev bold">{$egyed.szamlanev}</p>
                <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
                <p>{$egyed.szamlautca}</p>
                <p>Adószám: {$egyed.adoszam}</p>
            </div>
        </div>
    </div>
    <div class="row pull-left row-inner">
        <p class="head2label pull-left">Fizetési mód: {$egyed.fizmodnev|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Kelt: {$egyed.keltstr|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Teljesítés: {$egyed.teljesitesstr|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Esedékesség: {$egyed.esedekessegstr|default:"&nbsp;"}</p>
    </div>
    <div class="row pull-left">
        <div class="border">
            <div class="row-inner">
                {if ($egyed.megjegyzes|default)}
                Közlemény: {$egyed.megjegyzes}
                {/if}
            </div>
        </div>
    </div>
    <table class="teteltable pull-left">
        <thead>
            <th>Cikkszám</th>
            <th>Termék neve</th>
            <th class="textalignright">Mennyiség</th>
        </thead>
        <tbody>
            {foreach $egyed.tetellista as $tetel}
                <tr class="tetelsor">
                    <td>{$tetel.cikkszam}</td>
                    <td>{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</td>
                    <td class="textalignright">{number_format($tetel.mennyiseg,0,'',' ')} {$tetel.me}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    </div>
    <div class="lablec pull-left">
        <div>
            Átvevő:
        </div>
        <div class="line"></div>
    </div>
    <div class="keszult textaligncenter">Készült az MKW Webshop számlázó moduljával.</div>
{/block}