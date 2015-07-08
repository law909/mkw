{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/superzone/importsform.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
<div id="mattkarb-header">
	<h3>{t('Termék importok')}</h3>
</div>
<form id="mattkarb-form" action="" method="post">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#DefaTab">{t('Importok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Exportok')}" data-refcontrol="#DefaTab"></div>
		{/if}
		<div id="DefaTab" class="mattkarb-page" data-visible="visible">
            <div>
                <span id="TermekKategoria1" class="js-termekfabutton" data-text="{t('válasszon')}" data-name="termekfa1" data-value="">Ebbe a kategóriába kerüljenek a termékek</span>
            </div>
	<div class="matt-hseparator"></div>
            <div>
                <label>Képek mappája:</label><input name="path" value="{$path}">
            </div>
	<div class="matt-hseparator"></div>
            <div>
                <label for="GyartoEdit">Gyártó:</label>
                <select id="GyartoEdit" name="gyarto">
					<option value="">{t('válasszon')}</option>
					{foreach $gyartolist as $_gyarto}
						<option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
					{/foreach}
				</select>
            </div>
	<div class="matt-hseparator"></div>
            <div>
                <label>Importálandó fájl:</label>
                <input name="toimport" type="file">
            </div>
	<div class="matt-hseparator"></div>
            <div>
            <a href="/admin/import/szatalakit" class="js-szatalakit">Terméktörzs átalakítás</a>
            </div>
		</div>
	</div>
	<div class="admin-form-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>
</div>
{/block}