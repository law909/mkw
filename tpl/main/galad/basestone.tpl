<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
        <link href="/themes/main/galad/css/bootstrap.min.css" rel="stylesheet">
        <link href="/themes/main/galad/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="/themes/main/galad/magnific-popup.css" rel="stylesheet">
        <link href="/themes/main/galad/style.css" rel="stylesheet">
        {block "css"}{/block}
        <script src="/js/main/galad/jquery-1.11.3.min.js"></script>
        <script src="/js/main/galad/moment.min.js"></script>
        <script src="/js/main/galad/accounting.min.js"></script>
        <script src="/js/main/galad/bootstrap.min.js"></script>
        <script src="/js/main/galad/bootstrap-datetimepicker.min.js"></script>
        <script src="/js/main/galad/jquery.debounce.min.js"></script>
		<script src="/js/main/galad/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/galad/superzmsg.js"></script>
		<script src="/js/main/galad/superz.js"></script>
		<script src="/js/main/galad/cart.js"></script>
        {block "script"}{/block}
		<script src="/js/main/galad/superzone.js"></script>
    </head>
	<body {block "bodyclass"}{/block}>
        {block "stonebody"}
        {/block}
		<div id="dialogcenter" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer"></div>
                </div>
            </div>
		</div>
		<div id="messagecenter" class="mfp-hide"></div>
	</body>
</html>