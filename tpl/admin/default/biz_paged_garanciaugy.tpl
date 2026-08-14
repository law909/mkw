{extends "biz_paged_bevet.tpl"}

{* a garanciaügyön a fizmod lokalizálatlan neve szerepel *}
{block "datesrow"}
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr class="bold textaligncenter">
            <td width="12%">Kelt</td>
            <td width="14%">Raktár</td>
            <td width="12%">Teljesítés</td>
            <td width="12%">Fiz.határidő</td>
            <td width="14%">Fizetési mód</td>
            <td width="9%">Pénznem</td>
            <td width="12%">Eredeti biz.szám</td>
            <td width="15%">Biz. száma</td>
        </tr>
        <tr class="textaligncenter">
            <td>{$egyed.keltstr|default:"&nbsp;"}</td>
            <td>{$egyed.raktarnev|default:"&nbsp;"}</td>
            <td>{$egyed.teljesitesstr|default:"&nbsp;"}</td>
            <td>{$egyed.esedekessegstr|default:"&nbsp;"}</td>
            <td>{$egyed.fizmodnev|default:"&nbsp;"}</td>
            <td>{$egyed.valutanemnev|default:"&nbsp;"}</td>
            <td>{$egyed.erbizonylatszam|default:"&nbsp;"}</td>
            <td>{$egyed.id}</td>
        </tr>
    </table>
{/block}
