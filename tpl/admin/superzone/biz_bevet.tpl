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
                <p class="bold">Vevő:</p>
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
                <p class="bold">Szállító:</p>
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
            <th class="textalignright">Nettó e.ár</th>
            <th class="textalignright">Nettó érték</th>
            <th class="textalignright">ÁFA %</th>
            <th class="textalignright">ÁFA</th>
            <th class="textalignright">Bruttó érték</th>
        </thead>
        <tbody>
            {$mennyisegsum = 0}
            {foreach $egyed.tetellista as $tetel}
                {$mennyisegsum = $mennyisegsum + $tetel.mennyiseg}
                <tr class="tetelsor">
                    <td>{$tetel.cikkszam}</td>
                    <td>{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}</td>
                    <td class="textalignright">{$tetel.mennyiseg} {$tetel.me}</td>
                    <td class="textalignright">{$tetel.nettoegysar}</td>
                    <td class="textalignright">{$tetel.netto}</td>
                    <td class="textalignright">{$tetel.afanev}</td>
                    <td class="textalignright">{$tetel.afa}</td>
                    <td class="textalignright">{$tetel.brutto}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="row pull-left"><div class="border"><div class="row-inner bold">ÁFA részletezés</div></div></div>
    <table class="afaosszesitotable pull-left">
        <thead>
            <th>ÁFA kulcs</th>
            <th class="textalignright">Nettó érték</th>
            <th class="textalignright">ÁFA érték</th>
            <th class="textalignright">Bruttó érték</th>
        </thead>
        <tbody>
            {foreach $afaosszesito as $a}
                <tr>
                    <td>{$a.caption}</td>
                    <td class="textalignright">{$a.netto}</td>
                    <td class="textalignright">{$a.afa}</td>
                    <td class="textalignright">{$a.brutto}</td>
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
        <table class="osszesitotable pull-right">
            <tbody>
                <tr>
                    <td>Mennyiség:</td>
                    <td class="textalignright">{$mennyisegsum}</td>
                </tr>
                <tr>
                    <td>Nettó:</td>
                    <td class="textalignright">{$egyed.netto} {$egyed.valutanemnev}</td>
                </tr>
                <tr>
                    <td>ÁFA:</td>
                    <td class="textalignright">{$egyed.afa} {$egyed.valutanemnev}</td>
                </tr>
                <tr>
                    <td>Bruttó:</td>
                    <td class="textalignright">{$egyed.brutto} {$egyed.valutanemnev}</td>
                </tr>
                <tr>
                    <td class="bold">Fizetendő:</td>
                    <td class="textalignright bold">{$egyed.fizetendo} {$egyed.valutanemnev}</td>
                </tr>
                <tr>
                    <td colspan="2" class="fizetendokiirva">{$egyed.fizetendokiirva} {$egyed.valutanemnev}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="keszult textaligncenter">Készült az MKW Webshop számlázó moduljával.</div>
{/block}