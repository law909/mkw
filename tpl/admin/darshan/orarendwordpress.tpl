<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/admin/darshan/iframeResizer.contentWindow.min.js"></script>
    <style>
        body {
            font-family: 'Arial',Helvetica,Arial,Lucida,sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }
        a {
            color: #b63535;
        }
        a:hover {
            text-decoration: none;
        }
        .dtt {
            text-align: left;
        }
        .dttelmarad .dttoranev, .dttelmarad .dtttanar {
            color: white;
        }
        .dttnap {
            clear: both;
        }
        .dttnapnev {
            text-align: center;
            width: 100%;
            border-radius: 3px;
            padding: 10px 0;
            margin-bottom: 2px;
            color: white;
            font-weight: bold;
            font-variant: small-caps;
            font-size: 20px;
            background-color: #ded4d4;
        }
        .dttora {
            float: left;
            margin-bottom: 2px;
            background-color: #fff9f7;
            width: 100%;
        }
        .dttkisidopont, .dttnagyidopont {
            text-align: center;
            float: left;
            padding: 10px 0;
            margin: 0 1%;
            width: 10%;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }
        .dttnagyidopont {
            background-color: #669999;
        }
        .dttkisidopont {
            background-color: #A5C663;
        }
        .dttoranev {
            float: left;
            padding: 10px 0;
            margin-right: 1%;
            width: 40%;
            text-align: center;
        }
        .dtttanar {
            float: left;
            padding: 10px 0;
            margin-right: 1%;
            width: 36%;
            text-align: center;
        }
        .dttnagyterem, .dttkisterem {
            text-align: center;
            float: left;
            padding: 10px 0;
            width: 9%;
        }
        /* Responsive Styles Smartphone Portrait */
        @media all and (max-width: 479px) {
            .dttkisidopont, .dttnagyidopont {
                margin: 0 1%;
                padding: 2px;
                width: 14%;
            }
            .dttoranev {
                width: 36%;
                font-size: 12px;
                font-weight: bold;
            }
            .dtttanar {
                width: 26%;
                font-size: 12px;
            }
            .dttnagyterem, .dttkisterem {
                width: 19%;
                font-size: 12px;
            }
            .dttrow {
                width: 94%;
            }
        }
        /* Responsive Styles Smartphone Landscape */
        @media all and (max-width: 980px) {
            .dttkisidopont, .dttnagyidopont {
                margin: 0 1%;
                padding: 2px;
                width: 14%;
            }
            .dttoranev {
                width: 36%;
                font-size: 12px;
                font-weight: bold;
            }
            .dtttanar {
                width: 26%;
                font-size: 12px;
            }
            .dttnagyterem, .dttkisterem {
                width: 19%;
                font-size: 12px;
            }
            .dttrow {
                width: 94%;
            }
        }
    </style>

</head>

<body>
<div class="dtt">
    {foreach $orarend as $nap}
    <div class="dttnap">
        <div class="dttnapnev">{$nap['napnev']}</div>
        {foreach $nap['orak'] as $ora}
        <div class="dttora">
            <div class="dtt{$ora['class']}idopont">{$ora['kezdet']}-{$ora['veg']}</div>
            <div class="dttoranev"><a href="{$ora['oraurl']}">{$ora['oranev']}</a></div>
            <div class="dtttanar"><a href="{$ora['tanarurl']}">{$ora['tanar']}</a></div>
            <div class="dtt{$ora['class']}terem">{$ora['terem']}</div>
        </div>
        {/foreach}
    </div>
    {/foreach}
</div>
</body>
</html>