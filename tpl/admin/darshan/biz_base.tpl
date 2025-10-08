<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        * {
            font-family: DejaVu Serif, sans-serif;
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
            height: 860px;
        }

        .pull-left {
            float: left;
        }

        .pull-right {
            float: right;
        }

        .headbox {
            width: 50%;
            height: 140px;
        }

        .padding5 {
            padding: 5px;
        }

        .width25percent {
            width: 25%;
        }

        .width40percent {
            width: 40%;
        }

        .height100percent {
            height: 100%;
        }

        body {
            font-size: 10px;
        }

        .nev {
            font-size: 14px;
            font-weight: bold;
        }

        .biznev {
            font-size: 18px;
            font-weight: bold;
        }

        .tetelsor {
            font-size: 10px;
        }

        .keszult {
            font-size: 8px;
        }

        .osszesen {
            font-size: 10px;
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
    <title>{$egyed.id|default} - {$egyed.szamlanev}</title>
</head>
<body>
<div class="container">
    {block "body"}
    {/block}
    <div class="clear topmargin">
        <p class="keszult textaligncenter">Készült a(z) {if ($egyed.programnev)}{$egyed.programnev}{else}Billy számlázó{/if} programmal.</p>
    </div>
</div>
</body>
</html>