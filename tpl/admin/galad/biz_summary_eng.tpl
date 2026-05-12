<div class="clear">
    <table class="fullwidth osszesen">
        <tbody>
        <tr>
            <td class="bold halfwidth">Összesen / Total</td>
            <td class="bold halfwidth textalignright">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
        </tr>
        </tbody>
    </table>
    <table class="fullwidth">
        <tr>
            <td class="halfwidth topalign">
                <p>Összes mennyiség / Total quantity: {bizformat($summennyiseg)}</p>
                {if (!$nemkellfizetesireszlet)}
                    {if ($egyed.esedekesseg1str || $egyed.esedekesseg2str || $egyed.esedekesseg3str)}
                        <p>&nbsp;</p>
                        <p>PAYMENT:</p>
                        {if ($egyed.esedekesseg1str)}
                            <p>{$egyed.esedekesseg1str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo1)} {$egyed.valutanemnev}</p>
                        {/if}
                        {if ($egyed.esedekesseg2str)}
                            <p>{$egyed.esedekesseg2str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo2)} {$egyed.valutanemnev}</p>
                        {/if}
                        {if ($egyed.esedekesseg3str)}
                            <p>{$egyed.esedekesseg3str}&nbsp;&nbsp;&nbsp;{bizformat($egyed.fizetendo3)} {$egyed.valutanemnev}</p>
                        {/if}
                    {/if}
                {/if}
            </td>
            <td class="halfwidth">
                <table class="fullwidth topmargin10">
                    <tbody>
                    <tr>
                        <td></td>
                        <td class="textalignright bold">Nettó</td>
                        <td class="textalignright bold">ÁFA</td>
                        <td class="textalignright bold">Bruttó</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="textalignright bold">Net</td>
                        <td class="textalignright bold">VAT</td>
                        <td class="textalignright bold">Gross</td>
                    </tr>
                    {foreach $afaosszesito as $a}
                        <tr>
                            <td>{$a.caption}</td>
                            <td class="textalignright">{bizformat($a.netto)}</td>
                            <td class="textalignright">{bizformat($a.afa)}</td>
                            <td class="textalignright">{bizformat($a.brutto)}</td>
                        </tr>
                    {/foreach}
                    <tr>
                        <td class="topline" colspan="5"></td>
                    </tr>
                    <tr class="bold">
                        <td>Összesen / Total</td>
                        <td class="textalignright">{bizformat($egyed.netto)}</td>
                        <td class="textalignright">{bizformat($egyed.afa)}</td>
                        <td class="textalignright">{bizformat($egyed.brutto)}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <div class="clear toppadding10">
        <div class="textalignright osszesen bold">
            azaz {$egyed.fizetendokiirva} {$egyed.valutanemnev}
        </div>
        <div class="textalignright osszesen bold">
            Fizetendő végösszeg / Total value to pay: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
        </div>
    </div>
</div>
