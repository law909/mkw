{*
    Lapozott (mPDF) bizonylatsablonok közös alapja.

    Szerkezet és a miértje:
      - A fejléc <htmlpageheader>, a lábléc <htmlpagefooter>: az mPDF mindkettőt minden oldalra
        kiteszi. Az OSZLOPFEJLÉC is ide tartozik, nem <thead>-be: az mPDF a <thead>-et nem ismétli,
        ha a cellájában beágyazott táblázat van.
      - A tételek egyetlen, egyoszlopos táblázat sorai. Egy tétel = egy <tr>, benne beágyazott
        táblázat viszi a tétel két sorát. Az mPDF egy <tr>-t sosem vág ketté, így a tétel sem törik.
      - Az összesítő ugyanennek a táblázatnak az utolsó sora, ezért ugyanígy atomi, és mindig a
        legutolsó tétel után jön. page-break-inside: avoid nem kell (és nem is szabad: üres oldalt
        hagyott maga után).
      - A lapméret és a margók a mkw\mkwmpdf konstruktorából jönnek, itt csak a fejléc/lábléc
        bekötése van. A @page-be írt "size: A4 portrait" az mPDF-ben félremegy.

    A gyerek sablon a {block}-okat tölti ki; kötelező a columnheaders és az itemrows.

    Az oszlopszélességek a $w tömbben, MILLIMÉTERBEN, összegük 190 mm = A4 szélesség mínusz a
    mkw\mkwmpdf bal-jobb margója (10-10 mm). Százalékkal nem működik: minden tétel külön beágyazott
    táblázat, és az mPDF a százalékot csak ajánlásnak veszi – a rövid nevű tétel oszlopai
    elcsúsznak a többihez és az oszlopfejléchez képest. Ha a margó változik, ezeket is át kell írni.
*}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        body { font-family: dejavusans; font-size: 8pt; }
        p { margin: 0; }
        td { vertical-align: top; }
        .topline { border-top: solid 1px black; }
        .topbottommargin { margin-bottom: 5px; margin-top: 5px; }
        .dashedline { border-bottom: dashed 1px black; }
        .textalignright { text-align: right; }
        .textaligncenter { text-align: center; }
        .topalign { vertical-align: top; }
        .bold { font-weight: bold; }
        /* az mPDF a <tr>-re tett stílust nem örökíti a cellákra – enélkül a fejlécsorok nem lesznek félkövérek */
        tr.bold td { font-weight: bold; }
        .fullwidth { width: 100%; }
        .nev { font-size: 8pt; }
        .biznev { font-size: 13pt; font-weight: bold; }
        .tetelsor { font-size: 8pt; }
        .keszult { font-size: 6pt; }
        .osszesen { font-size: 10pt; }
        /* a padding-bottom választja el a tételeket egymástól – minden sablon-családban,
           mert minden tétel ebben a cellában ül */
        .tetelcell { padding: 0 0 1.5mm 0; border: 0; }
        /* a felső szegély zárja le a tétellistát: ez a vonal az utolsó tétel után fut */
        .osszesitocell { border-top: solid 1px black; border-left: 0; border-right: 0; border-bottom: 0; padding-top: 3mm; }
        /* abszolút szélesség, nem 100%: a beágyazott tételtáblák így pontosan ugyanazt a
           190 mm-t kapják, mint az oldalfejléc oszlopfejléc-táblája */
        .tetelgrid { width: 190mm; border-collapse: collapse; }
        /* oszlopköz, különben a jobbra igazított érték a szomszéd oszlop feliratához ér */
        .tetelgrid td, .osszesitogrid td { padding-right: 3px; }
        @page {
            odd-header-name: html_bizfej;
            even-header-name: html_bizfej;
            odd-footer-name: html_bizlab;
            even-footer-name: html_bizlab;
        }
    </style>
    {block "inhead"}{/block}
    <title>{$egyed.id|default} - {$egyed.szamlanev} - {if ($egyed.nyomtatva)}másolat{else}eredeti{/if}</title>
</head>
<body>

<htmlpageheader name="bizfej">
    <table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td>
                <span class="biznev">{if $teszt}<span style="color:red">TESZT MÓD</span> {/if}{block "title"}{/block}</span>
            </td>
            <td class="textalignright">
                {block "copymark"}{/block}{literal}{PAGENO}/{nbpg}{/literal} oldal
            </td>
        </tr>
    </table>
    <div class="topline topbottommargin"></div>
    {block "headboxes"}{include "biz_paged_headboxki.tpl"}{/block}
    <div class="topline topbottommargin"></div>
    {block "datesrow"}{/block}
    <div class="topline topbottommargin"></div>
    {block "headextra"}{/block}
    {* a blokk egész <tr>-eket ad, mert a kétnyelvű változatoknál két fejlécsor kell *}
    <table class="tetelgrid fullwidth" cellspacing="0" cellpadding="0" border="0">
        {block "columnheaders"}{/block}
    </table>
</htmlpageheader>

<htmlpagefooter name="bizlab">
    <div class="keszult">
        {if ($egyed.printrendelet)}Jelen számla megfelel a 47/2007 (XII.29) PM rendeletben előírtaknak. {/if}
        Készült a(z) {if ($egyed.programnev)}{$egyed.programnev} programmal{else}MKW Webshop számlázó moduljával{/if}.
    </div>
</htmlpagefooter>

{$summennyiseg = 0}
<table class="tetelek fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tbody>
    {foreach $egyed.tetellista as $teteldb => $tetel}
        {$summennyiseg = $summennyiseg + $tetel.mennyiseg}
        <tr>
            <td class="tetelcell">
                <table class="tetelgrid fullwidth" cellspacing="0" cellpadding="0" border="0">
                    {block "itemrows"}{/block}
                </table>
            </td>
        </tr>
    {/foreach}
    <tr>
        <td class="osszesitocell">{block "summary"}{include "biz_paged_summary.tpl"}{/block}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
