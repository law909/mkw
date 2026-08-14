{extends "biz_paged_base.tpl"}

{* lásd a biz_paged_szamla.tpl-t: egyetlen szélesség-vektor, összegük 100% *}
{$w = ['sorszam'=>'6mm','termek'=>'27mm','mennyiseg'=>'21mm','me'=>'8mm','egysar'=>'25mm','netto'=>'26mm','afanev'=>'9mm','afa'=>'26mm','brutto'=>'42mm','nevsor'=>'184mm']}

{block "title"}{$egyed.bizonylatnev}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="16%">Kelt</td>
            <td width="16%">Teljesítés</td>
            <td width="16%">Fiz.határidő</td>
            <td width="18%">Fizetési mód</td>
            <td width="12%">Pénznem</td>
            <td width="22%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
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

{* a szállítólevélen a számsor van elöl, alatta a megnevezés – a számlához képest fordítva *}
{block "itemrows"}
    <tr class="tetelsor">
        <td width="{$w.sorszam}">{$teteldb + 1}</td>
        <td width="{$w.termek}"></td>
        <td width="{$w.mennyiseg}" class="textalignright">{bizformat($tetel.mennyiseg)}</td>
        <td width="{$w.me}">{$tetel.me}</td>
        <td width="{$w.egysar}" class="textalignright">{bizformat($tetel.nettoegysar)}</td>
        <td width="{$w.netto}" class="textalignright">{bizformat($tetel.netto)}</td>
        <td width="{$w.afanev}" class="textalignright">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright">{bizformat($tetel.afa)}</td>
        <td width="{$w.brutto}" class="textalignright">{bizformat($tetel.brutto)}</td>
    </tr>
    <tr class="tetelsor">
        <td class="dashedline"></td>
        <td colspan="8" width="{$w.nevsor}" class="dashedline bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}({$tetel.vtszszam})</td>
    </tr>
{/block}
