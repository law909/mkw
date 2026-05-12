<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        p {
            margin: 0;
        }

        th {
            text-align: left;
        }

        .topline {
            border-top: solid 1px black;
        }

        .topbottommargin {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .topmargin {
            margin-top: 5px;
        }

        .topmargin10 {
            margin-top: 10px;
        }

        .bottommargin {
            margin-bottom: 5px;
        }

        .toppadding10 {
            padding-top: 10px;
        }

        .dashedline {
            border-bottom: dashed 1px black;
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .clear {
            clear: both;
        }

        .textalignright {
            text-align: right;
        }

        .textaligncenter {
            text-align: center;
        }

        .topalign {
            vertical-align: top;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .fullwidth {
            width: 100%;
        }

        .halfwidth {
            width: 50%;
        }

        .row-inner {
            padding: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .border {
            border: solid 1px black;
        }

        .teto {
            height: 820px;
        }

        .pull-left {
            float: left;
        }

        .pull-right {
            float: right;
        }

        .headboxinner {
            padding: 5px;
        }

        body {
            font-size: 10px;
        }

        .nev {
            font-size: 10px;
        }

        .biznev {
            font-size: 16px;
            font-weight: bold;
        }

        .tetelsor {
            font-size: 10px;
        }

        .keszult {
            font-size: 8px;
        }

        .osszesen {
            font-size: 12px;
        }

        @media all {
            .page-break {
                display: none;
            }
        }

        @media print, screen {
            .container {
                width: 100%;
                margin: 0;
            }

            .page-break {
                display: block;
                page-break-before: always;
            }
        }

        @page {
            margin: 0.4in 0.39in 0.57in 0.4in;
        }
    </style>
    {block "inhead"}
    {/block}
    <title>{$egyed.id|default} - {$egyed.szamlanev} - {if ($egyed.nyomtatva)}másolat{else}eredeti{/if}</title>
</head>
<body>
<div class="container">
    {block "body"}
    {/block}
    <div class="clear topmargin">
        {if ($egyed.printrendelet)}
            <p class="keszult">Jelen számla megfelel a 47/2007 (XII.29) PM rendeletben előírtaknak.</p>
        {/if}
        <p class="keszult">Készült a(z) {if ($egyed.programnev)}{$egyed.programnev} programmal{else}MKW Webshop számlázó moduljával{/if}.</p>
    </div>
</div>
</body>
</html>