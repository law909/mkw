<html>
<head>
    <style>
        {* a margó a háttér-PDF fejlécének és láblécének helye *}
        @page {
            margin-top: 30mm;
            margin-bottom: 30mm;
            margin-left: 12mm;
            margin-right: 12mm;
        }
        body {
            font-family: dejavusans;
            font-size: 7.5pt;
        }
        .szoveg {
            margin-bottom: 3mm;
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
        td.ar {
            text-align: right;
            white-space: nowrap;
        }
    </style>
</head>
<body>
{if ($fejszoveg)}
    <div class="szoveg">{$fejszoveg|escape|nl2br}</div>
{/if}
{if ($csoportok)}
    <table class="arlista">
        {if ($savok)}
            <thead>
            <tr>
                <td class="ures" colspan="3"></td>
                <td class="fej" colspan="{count($savok)}">{$feliratok.savok}</td>
            </tr>
            <tr>
                <td class="ures" colspan="3"></td>
                {foreach $savok as $_sav}
                    <td class="fej">{$_sav.nev}</td>
                {/foreach}
            </tr>
            </thead>
        {/if}
        <tbody>
        {foreach $csoportok as $_csoport}
            <tr>
                <td class="kategoria" colspan="{3 + count($savok)}">{$_csoport.nev}</td>
            </tr>
            <tr>
                <td class="ures" colspan="2"></td>
                <td class="fej">{$feliratok.kiskerar}</td>
                {foreach $savok as $_sav}
                    <td class="fej">{if (isset($_csoport.kedvezmenyek[$_sav.id]))}-{str_replace('.', ',', $_csoport.kedvezmenyek[$_sav.id])}%{/if}</td>
                {/foreach}
            </tr>
            {foreach $_csoport.termekek as $_termek}
                <tr>
                    <td>{$_termek.cikkszam}</td>
                    <td>{$_termek.nev}</td>
                    <td class="ar">{number_format($_termek.ar, $tizedes, ',', '.')} {$penznem}</td>
                    {foreach $_termek.savarak as $_savar}
                        <td class="ar">{if ($_savar !== null)}{number_format($_savar, $tizedes, ',', '.')} {$penznem}{/if}</td>
                    {/foreach}
                </tr>
            {/foreach}
        {/foreach}
        </tbody>
    </table>
{else}
    <p>{$feliratok.ures}</p>
{/if}
{if ($labszoveg)}
    <div class="szoveg" style="margin-top: 3mm">{$labszoveg|escape|nl2br}</div>
{/if}
</body>
</html>
