{extends "biz_base.tpl"}

{block "body"}
    <div class="teto">
    <div>
        <div class="biznev">Díjbekérő</div>
        <div class="bizszam textalignright">{$egyed.id}</div>
    </div>
    {include "biz_headboxki.tpl"}
    <div class="row pull-left row-inner">
        <p class="head2label pull-left">Fizetési mód: {$egyed.fizmodnev|default:"&nbsp;"}</p>
        <p class="head2label pull-left">Kelt: {$egyed.keltstr|default:"&nbsp;"}</p>
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
            <th>ME</th>
            <th class="textalignright">Nettó e.ár</th>
            <th class="textalignright">Nettó érték</th>
            <th class="textalignright">ÁFA %</th>
            <th class="textalignright">ÁFA</th>
            <th class="textalignright">Bruttó érték</th>
        </thead>
        <tbody>
            {foreach $egyed.tetellista as $tetel}
                <tr class="tetelsor">
                    <td>{$tetel.cikkszam}</td>
                    <td>{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</td>
                    <td class="textalignright">{number_format($tetel.mennyiseg,0,'',' ')}</td>
                    <td>{$tetel.me}</td>
                    <td class="textalignright">{number_format($tetel.nettoegysar,0,'',' ')}</td>
                    <td class="textalignright">{number_format($tetel.netto,0,'',' ')}</td>
                    <td class="textalignright">{$tetel.afanev}</td>
                    <td class="textalignright">{number_format($tetel.afa,0,'',' ')}</td>
                    <td class="textalignright">{number_format($tetel.brutto,0,'',' ')}</td>
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
                    <td class="textalignright">{number_format($a.netto,0,'',' ')}</td>
                    <td class="textalignright">{number_format($a.afa,0,'',' ')}</td>
                    <td class="textalignright">{number_format($a.brutto,0,'',' ')}</td>
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
        <div class="pull-left">
            <p>Köszönjük, hogy nálunk vásárolt!</p>
            <p><br>A csomagolás termékdíj-kötelezettség az eladót terheli.</p>
        </div>
        <table class="osszesitotable pull-right">
            <tbody>
                <tr>
                    <td>Nettó:</td>
                    <td class="textalignright">{number_format($egyed.netto,0,'',' ')} Ft</td>
                </tr>
                <tr>
                    <td>ÁFA:</td>
                    <td class="textalignright">{number_format($egyed.afa,0,'',' ')} Ft</td>
                </tr>
                <tr>
                    <td>Bruttó:</td>
                    <td class="textalignright">{number_format($egyed.brutto,0,'',' ')} Ft</td>
                </tr>
                <tr>
                    <td class="bold">Fizetendő:</td>
                    <td class="textalignright bold">{number_format($egyed.fizetendo,0,'',' ')} Ft</td>
                </tr>
                <tr>
                    <td colspan="2" class="fizetendokiirva">{$egyed.fizetendokiirva} forint</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="textaligncenter">A díjbekérő 1 eredeti példányban készült.</div>
{/block}