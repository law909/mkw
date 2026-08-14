{extends "biz_paged_elolegbekero.tpl"}

{block "title"}Díjbekérő / Proforma invoice{/block}

{block "headboxes"}{include "biz_paged_headboxki_eng.tpl"}{/block}

{* a díjbekérőn nincs teljesítés dátum *}
{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="18%">Kelt</td>
            <td width="18%">Fiz.határidő</td>
            <td width="20%">Fizetési mód</td>
            <td width="14%">Pénznem</td>
            <td width="30%">Díjbekérő száma</td>
        </tr>
        <tr class="bold textaligncenter">
            <td>Issue</td>
            <td>Payment due</td>
            <td>Payment method</td>
            <td>Currency</td>
            <td>Proforma invoice #</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
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

{block "summary"}
    {include "biz_paged_summary_eng.tpl" nemkellfizetesireszlet=true}
    <div style="padding-top: 5px;">
        Átutaláskor kérjük, a közleményben tüntesse fel a díjbekérő számát: {$egyed.id}<br />
        At transfer of amount, please indicate the number of voucher in comment: {$egyed.id}
    </div>
{/block}
