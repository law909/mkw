{extends "biz_paged_koltsegszamla.tpl"}

{block "headboxes"}{include "biz_paged_headboxki.tpl"}{/block}

{* leltárbizonylat: raktár, kelt, teljesítés – nincs fizetési mód és esedékesség *}
{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="30%">Raktár</td>
            <td width="22%">Kelt</td>
            <td width="22%">Teljesítés</td>
            <td width="26%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.raktarnev|default:"&nbsp;"}</td>
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}

{block "thanks"}{/block}
