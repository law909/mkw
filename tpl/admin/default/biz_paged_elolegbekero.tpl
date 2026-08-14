{extends "biz_paged_megrendeles.tpl"}

{block "title"}Díjbekérő{/block}

{* a díjbekérőn a számláéval azonos oszlopfeliratok állnak, nem a megrendeléséi *}
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
