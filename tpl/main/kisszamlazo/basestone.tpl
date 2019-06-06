<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
        {block "css"}{/block}
        {block "script"}{/block}
    </head>
	<body {block "bodyclass"}{/block}>
        {block "stonebody"}
        {/block}
	</body>
</html>