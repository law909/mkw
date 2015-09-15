<!DOCTYPE html>
<html>
	<head>
        <style type="text/css">
            p { margin: 0;}
            th { text-align: left;}
            .topline { border-top: solid 1px black;}
            .topbottommargin { margin-bottom: 5px; margin-top: 5px;}
            .topmargin { margin-top: 5px;}
            .topmargin10 { margin-top: 10px;}
            .bottommargin { margin-bottom: 5px;}
            .toppadding10 { padding-top: 10px;}
            .dashedline { border-bottom: dashed 1px black; margin-bottom: 5px; margin-top: 5px;}
            .clear { clear: both;}
            .textalignright { text-align: right;}
            .textaligncenter { text-align: center;}
            .container { width: 90%;margin: 20px auto;}
            .fullwidth { width: 100%;}
            .halfwidth { width: 50%;}
            .row-inner { padding: 5px;}
            .bold { font-weight: bold;}
            .border { border: solid 1px black;}
            .teto { height: 820px;}
            .pull-left { float: left;}
            .pull-right { float: right;}
            .headboxinner { padding: 5px;}

            body { font-size: 11px; font-family: Arial;}
            .nev { font-size: 11px;}
            .biznev { font-size: 17px; font-weight: bold;}
            .tetelsor { font-size: 12px;}
            .fizetendokiirva { font-size: 8px;}
            .keszult { font-size: 6px;}
            .osszesen { font-size: 13px;}

            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .container { width: 100%; margin: 0;}
            	.page-break	{ display: block; page-break-before: always; }
            }

            @page {
                margin: 0.4in 0.39in 0.57in 0.4in;
            }
        </style>
		{block "inhead"}
		{/block}
		<title>{$egyed.id|default} - {t('MKW Admin')}</title>
	</head>
	<body>
        <div class="container">
        {block "body"}
        {/block}
        </div>
	</body>
</html>