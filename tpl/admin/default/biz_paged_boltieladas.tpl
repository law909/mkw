{extends "biz_paged_koltsegszamla.tpl"}

{* kimenő irányú bizonylat, és nincs eredeti bizonylatszáma *}
{block "headboxes"}{include "biz_paged_headboxki.tpl"}{/block}

{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="20%">Fizetési mód</td>
            <td width="18%">Kelt</td>
            <td width="18%">Teljesítés</td>
            <td width="18%">Esedékesség</td>
            <td width="26%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.fizmodnev_locale|default:"&nbsp;"}</td>
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}
