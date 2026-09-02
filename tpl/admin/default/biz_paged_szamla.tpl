{extends "biz_paged_base.tpl"}

{*
    Egyetlen szélesség-vektor: az oldalfejléc oszlopfejléce és a tételsorok ugyanebből dolgoznak,
    így nem csúszhatnak el egymáshoz képest. Összegük 100%. A "termek" oszlop szűk lehet, mert a
    terméknév a fölötte lévő, colspan-os sorban van – itt csak a fejlécfelirat fér el benne.
*}
{$w = ($egyed.kedvezmenycount > 0)
? ['sorszam'=>'6mm','termek'=>'11mm','mennyiseg'=>'19mm','me'=>'6mm','ebrutto'=>'23mm','kedv'=>'25mm','egysar'=>'17mm','netto'=>'21mm','afanev'=>'7mm','afa'=>'21mm','brutto'=>'34mm','nevsor'=>'184mm']
: ['sorszam'=>'6mm','termek'=>'27mm','mennyiseg'=>'21mm','me'=>'8mm','egysar'=>'25mm','netto'=>'26mm','afanev'=>'9mm','afa'=>'26mm','brutto'=>'42mm','nevsor'=>'184mm']}

{block "title"}Számla{/block}

{block "copymark"}{if ($egyed.nyomtatva)}Másolat{else}Eredeti példány{/if}. {/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="16%">Kelt</td>
            <td width="16%">Teljesítés</td>
            <td width="16%">Fiz.határidő</td>
            <td width="18%">Fizetési mód</td>
            <td width="12%">Pénznem</td>
            <td width="22%" class="textalignright">Számla száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td class="textalignright">{$egyed.id}</td>
        </tr>
    </table>
{/block}

{block "headextra"}
    {if ($egyed.fuvarlevelszam)}
        <div style="padding: 0 5px;">Fuvarlevél száma: {$egyed.fuvarlevelszam}</div>
        <div class="topline topbottommargin"></div>
    {/if}
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
        {if ($egyed.kedvezmenycount > 0)}
            <td width="{$w.ebrutto}" class="textalignright">Eredeti e.ár</td>
            <td width="{$w.kedv}" class="textalignright">Kedvezmény %</td>
        {/if}
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
        <td colspan="{if ($egyed.kedvezmenycount > 0)}10{else}8{/if}" width="{$w.nevsor}"
            class="bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}{if ($tetel.termekegyediazonosito|default)}({$tetel.termekegyediazonosito}) {/if}{if ($maintheme === 'superzoneb2b')}({$tetel.vtszszam}){/if}</td>
    </tr>
    <tr class="tetelsor">
        <td width="{$w.sorszam}" class="dashedline"></td>
        <td width="{$w.termek}" class="dashedline"></td>
        <td width="{$w.mennyiseg}" class="textalignright dashedline">{bizformat($tetel.mennyiseg)}</td>
        <td width="{$w.me}" class="dashedline">{$tetel.me}</td>
        {if ($egyed.kedvezmenycount > 0)}
            <td width="{$w.ebrutto}" class="textalignright dashedline">{bizformat($tetel.ebruttoegysar)}</td>
            <td width="{$w.kedv}" class="textalignright dashedline">{bizformat($tetel.kedvezmeny)}</td>
        {/if}
        <td width="{$w.egysar}" class="textalignright dashedline">{bizformat($tetel.nettoegysar)}</td>
        <td width="{$w.netto}" class="textalignright dashedline">{bizformat($tetel.netto)}</td>
        <td width="{$w.afanev}" class="textalignright dashedline">{$tetel.afanev}</td>
        <td width="{$w.afa}" class="textalignright dashedline">{bizformat($tetel.afa)}</td>
        <td width="{$w.brutto}" class="textalignright dashedline">{bizformat($tetel.brutto)}</td>
    </tr>
{/block}
