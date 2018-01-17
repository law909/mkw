<!DOCTYPE html>
<html>
	<head>
        <style type="text/css">
            body { font-size: 12px;}
            p { margin: 0;}
            th { text-align: left;}
            .line { border-top: solid 1px black;}
            .textalignright { text-align: right;}
            .textaligncenter { text-align: center;}
            .container { width: 90%;margin: 20px auto;}
            .row { width: 100%;}
            .row-inner { padding: 5px;}
            .bold { font-weight: bold;}
            .border { border: solid 1px black;}
            .nev { font-size: 18px;}
            .teto { height: 820px;}
            .pull-left { float: left;}
            .pull-right { float: right;}
            .headbox { width: 50%;height: 120px;}
            .headboxborder { height: 100%;}
            .headboxinner { padding: 5px;}
            .bizszam, .biznev { font-size: 20px;font-weight: bold;}
            .bizszam { width: 50%; float: left;}
            .biznev { width: 50%; float: left;}
            .head2label { width: 25%;}
            .teteltable { width: 100%;}
            .tetelsor { font-size: 10px;}
            .afaosszesitotable { width: 40%;}
            .osszesitotable { width: 25%; float: right;}
            .fizetendokiirva { font-size: 8px;}
            .keszult { font-size: 6px;}
            .lablec { width: 100%;}
            @media print {
                .container { width: 100%; margin: 0;}
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
        </div>
	</body>
</html>