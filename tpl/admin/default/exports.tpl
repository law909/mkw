{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/default/exportsform.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
<div id="mattkarb-header">
	<h3>{at('Termék exportok')}</h3>
</div>
<form id="mattkarb-form" action="" method="post">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#DefaTab">{at('Exportok')}</a></li>
		</ul>
		<div id="DefaTab" class="mattkarb-page" data-visible="visible">
            <a href="/admin/export/grando" class="js-grandoexport">Grando export</a>
		</div>
	</div>
	<div class="admin-form-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
</div>
{/block}