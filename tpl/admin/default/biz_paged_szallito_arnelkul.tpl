{extends "biz_paged_base.tpl"}

{* ár nélküli szállítólevél: nincs egységár/érték/ÁFA oszlop és nincs ÁFA-összesítő *}
{* a 'nevsor' a colspan-os terméknév-cella szélessége: a sorszám utáni oszlopok összege.
   Enélkül az mPDF minden tételnél újraszámolja a beágyazott tábla oszlopait, és a rövid nevű
   tétel számoszlopai elcsúsznak a többihez képest. *}
{$w = ['sorszam'=>'11mm','termek'=>'118mm','mennyiseg'=>'38mm','me'=>'23mm','nevsor'=>'179mm']}

{block "title"}{$egyed.bizonylatnev}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="33%">Kelt</td>
            <td width="33%">Teljesítés</td>
            <td width="34%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
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
    </tr>
{/block}

{block "itemrows"}
    <tr class="tetelsor">
        <td width="{$w.sorszam}">{$teteldb + 1}</td>
        <td width="{$w.termek}"></td>
        <td width="{$w.mennyiseg}" class="textalignright">{bizformat($tetel.mennyiseg)}</td>
        <td width="{$w.me}">{$tetel.me}</td>
    </tr>
    <tr class="tetelsor">
        <td class="dashedline"></td>
        <td colspan="3" width="{$w.nevsor}" class="dashedline bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}({$tetel.vtszszam})</td>
    </tr>
{/block}

{block "summary"}
    <div>Összes mennyiség: {bizformat($summennyiseg)}</div>
{/block}
