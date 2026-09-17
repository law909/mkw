<html>
<head>
    <style>
        {* az alsó margó a háttér-PDF láblécének helye; a felsőt a futó sávfej magasságából az mkwmpdf számolja *}
        @page {
            margin-bottom: 30mm;
            margin-left: 12mm;
            margin-right: 12mm;
            {if ($csoportok && $savok)}
            odd-header-name: html_savfej;
            even-header-name: html_savfej;
            {/if}
        }
        body {
            font-family: dejavusans;
            font-size: 7.5pt;
        }
        .szoveg {
            margin-bottom: 3mm;
        }
        .szoveg.fej {
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }
        {* összevont szegélynél a sorokon átívelő cellák mPDF figyelmeztetést adnak, ami fejlesztői módban a PDF elé kerül *}
        table.arlista {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        table.arlista td {
            border: 0.1mm solid #000;
            padding: 0.5mm 1mm;
        }
        {* csoportonként külön tábla: a rögzített szélességek tartják egy vonalban az oszlopokat *}
        table.arlista td.cikkszam {
            width: 28mm;
        }
        table.arlista td.ar {
            width: 21mm;
            text-align: right;
            white-space: nowrap;
        }
        table.arlista td.ures {
            border: none;
        }
        table.arlista td.fej {
            text-align: center;
            font-weight: bold;
        }
        table.arlista td.kategoria {
            border: none;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            padding-top: 3mm;
        }
    </style>
</head>
<body>
{if ($csoportok && $savok)}
    <htmlpageheader name="savfej">
        <table class="arlista">
            <tr>
                <td class="ures" colspan="3"></td>
                <td class="fej" colspan="{count($savok)}">{$feliratok.savok}</td>
            </tr>
            <tr>
                <td class="ures" colspan="3"></td>
                {foreach $savok as $_sav}
                    <td class="fej ar">{$_sav.nev}</td>
                {/foreach}
            </tr>
        </table>
    </htmlpageheader>
{/if}
{if ($fejszoveg)}
    <div class="szoveg fej">{$fejszoveg|escape|nl2br}</div>
{/if}
{if ($csoportok)}
    {foreach $csoportok as $_csoport}
        {* ha a csoport nem fér a lap maradékára, új lapon kezdődik; az egy lapnál hosszabb csoport a fejével folytatódik *}
        <table class="arlista" style="page-break-inside: avoid">
            <thead>
            <tr>
                <td class="kategoria" colspan="{3 + count($savok)}">{$_csoport.nev}</td>
            </tr>
            <tr>
                <td class="ures" colspan="2"></td>
                <td class="fej ar">{$arsavnev|escape}</td>
                {foreach $savok as $_sav}
                    <td class="fej ar">{if (isset($_csoport.kedvezmenyek[$_sav.id]))}-{str_replace('.', ',', $_csoport.kedvezmenyek[$_sav.id])}%{/if}</td>
                {/foreach}
            </tr>
            </thead>
            <tbody>
            {foreach $_csoport.termekek as $_termek}
                <tr>
                    <td class="cikkszam">{$_termek.cikkszam}</td>
                    <td>{$_termek.nev}</td>
                    <td class="ar">{number_format($_termek.ar, $tizedes, ',', '.')} {$penznem}</td>
                    {foreach $_termek.savarak as $_savar}
                        <td class="ar">{if ($_savar !== null)}{number_format($_savar, $tizedes, ',', '.')} {$penznem}{/if}</td>
                    {/foreach}
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/foreach}
{else}
    <p>{$feliratok.ures}</p>
{/if}
{if ($labszoveg)}
    <div class="szoveg" style="margin-top: 3mm">{$labszoveg|escape|nl2br}</div>
{/if}
</body>
</html>
