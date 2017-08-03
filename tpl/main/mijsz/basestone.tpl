<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		<!--link type="text/css" rel="stylesheet" href="/themes/main/mijsz/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mijsz/bootstrap-responsive.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mijsz/jquery.slider.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mijsz/magnific-popup.css"-->
        <link rel="stylesheet" href="/themes/main/mijsz/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mijsz/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/mijsz.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/style.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/magnify.css">
		{block "css"}{/block}
        {if ($dev)}
        <script src="/js/main/mijsz/jquery-1.11.1.min.js"></script>

		<script src="/js/main/mijsz/jquery-migrate-1.2.1.js"></script>

        <script src="/js/main/mijsz/mijszerrorlog.js"></script>

		<script src="/js/main/mijsz/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/mijsz/jquery.slider.min.js"></script>
        <script src="/js/main/mijsz/jquery.royalslider.min.js"></script>
        <script src="/js/main/mijsz/jquery.debounce.min.js"></script>
        <script src="/js/main/mijsz/jquery.magnify.js"></script>
        <script src="/js/main/mijsz/jquery.magnify-mobile.js"></script>
        <script src="/js/main/mijsz/bootstrap-transition.js"></script>
		<script src="/js/main/mijsz/bootstrap-modal.js"></script>
		<script src="/js/main/mijsz/bootstrap-tab.js"></script>
		<script src="/js/main/mijsz/bootstrap-typeahead.js"></script>
		<script src="/js/main/mijsz/bootstrap-tooltip.js"></script>
		<script src="/js/main/mijsz/h5f.js"></script>
		<script src="/js/main/mijsz/matt-accordion.js"></script>
        {else}
        <script src="/js/main/mijsz/mijszbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script src="/js/main/mijsz/mijszmsg.js"></script>
		<script src="/js/main/mijsz/iyengar.js"></script>
		<script src="/js/main/mijsz/checks.js"></script>
		<script src="/js/main/mijsz/fiok.js"></script>
		<script src="/js/main/mijsz/mijsz.js"></script>
        {else}
        <script src="/js/main/mijsz/mijszapp.js?v={$jsversion}"></script>
        {/if}
	</head>
	<body class="bgimg">
        {block "body"}
        {/block}
		{block "stonebody"}
		{/block}
		<div id="dialogcenter" class="modal hide fade" tabindex="-1" role="dialog">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
		  </div>
		  <div class="modal-body"></div>
		  <div class="modal-footer"></div>
		</div>
		<div id="messagecenter" class="mfp-hide"></div>
	</body>
</html>