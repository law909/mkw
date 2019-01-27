<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    <style>
        body {
            font-family: 'Arial',Helvetica,Arial,Lucida,sans-serif;
            font-size: 10px;
            font-weight: 500;
            color: #666;
        }
        h3, h4 {
            margin: 5px 0;
        }
        .dtt {
            text-align: left;
            width: 100%;
        }
        .dttelmarad .dttoranev, .dttelmarad .dtttanar {
            color: white;
        }
        .dttnapnev {
            margin-bottom: 2px;
            font-weight: bold;
            font-variant: small-caps;
            font-size: 12px;
        }
        .dttora {
            margin-bottom: 2px;
            width: 100%;
        }
        .dttnagyidopont {
            width: 20%;
        }
        .dttoranev {
            width: 40%;
        }
        .dtttanar {
            width: 40%;
        }
        @media print {
            a {
                color: #000;
                text-decoration: none;
            }
            body {
                color: #000;
                background: #fff;
                width: 100%;
            }
        }
    </style>

</head>

<body>
<h3>Órarend - Darshan Jógastúdió</h3>
<h4>http://jogadarshan.hu</h4>
<h4>1068. Budapest, Szófia utca 15. fsz. 1.</h4>
<table class="dtt">
    {foreach $orarend as $nap}
        <th class="dttnapnev">{$nap['napnev']}</th>
        {foreach $nap['orak'] as $ora}
        <tr class="dttora">
            <td class="dtt{$ora['class']}idopont">{$ora['kezdet']}-{$ora['veg']}</td>
            <td class="dttoranev">{$ora['oranev']}</td>
            <td class="dtttanar">{$ora['tanar']}</td>
        </tr>
        {/foreach}
    {/foreach}
</table>
<h3>Órarend - Darshan Jógastúdió</h3>
<h4>http://jogadarshan.hu</h4>
<h4>1068. Budapest, Szófia utca 15. fsz. 1.</h4>
<table class="dtt">
    {foreach $orarend as $nap}
        <th class="dttnapnev">{$nap['napnev']}</th>
        {foreach $nap['orak'] as $ora}
            <tr class="dttora">
                <td class="dtt{$ora['class']}idopont">{$ora['kezdet']}-{$ora['veg']}</td>
                <td class="dttoranev">{$ora['oranev']}</td>
                <td class="dtttanar">{$ora['tanar']}</td>
            </tr>
        {/foreach}
    {/foreach}
</table>
</body>
</html>