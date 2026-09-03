{*
    Munkalap nyomtatási képe. A számláéra hasonlít, csak a fejlécben a gép adatai (egyedi
    azonosító, km óra állás, hiba leírása, következő szerviz) is szerepelnek, és nincs
    fizetendő végösszeg: a munkalap nem pénzügyi bizonylat, abból a számla készül.

    A bizonylattípus tplname-je közvetlenül ez a sablon: a munkalapnak nincs régi, nem lapozott
    formája.
*}
{extends "biz_paged_base.tpl"}

{$w = ['sorszam'=>'6mm','termek'=>'27mm','mennyiseg'=>'21mm','me'=>'8mm','egysar'=>'25mm','netto'=>'26mm','afanev'=>'9mm','afa'=>'26mm','brutto'=>'42mm','nevsor'=>'184mm']}

{block "title"}Munkalap{/block}

{block "copymark"}{if ($egyed.nyomtatva)}Másolat{else}Eredeti példány{/if}. {/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="16%">Kelt</td>
            <td width="16%">Teljesítés</td>
            <td width="16%">Határidő</td>
            <td width="18%">Státusz</td>
            <td width="12%">Pénznem</td>
            <td width="22%" class="textalignright">Munkalap száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.hataridostr|default:"&nbsp;"}</td>
            <td>{$egyed.munkalapstatusznev|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td class="textalignright">{$egyed.id}</td>
        </tr>
    </table>
{/block}

{block "headextra"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="25%" style="padding: 0 5px;">Egyedi azonosító: <span class="bold">{$egyed.munkalapegyediazonosito|default:"&nbsp;"}</span></td>
            <td width="45%" style="padding: 0 5px;">Gép: <span class="bold">{$egyed.munkalaptermeknev|default:"&nbsp;"}</span></td>
            <td width="30%" style="padding: 0 5px;">Km óra állás: <span class="bold">{$egyed.munkalapkmoraallas|default:"&nbsp;"}</span></td>
        </tr>
    </table>
    {if ($egyed.munkalapkovetkezoszervizstr || $egyed.munkalapkovetkezoszervizkm)}
        <div style="padding: 0 5px;">
            Következő szerviz: {$egyed.munkalapkovetkezoszervizstr|default:"&ndash;"}{if ($egyed.munkalapkovetkezoszervizkm)}, {$egyed.munkalapkovetkezoszervizkm} km{/if}
        </div>
    {/if}
    {if ($egyed.munkalaphibaleiras)}
        <div style="padding: 0 5px;">Hiba leírása: {$egyed.munkalaphibaleiras|escape|nl2br}</div>
    {/if}
    {if ($egyed.megjegyzes)}
        <div style="padding: 0 5px;">Megjegyzés: {$egyed.megjegyzes}</div>
    {/if}
    <div class="topline topbottommargin"></div>
{/block}

{block "columnheaders"}
    <tr class="bold">
        <td width="{$w.sorszam}">#</td>
        <td width="{$w.termek}">Termék</td>
        <td width="{$w.mennyiseg}" class="textalignright">Mennyiség</td>
        <td width="{$w.me}">ME</td>
        <td width="{$w.egysar}" class="textalignright">Egységár</td>
        <td width="{$w.netto}" class="textalignright">Nettó érték</td>
        <td width="{$w.afanev}" class="textalignright">ÁFA</td>
        <td width="{$w.afa}" class="textalignright">ÁFA érték</td>
        <td width="{$w.brutto}" class="textalignright">Bruttó érték</td>
    </tr>
{/block}

{block "itemrows"}
    <tr class="tetelsor">
        <td>{$teteldb + 1}</td>
        <td colspan="8" width="{$w.nevsor}"
            class="bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}{if ($tetel.termekegyediazonosito|default)}({$tetel.termekegyediazonosito}) {/if}</td>
    </tr>
    <tr class="tetelsor">
        <td width="{$w.sorszam}" class="dashedline"></td>
        <td width="{$w.termek}" class="dashedline"></td>
        <td width="{$w.mennyiseg}" class="textalignright dashedline">{bizformat($tetel.mennyiseg)}</td>
        <td width="{$w.me}" class="dashedline">{$tetel.me}</td>
        <td width="{$w.egysar}" class="textalignright dashedline">{bizformat($tetel.nettoegysar)}</td>
        <td width="{$w.netto}" class="textalignright dashedline">{bizformat($tetel.netto)}</td>
        <td width="{$w.afanev}" class="textalignright dashedline">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright dashedline">{bizformat($tetel.afa)}</td>
        <td width="{$w.brutto}" class="textalignright dashedline">{bizformat($tetel.brutto)}</td>
    </tr>
{/block}

{block "summary"}{include "biz_paged_summary.tpl" nemkellfizetendo=true}{/block}
