{extends "biz_paged_szallito.tpl"}

{block "copymark"}{if ($egyed.nyomtatva)}Másolat{else}Eredeti példány{/if}. {/block}

{* selejtezésnél nincs fizetési határidő és fizetési mód *}
{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="25%">Kelt</td>
            <td width="25%">Teljesítés</td>
            <td width="20%">Pénznem</td>
            <td width="30%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
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

{block "summary"}{include "biz_paged_summary.tpl" nemkellfizetendo=true}{/block}
