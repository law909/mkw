{extends "biz_paged_szallito.tpl"}

{* a kivétnél a fizetési határidő helyén a raktár áll *}
{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="16%">Kelt</td>
            <td width="16%">Raktár</td>
            <td width="16%">Teljesítés</td>
            <td width="18%">Fizetési mód</td>
            <td width="12%">Pénznem</td>
            <td width="22%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.raktarnev|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}
