{* a biz_paged_summary.tpl kétnyelvű párja, a fizetési részletekkel *}
<table class="osszesito" cellspacing="0" cellpadding="0" border="0" style="width: 190mm;">
    <tr>
        <td width="60mm" class="topalign">
            Összes mennyiség / Total quantity: {bizformat($summennyiseg)}
            {if (!$nemkellfizetesireszlet)}
                {if ($egyed.esedekesseg1str || $egyed.esedekesseg2str || $egyed.esedekesseg3str)}
                    <br/>
                    <br/>
                    PAYMENT:
                    {if ($egyed.esedekesseg1str)}<br/>{$egyed.esedekesseg1str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo1)} {$egyed.valutanemnev}{/if}
                    {if ($egyed.esedekesseg2str)}<br/>{$egyed.esedekesseg2str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo2)} {$egyed.valutanemnev}{/if}
                    {if ($egyed.esedekesseg3str)}<br/>{$egyed.esedekesseg3str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo3)} {$egyed.valutanemnev}{/if}
                {/if}
            {/if}
        </td>
        <td width="130mm" class="topalign">
            <table class="osszesitogrid" cellspacing="0" cellpadding="0" border="0" style="width: 130mm;">
                <tr>
                    <td colspan="2" class="osszesen bold" style="padding-bottom: 3mm;">Összesen / Total</td>
                    <td colspan="2" class="textalignright osszesen bold" style="padding-bottom: 3mm;">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
                </tr>
                {if ($egyed.valutasszamla)}
                    <tr>
                        <td colspan="4" style="padding-bottom: 2mm;">Árfolyam / Exchange rate: {bizformat($egyed.arfolyam)} HUF/{$egyed.valutanemnev}</td>
                    </tr>
                {/if}
                <tr class="bold">
                    <td width="40mm"></td>
                    <td width="30mm" class="textalignright">Nettó / Net</td>
                    <td width="30mm" class="textalignright">ÁFA / VAT</td>
                    <td width="30mm" class="textalignright">Bruttó / Gross</td>
                </tr>
                {foreach $afaosszesito as $a}
                    <tr>
                        <td>{$a.caption}{if ($egyed.valutasszamla)} {$egyed.valutanemnev}{/if}</td>
                        <td class="textalignright">{bizformat($a.netto)}</td>
                        <td class="textalignright">{bizformat($a.afa)}</td>
                        <td class="textalignright">{bizformat($a.brutto)}</td>
                    </tr>
                    {if ($egyed.valutasszamla)}
                        <tr>
                            <td>{$a.caption} HUF</td>
                            <td class="textalignright">{bizformat($a.nettohuf)}</td>
                            <td class="textalignright">{bizformat($a.afahuf)}</td>
                            <td class="textalignright">{bizformat($a.bruttohuf)}</td>
                        </tr>
                    {/if}
                {/foreach}
                <tr class="bold">
                    <td class="topline">Összesen / Total{if ($egyed.valutasszamla)} {$egyed.valutanemnev}{/if}</td>
                    <td class="topline textalignright">{bizformat($egyed.netto)}</td>
                    <td class="topline textalignright">{bizformat($egyed.afa)}</td>
                    <td class="topline textalignright">{bizformat($egyed.brutto)}</td>
                </tr>
                {if ($egyed.valutasszamla)}
                    <tr>
                        <td>Összesen / Total HUF</td>
                        <td class="textalignright">{bizformat($egyed.nettohuf)}</td>
                        <td class="textalignright">{bizformat($egyed.afahuf)}</td>
                        <td class="textalignright">{bizformat($egyed.bruttohuf)}</td>
                    </tr>
                {/if}
                {if (!$nemkellfizetendo)}
                    <tr>
                        <td colspan="4" class="textalignright osszesen bold" style="padding-top: 3mm;">
                            azaz {$egyed.fizetendokiirva} {$egyed.valutanemnev}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="textalignright osszesen bold">
                            Fizetendő végösszeg / Total value to pay: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
                        </td>
                    </tr>
                {/if}
            </table>
        </td>
    </tr>
</table>
