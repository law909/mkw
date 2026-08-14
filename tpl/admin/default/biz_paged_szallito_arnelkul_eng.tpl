{extends "biz_paged_szallito_arnelkul.tpl"}

{block "title"}Szállítólevél / Delivery bill{/block}

{block "headboxes"}{include "biz_paged_headboxki_eng.tpl"}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="33%">Kelt</td>
            <td width="33%">Teljesítés</td>
            <td width="34%">Szállítólevél száma</td>
        </tr>
        <tr class="bold textaligncenter">
            <td>Issue</td>
            <td>Fulfillment</td>
            <td>Delivery bill #</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
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
    </tr>
    <tr class="bold">
        <td></td>
        <td>Product</td>
        <td class="textalignright">Quantity</td>
        <td>Unit</td>
    </tr>
{/block}

{block "summary"}
    <div>Összes mennyiség / Total quantity: {bizformat($summennyiseg)}</div>
{/block}
