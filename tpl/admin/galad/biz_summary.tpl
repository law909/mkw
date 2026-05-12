<div class="clear">
    <table class="fullwidth osszesen">
        <tbody>
        <tr>
            <td class="bold halfwidth">Összesen</td>
            <td class="bold halfwidth textalignright">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
        </tr>
        </tbody>
    </table>
    <table class="fullwidth">
        <tr>
            <td class="halfwidth topalign">
                <p>Összes mennyiség: {bizformat($summennyiseg)}</p>
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
                        <td>Összesen</td>
                        <td class="textalignright">{bizformat($egyed.netto)}</td>
                        <td class="textalignright">{bizformat($egyed.afa)}</td>
                        <td class="textalignright">{bizformat($egyed.brutto)}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    {if (!$nemkellfizetendo)}
        <div class="clear toppadding10">
            <div class="textalignright osszesen bold">
                azaz {$egyed.fizetendokiirva} {$egyed.valutanemnev}
            </div>
            <div class="textalignright osszesen bold">
                Fizetendő végösszeg: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
            </div>
        </div>
    {/if}
</div>
