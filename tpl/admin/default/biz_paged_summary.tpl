{*
    Összesítő a lapozott sablonokhoz. A biz_paged_base.tpl a tételtáblázat utolsó SORÁBA teszi,
    ezért egyben marad és a legutolsó tétel után jön – az mPDF egy <tr>-t nem vág ketté.
    page-break-inside: avoid szándékosan NINCS rajta: azzal az mPDF üres oldalt hagyott maga után.

    A régi biz_summary.tpl-hez képest a szegélyt vivő üres térköz-sor (colspan="5" egy négyoszlopos
    táblában) helyett az "Összesen" sor cellái viszik a felső vonalat: az mPDF az üres cellákat
    összevonhatja, és a vonal eltűnne.

    A pénzügyi rész a jobb oldali hasábban ül, balra csak a mennyiség marad. A szélességek –
    a tételtáblákéhoz hasonlóan – milliméterben: a beágyazott tábláknál az mPDF a százalékot csak
    ajánlásnak veszi, és a blokk nem érne el a jobb margóig.

    Valutás bizonylaton minden ÁFA-kulcs (és a végösszeg) két sort kap: elöl a bizonylat
    valutájában, alatta forintban. Oszlopokban nem fért volna el: a HUF-egyenérték milliárdos is
    lehet (a fejlesztői DB-ben a legnagyobb 6 505 432 059,00).
*}
<table class="osszesito" cellspacing="0" cellpadding="0" border="0" style="width: 190mm;">
    <tr>
        <td width="60mm" class="topalign">{if ($setup.theme === 'superzoneb2b')}Összes mennyiség: {bizformat($summennyiseg)}{/if}</td>
        <td width="130mm" class="topalign">
            <table class="osszesitogrid" cellspacing="0" cellpadding="0" border="0" style="width: 130mm;">
                <tr>
                    <td colspan="2" class="osszesen bold" style="padding-bottom: 3mm;">Összesen</td>
                    <td colspan="2" class="textalignright osszesen bold" style="padding-bottom: 3mm;">{bizformat($egyed.brutto)} {$egyed.valutanemnev}</td>
                </tr>
                {if ($egyed.valutasszamla)}
                    <tr>
                        <td colspan="4" style="padding-bottom: 2mm;">Árfolyam: {bizformat($egyed.arfolyam)} HUF/{$egyed.valutanemnev}</td>
                    </tr>
                {/if}
                <tr class="bold">
                    <td width="40mm"></td>
                    <td width="30mm" class="textalignright">Nettó</td>
                    <td width="30mm" class="textalignright">ÁFA</td>
                    <td width="30mm" class="textalignright">Bruttó</td>
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
                    <td class="topline">Összesen{if ($egyed.valutasszamla)} {$egyed.valutanemnev}{/if}</td>
                    <td class="topline textalignright">{bizformat($egyed.netto)}</td>
                    <td class="topline textalignright">{bizformat($egyed.afa)}</td>
                    <td class="topline textalignright">{bizformat($egyed.brutto)}</td>
                </tr>
                {if ($egyed.valutasszamla)}
                    <tr>
                        <td>Összesen HUF</td>
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
                            Fizetendő végösszeg: {bizformat($egyed.fizetendo)} {$egyed.valutanemnev}
                        </td>
                    </tr>
                {/if}
            </table>
        </td>
    </tr>
</table>
