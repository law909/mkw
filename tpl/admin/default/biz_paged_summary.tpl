{*
    Összesítő a lapozott sablonokhoz. A biz_paged_base.tpl a tételtáblázat utolsó SORÁBA teszi,
    ezért egyben marad és a legutolsó tétel után jön – az mPDF egy <tr>-t nem vág ketté.
    page-break-inside: avoid szándékosan NINCS rajta: azzal az mPDF üres oldalt hagyott maga után.

    A régi biz_summary.tpl-hez képest a szegélyt vivő üres térköz-sor (colspan="5" egy négyoszlopos
    táblában) helyett az "Összesen" sor cellái viszik a felső vonalat: az mPDF az üres cellákat
    összevonhatja, és a vonal eltűnne.
*}
<table class="osszesito fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tr class="osszesen bold">
        <td width="50%">Összesen</td>
        <td width="50%" class="textalignright">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
    </tr>
    <tr>
        <td class="topalign" style="padding-top: 5px;">Összes mennyiség: {bizformat($summennyiseg)}</td>
        <td class="topalign" style="padding-top: 10px;">
            <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
                <tr class="bold">
                    <td width="28%"></td>
                    <td width="24%" class="textalignright">Nettó</td>
                    <td width="24%" class="textalignright">ÁFA</td>
                    <td width="24%" class="textalignright">Bruttó</td>
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
                    <td class="topline">Összesen</td>
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
                Fizetendő végösszeg: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
            </td>
        </tr>
    {/if}
</table>
