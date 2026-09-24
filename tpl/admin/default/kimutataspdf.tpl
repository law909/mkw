<html>
<head>
    <style>
        @page {
            {if ($landscape)}sheet-size: A4-L;{/if}
            odd-footer-name: html_lablec;
            even-footer-name: html_lablec;
        }
        body {
            font-family: dejavusans;
            font-size: 8pt;
        }
        h1 {
            font-size: 13pt;
            margin: 0 0 2mm 0;
        }
        table.szurok td {
            padding: 0.3mm 3mm 0.3mm 0;
            vertical-align: top;
        }
        table.szurok td.cimke {
            color: #555;
            white-space: nowrap;
        }
        .chartnote {
            color: #555;
            font-size: 7pt;
            margin-top: 2mm;
        }
        .chart {
            margin-top: 3mm;
            text-align: center;
        }
        .lablec {
            font-size: 7pt;
            color: #555;
        }
        {* colspan cells in the body: a collapsed border makes mPDF warn, which spoils the PDF in developer mode *}
        table.arbevetel-table {
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 4mm;
            width: 100%;
        }
        table.arbevetel-table th {
            border-bottom: 0.4mm solid #8a7f6a;
            text-align: left;
            padding: 0.6mm 1.5mm;
            white-space: nowrap;
        }
        table.arbevetel-table td {
            padding: 0.5mm 1.5mm;
        }
        table.arbevetel-table th.textalignright,
        table.arbevetel-table td.textalignright {
            text-align: right;
            white-space: nowrap;
        }
        table.arbevetel-table tr.arbevetel-header td {
            font-weight: bold;
            padding-top: 2mm;
            border-bottom: 0.2mm solid #b5ab96;
        }
        table.arbevetel-table tr.arbevetel-header-0 td {
            background-color: #eeeeee;
            padding-top: 1mm;
        }
        table.arbevetel-table tr.arbevetel-subtotal td {
            font-weight: bold;
            border-top: 0.2mm solid #b5ab96;
            padding-bottom: 1.5mm;
        }
        table.arbevetel-table tfoot td {
            font-weight: bold;
            border-top: 0.6mm double #8a7f6a;
        }
        table.arbevetel-table td.arbevetel-level-1 {
            padding-left: 5mm;
        }
        table.arbevetel-table td.arbevetel-level-2 {
            padding-left: 9mm;
        }
        table.arbevetel-table td.arbevetel-level-3 {
            padding-left: 13mm;
        }
        table.arbevetel-table td.arbevetel-level-4 {
            padding-left: 17mm;
        }
        table.arbevetel-pivot caption {
            text-align: left;
            font-weight: bold;
        }
        table.arbevetel-pivot td.arbevetel-pivot-total {
            font-weight: bold;
        }
    </style>
</head>
<body>
<htmlpagefooter name="lablec">
    <table width="100%" class="lablec">
        <tr>
            <td>{$title|escape} · {$generated}</td>
            <td align="right">{literal}{PAGENO} / {nbpg}{/literal}</td>
        </tr>
    </table>
</htmlpagefooter>
<h1>{$title|escape}</h1>
<table class="szurok">
    {foreach $szurok as $_szuro}
        <tr>
            <td class="cimke">{$_szuro[0]|escape}:</td>
            <td>{$_szuro[1]|escape}</td>
        </tr>
    {/foreach}
</table>
{if ($chartnote)}
    <div class="chartnote">{$chartnote|escape}</div>
{/if}
{if ($chart)}
    <div class="chart"><img src="{$chart}" style="width: {if ($landscape)}240mm{else}185mm{/if};"></div>
{/if}
{$table}
</body>
</html>
