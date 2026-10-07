<html>
<head>
    <style>
        @page {
            odd-footer-name: html_lablec;
            even-footer-name: html_lablec;
        }
        body {
            font-family: dejavusans;
            font-size: 8pt;
        }
        h1 {
            font-size: 13pt;
            margin: 0 0 1mm 0;
        }
        h2 {
            font-size: 10pt;
            margin: 5mm 0 1.5mm 0;
        }
        .fej {
            color: #555;
        }
        .figyelmeztetes {
            color: #c00;
            font-weight: bold;
            margin-top: 2mm;
        }
        .lablec {
            font-size: 7pt;
            color: #555;
        }
        table.lista {
            border-collapse: separate;
            border-spacing: 0;
        }
        table.lista th {
            border-bottom: 0.4mm solid #8a7f6a;
            text-align: left;
            padding: 0.6mm 2mm;
        }
        table.lista td {
            padding: 0.5mm 2mm;
            border-bottom: 0.1mm solid #ddd;
        }
        table.lista tfoot td {
            font-weight: bold;
            border-top: 0.6mm double #8a7f6a;
            border-bottom: none;
        }
        table.lista .jobb {
            text-align: right;
        }
    </style>
</head>
<body>
<htmlpagefooter name="lablec">
    <table width="100%" class="lablec">
        <tr>
            <td>Csomagolási lista / Packing list · {$egyed.id|escape}</td>
            <td align="right">{literal}{PAGENO} / {nbpg}{/literal}</td>
        </tr>
    </table>
</htmlpagefooter>

<h1>Csomagolási lista / Packing list</h1>
<div class="fej">{$egyed.id|escape} &nbsp; {$egyed.partnernev|escape} &nbsp; {$egyed.keltstr}</div>
{if ($hianyzik > 0)}
    <div class="figyelmeztetes">Figyelem: {$hianyzik} darab nincs dobozba téve.</div>
{/if}

<h2>Dobozméretek / Box sizes</h2>
<table class="lista">
    <thead>
    <tr>
        <th>Méret / Size</th>
        <th class="jobb">Darab / Pcs</th>
    </tr>
    </thead>
    <tbody>
    {foreach $meretek as $_meret => $_db}
        <tr>
            <td>{$_meret}</td>
            <td class="jobb">{$_db}</td>
        </tr>
    {/foreach}
    </tbody>
</table>

<h2>Dobozok / Boxes</h2>
<table class="lista">
    <thead>
    <tr>
        <th>Doboz / Box</th>
        <th>Méret / Size</th>
        <th class="jobb">Nettó kg / Net kg</th>
        <th class="jobb">Bruttó kg / Gross kg</th>
        <th class="jobb">Térfogat m³ / Volume m³</th>
    </tr>
    </thead>
    <tbody>
    {foreach $dobozlista as $_doboz}
        <tr>
            <td>{$_doboz.dobozszam}</td>
            <td>{$_doboz.meret}</td>
            <td class="jobb">{number_format($_doboz.nettosuly, 2, ',', ' ')}</td>
            <td class="jobb">{number_format($_doboz.bruttosuly, 2, ',', ' ')}</td>
            <td class="jobb">{number_format($_doboz.terfogat, 3, ',', ' ')}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td>Összesen / Total</td>
        <td>{count($dobozlista)} doboz / boxes</td>
        <td class="jobb">{number_format($osszesen.nettosuly, 2, ',', ' ')}</td>
        <td class="jobb">{number_format($osszesen.bruttosuly, 2, ',', ' ')}</td>
        <td class="jobb">{number_format($osszesen.terfogat, 3, ',', ' ')}</td>
    </tr>
    </tfoot>
</table>

<h2>Dobozok tartalma / Box contents</h2>
<table class="lista" width="100%">
    <thead>
    <tr>
        <th>Doboz / Box</th>
        <th>Cikkszám / Item no.</th>
        <th>Termék / Product</th>
        <th>Szín / Colour</th>
        <th>Méret / Size</th>
        <th class="jobb">Darab / Pcs</th>
    </tr>
    </thead>
    <tbody>
    {foreach $tartalom as $_sor}
        <tr>
            <td>{$_sor.dobozszam}</td>
            <td>{$_sor.cikkszam|escape}</td>
            <td>{$_sor.nev|escape}</td>
            <td>{$_sor.szin|escape}</td>
            <td>{$_sor.meret|escape}</td>
            <td class="jobb">{$_sor.mennyiseg*1}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
</body>
</html>
