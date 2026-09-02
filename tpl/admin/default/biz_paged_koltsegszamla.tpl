{*
    A default téma "teto" családjának lapozott alapja. Ezek a sablonok másképp épülnek fel, mint a
    számla-család: egy tétel EGY sor, az összesítő alatt pedig "Átvevő:" aláírásvonal áll.
*}
{extends "biz_paged_base.tpl"}

{* a leltárbizonylatokon százmilliós összegek is előfordulnak, ezért a pénzoszlopok bővek:
   ha a tartalom nem fér a megadott szélességbe, az mPDF tételenként másképp osztja újra,
   és elcsúsznak az oszlopok *}
{$w = ['cikkszam'=>'24mm','termek'=>'50mm','mennyiseg'=>'17mm','egysar'=>'21mm','netto'=>'22mm','afanev'=>'8mm','afa'=>'22mm','brutto'=>'26mm']}

{block "title"}{$egyed.bizonylatnev}{/block}

{block "headboxes"}{include "biz_paged_headboxki_reverse.tpl"}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="18%">Eredeti biz.szám</td>
            <td width="16%">Fizetési mód</td>
            <td width="15%">Kelt</td>
            <td width="15%">Teljesítés</td>
            <td width="15%">Esedékesség</td>
            <td width="21%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.erbizonylatszam|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}

{block "headextra"}
    {if ($egyed.megjegyzes)}
        <div style="padding: 0 5px;">Közlemény: {$egyed.megjegyzes}</div>
        <div class="topline topbottommargin"></div>
    {/if}
{/block}

{block "columnheaders"}
    <tr class="bold">
        <td width="{$w.cikkszam}">Cikkszám</td>
        <td width="{$w.termek}">Termék neve</td>
        <td width="{$w.mennyiseg}" class="textalignright">Mennyiség</td>
        <td width="{$w.egysar}" class="textalignright">Nettó e.ár</td>
        <td width="{$w.netto}" class="textalignright">Nettó érték</td>
        <td width="{$w.afanev}" class="textalignright">ÁFA %</td>
        <td width="{$w.afa}" class="textalignright">ÁFA</td>
        <td width="{$w.brutto}" class="textalignright">Bruttó érték</td>
    </tr>
{/block}

{* ebben a családban egy tétel egyetlen sor, nem kettő *}
{block "itemrows"}
    <tr class="tetelsor">
        <td width="{$w.cikkszam}">{$tetel.cikkszam}</td>
        <td width="{$w.termek}">{$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek} {/foreach}{if ($tetel.termekegyediazonosito|default)}({$tetel.termekegyediazonosito}) {/if}</td>
        <td width="{$w.mennyiseg}" class="textalignright">{number_format($tetel.mennyiseg,2,',',' ')} {$tetel.me}</td>
        <td width="{$w.egysar}" class="textalignright">{number_format($tetel.nettoegysar,2,',',' ')}</td>
        <td width="{$w.netto}" class="textalignright">{number_format($tetel.netto,2,',',' ')}</td>
        <td width="{$w.afanev}" class="textalignright">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright">{number_format($tetel.afa,2,',',' ')}</td>
        <td width="{$w.brutto}" class="textalignright">{number_format($tetel.brutto,2,',',' ')}</td>
    </tr>
{/block}

{* a pénzügyi rész a többi bizonylattal közös; ez a család csak az aláírásvonalat teszi alá *}
{block "summary"}
    {include "biz_paged_summary.tpl"}
    {* az aláírásvonal a cella alsó szegélye: az üres <div class="topline">-t az mPDF táblacellában
       nem rajzolja ki, a padding adja alatta az aláírásnyi helyet *}
    <table cellspacing="0" cellpadding="0" border="0" style="width: 190mm; padding-top: 10px;">
        <tr>
            <td width="95mm" style="border-bottom: solid 1px black; padding-bottom: 10mm;">Átvevő:</td>
            <td width="95mm" class="textalignright topalign">{block "thanks"}Köszönjük, hogy nálunk vásárolt!{/block}</td>
        </tr>
    </table>
    {* egycellás 190 mm-es tábla, különben a textaligncenter csak a tartalom saját szélességén központoz *}
    <table cellspacing="0" cellpadding="0" border="0" style="width: 190mm; padding-top: 5px;">
        <tr>
            <td class="textaligncenter">{if ($egyed.nyomtatva)}Másolat{else}Eredeti példány{/if}.</td>
        </tr>
    </table>
{/block}
