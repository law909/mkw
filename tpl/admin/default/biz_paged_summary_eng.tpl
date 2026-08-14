{* a biz_paged_summary.tpl kétnyelvű párja, a fizetési részletekkel *}
<table class="osszesito fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tr class="osszesen bold">
        <td width="50%">Összesen / Total</td>
        <td width="50%" class="textalignright">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
    </tr>
    <tr>
        <td class="topalign" style="padding-top: 5px;">
            Összes mennyiség / Total quantity: {bizformat($summennyiseg)}
            {if (!$nemkellfizetesireszlet)}
                {if ($egyed.esedekesseg1str || $egyed.esedekesseg2str || $egyed.esedekesseg3str)}
                    <br /><br />PAYMENT:
                    {if ($egyed.esedekesseg1str)}<br />{$egyed.esedekesseg1str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo1)} {$egyed.valutanemnev}{/if}
                    {if ($egyed.esedekesseg2str)}<br />{$egyed.esedekesseg2str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo2)} {$egyed.valutanemnev}{/if}
                    {if ($egyed.esedekesseg3str)}<br />{$egyed.esedekesseg3str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo3)} {$egyed.valutanemnev}{/if}
                {/if}
            {/if}
        </td>
        <td class="topalign" style="padding-top: 10px;">
            <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
                <tr class="bold">
                    <td width="28%"></td>
                    <td width="24%" class="textalignright">Nettó / Net</td>
                    <td width="24%" class="textalignright">ÁFA / VAT</td>
                    <td width="24%" class="textalignright">Bruttó / Gross</td>
                </tr>
                {foreach $afaosszesito as $a}
                    <tr>
                        <td>{$a.caption}</td>
                        <td class="textalignright">{bizformat($a.netto)}</td>
                        <td class="textalignright">{bizformat($a.afa)}</td>
                        <td class="textalignright">{bizformat($a.brutto)}</td>
                    </tr>
                {/foreach}
                <tr class="bold">
                    <td class="topline">Összesen / Total</td>
                    <td class="topline textalignright">{bizformat($egyed.netto)}</td>
                    <td class="topline textalignright">{bizformat($egyed.afa)}</td>
                    <td class="topline textalignright">{bizformat($egyed.brutto)}</td>
                </tr>
            </table>
        </td>
    </tr>
    {if (!$nemkellfizetendo)}
        <tr>
            <td colspan="2" class="textalignright osszesen bold" style="padding-top: 10px;">
                azaz {$egyed.fizetendokiirva} {$egyed.valutanemnev}
            </td>
        </tr>
        <tr>
            <td colspan="2" class="textalignright osszesen bold">
                Fizetendő végösszeg / Total value to pay: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
            </td>
        </tr>
    {/if}
</table>
