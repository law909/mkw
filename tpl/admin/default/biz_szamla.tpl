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
                <p>Adószám: {$egyed.szamlaadoszam}</p>
            </div>
        </div>
    </div>
    <div class="row pull-left row-inner">
        <p class="head2label pull-left">Fizetési mód: {$egyed.fizmodnev|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Szállítási mód: {$egyed.szallmodnev|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Kelt: {$egyed.keltstr|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Teljesítés: {$egyed.teljesitesstr|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Esedékesség: {$egyed.esedekessegstr|default:"&nbsp;"}</p>
    </div>
    <div class="row pull-left">
        <div class="border">
            <div class="row-inner">
                {$egyed.megjegyzes}
            </div>
        </div>
    </div>
    <table class="teteltable">
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
            {foreach $egyed.tetellista as $tetel}
                <tr>
                    <td>{$tetel.cikkszam}</td>
                    <td>{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</td>
                    <td class="textalignright">{number_format($tetel.mennyiseg,0,'',' ')} {$tetel.me}</td>
                    <td class="textalignright">{number_format($tetel.nettoegysarhuf,0,'',' ')}</td>
                    <td class="textalignright">{number_format($tetel.nettohuf,0,'',' ')}</td>
                    <td class="textalignright">{$tetel.afanev}</td>
                    <td class="textalignright">{number_format($tetel.afahuf,0,'',' ')}</td>
                    <td class="textalignright">{number_format($tetel.bruttohuf,0,'',' ')}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="row pull-left"><div class="border"><div class="row-inner bold">ÁFA részletezés</div></div></div>
    <table class="afaosszesitotable">
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
                    <td class="textalignright">{number_format($a.netto,0,'',' ')}</td>
                    <td class="textalignright">{number_format($a.afa,0,'',' ')}</td>
                    <td class="textalignright">{number_format($a.brutto,0,'',' ')}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    </div>
    <div class="lablec">FASZ</div>
{/block}