{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/default/minicrm.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
<div id="mattkarb-header">
	<h3>{at('MiniCRM')}</h3>
</div>
<form id="mattkarb-form" action="" method="post">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#DefaTab">{at('MiniCRM')}</a></li>
		</ul>
		<div id="DefaTab" class="mattkarb-page" data-visible="visible">
            <div>
                <a href="/admin/minicrm/partnerimport" class="js-partnerimport">Új partnerek importja MiniCRM-ből</a>
            </div>
            <div class="matt-hseparator"></div>
		</div>
	</div>
</form>
</div>
{/block}