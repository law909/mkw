{extends "biz_paged_base.tpl"}

{* lásd a biz_paged_szamla.tpl-t: egyetlen szélesség-vektor, összegük 100% *}
{$w = ($egyed.kedvezmenycount > 0)
    ? ['sorszam'=>'6mm','termek'=>'11mm','mennyiseg'=>'19mm','me'=>'6mm','ebrutto'=>'23mm','kedv'=>'25mm','egysar'=>'17mm','netto'=>'21mm','afanev'=>'7mm','afa'=>'21mm','brutto'=>'34mm','nevsor'=>'184mm']
    : ['sorszam'=>'6mm','termek'=>'27mm','mennyiseg'=>'21mm','me'=>'8mm','egysar'=>'25mm','netto'=>'26mm','afanev'=>'9mm','afa'=>'26mm','brutto'=>'42mm','nevsor'=>'184mm']}

{block "title"}Számla / Invoice{/block}

{block "copymark"}{if ($egyed.nyomtatva)}Másolat/Copy{else}Eredeti példány/Original{/if}. {/block}

{block "headboxes"}{include "biz_paged_headboxki_eng.tpl"}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="16%">Kelt</td>
            <td width="16%">Teljesítés</td>
            <td width="16%">Fiz.határidő</td>
            <td width="18%">Fizetési mód</td>
            <td width="12%">Pénznem</td>
            <td width="22%">Számla száma</td>
        </tr>
        <tr class="bold textaligncenter">
            <td>Issue</td>
            <td>Fulfillment</td>
            <td>Payment due</td>
            <td>Payment method</td>
            <td>Currency</td>
            <td>Invoice number</td>
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
    {if ($egyed.fuvarlevelszam)}
        <div style="padding: 0 5px;">Fuvarlevél száma / Delivery note number: {$egyed.fuvarlevelszam}</div>
        <div class="topline topbottommargin"></div>
    {/if}
    {if ($egyed.megjegyzes)}
        <div style="padding: 0 5px;">Közlemény / Notes: {$egyed.megjegyzes}</div>
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
    <tr class="bold">
        <td></td>
        <td>Product</td>
        <td class="textalignright">Quantity</td>
        <td>Unit</td>
        {if ($egyed.kedvezmenycount > 0)}
            <td class="textalignright">Original u.price</td>
            <td class="textalignright">Discount %</td>
        {/if}
        <td class="textalignright">Unit price</td>
        <td class="textalignright">Net value</td>
        <td class="textalignright">VAT</td>
        <td class="textalignright">VAT value</td>
        <td class="textalignright">Gross value</td>
    </tr>
{/block}

{block "itemrows"}
    <tr class="tetelsor">
        <td>{$teteldb + 1}</td>
        <td colspan="{if ($egyed.kedvezmenycount > 0)}10{else}8{/if}" width="{$w.nevsor}" class="bold">{$tetel.cikkszam} {$tetel.termeknev} {foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}{if ($tetel.termekegyediazonosito|default)}({$tetel.termekegyediazonosito}) {/if}({$tetel.vtszszam})</td>
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

{block "summary"}{include "biz_paged_summary_eng.tpl"}{/block}
