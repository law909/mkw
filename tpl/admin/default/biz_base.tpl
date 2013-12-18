<!DOCTYPE html>
<html>
	<head>
        <style type="text/css">
            body { font-size: 12px;}
            p { margin: 0;}
            th { text-align: left;}
            .textalignright { text-align: right;}
            .container { width: 90%;margin: 20px auto;}
            .row { width: 100%;}
            .row-inner { padding: 5px;}
            .bold { font-weight: bold;}
            .border { border: solid 1px black;}
            .nev { font-size: 18px;}
            .teto { height: 900px;}
            .pull-left { float: left;}
            .headbox { width: 50%;height: 120px;}
            .headboxborder { height: 100%;}
            .headboxinner { padding: 5px;}
            .bizszam, .biznev { font-size: 20px;font-weight: bold;}
            .bizszam { width: 50%; float: left;}
            .biznev { width: 50%; float: left;}
            .head2label { width: 20%;}
            .teteltable { width: 100%;}
            .afaosszesitotable { width: 30%;}
            @media print {
                .container { width: 100%; margin: 0;}
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