<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="/themes/ui/{$uitheme}/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/style.css" />
		<link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/matt.css" />
        <script type="text/javascript" src="/js/admin/default/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="/js/admin/default/jquery-migrate-1.2.1.js"></script>
		<script type="text/javascript" src="/js/admin/default/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/admin/default/jquery.blockUI.js"></script>
		<script type="text/javascript" src="/js/admin/default/dmb.js"></script>
		<script type="text/javascript" src="/ckfinder/ckfinder.js"></script>
		{block "inhead"}
		{/block}
		<script type="text/javascript" src="/js/admin/default/appinit.js"></script>
		<title>{$pagetitle|default} - {t('MKW Admin')}</title>
	</head>
	<body>
	<div id="messagecenter"></div>
	<div id="dialogcenter"></div>
	<div>
    {if ($userloggedin)}
	<div id="menu" class="matt-container ui-widget ui-widget-content ui-corner-all">
		{if ($setup.gyartas==1)}
		<div class="menu-titlebar" data-caption="{t('Gyártás')}" data-refcontrol="#GyartasTab"></div>
		<div id="GyartasTab">
			<div><a class="menupont" href="/admin/nullaslista/viewlist">{t('Nullás lista')}</a></div>
		</div>
        {/if}
        <div class="textaligncenter">{$loggedinuser.name}</div>
		<div><a class="menupont" href="/admin/logout">{t('Kijelentkezés')}</a></div>
		<div class="menu-titlebar" data-caption="{t('Kereskedelem')}" data-refcontrol="#KereskedelemTab"></div>
		<div id="KereskedelemTab">
			<div><a class="menupont" href="/admin/bevetfej/viewlist">{t('Bevételezések')}</a></div>
			<div><a class="menupont" href="/admin/kivetfej/viewlist">{t('Kivétek')}</a></div>
			<div><a class="menupont" href="/admin/megrendelesfej/viewlist">{t('Megrendelések')}</a></div>
			<div><a class="menupont" href="/admin/szamlafej/viewlist">{t('Számlák')}</a></div>
			<div><a class="menupont" href="/admin/keziszamlafej/viewlist">{t('Kézi számlák')}</a></div>
			<div><a class="menupont" href="/admin/partner/viewlist">{t('Partnerek')}</a></div>
			<div><a class="menupont" href="/admin/termek/viewlist">{t('Termékek')}</a></div>
			<div><a class="menupont" href="/admin/termekfa/viewlist">{t('Termék kategóriák')}</a></div>
			<div><a class="menupont" href="/admin/termekcimke/viewlist">{t('Termékcímkék')}</a></div>
			<div><a class="menupont" href="/admin/partnercimke/viewlist">{t('Partnercímkék')}</a></div>
		</div>
		<div class="menu-titlebar" data-caption="{t('Webáruház')}" data-refcontrol="#WebTab"></div>
		<div id="WebTab">
			<div><a class="menupont" href="/admin/kosar/viewlist">{t('Kosár')}</a></div>
			<div><a class="menupont" href="/admin/korhinta/viewlist">{t('Körhinta')}</a></div>
			<div><a class="menupont" href="/admin/hir/viewlist">{t('Hírek')}</a></div>
			<div><a class="menupont" href="/admin/statlap/viewlist">{t('Statikus lapok')}</a></div>
			<div><a class="menupont" href="/admin/emailtemplate/viewlist">{t('Email sablonok')}</a></div>
			<div><a class="menupont" href="/admin/template/viewlist">{t('Sablonszerkesztő')}</a></div>
			<div><a class="menupont" href="/admin/sitemap/view">{t('Sitemap')}</a></div>
		</div>
		<div class="menu-titlebar" data-caption="{t('Kimutatások')}" data-refcontrol="#ReportTab"></div>
		<div id="ReportTab">
			<div><a class="menupont" href="/admin/fifo/view">{t('Készletérték')}</a></div>
		</div>
		<div class="menu-titlebar" data-caption="{t('HR')}" data-refcontrol="#HRTab"></div>
		<div id="HRTab" data-visible="hidden">
			<div><a class="menupont" href="/admin/dolgozo/viewlist">{t('Dolgozók')}</a></div>
			<div><a class="menupont" href="/admin/uzletkoto/viewlist">{t('Üzletkötők')}</a></div>
			<div><a class="menupont" href="/admin/jelenletiiv/viewlist">{t('Jelenléti ív')}</a></div>
		</div>
		<div class="menu-titlebar" data-caption="{t('CRM')}" data-refcontrol="#CRMTab"></div>
		<div id="CRMTab" data-visible="hidden">
			<div><a class="menupont" href="/admin/partner/viewlist">{t('Partnerek')}</a></div>
			<div><a class="menupont" href="/admin/teendo/viewlist">{t('Teendők')}</a></div>
			<div><a class="menupont" href="/admin/esemeny/viewlist">{t('Események')}</a></div>
		</div>
		<div class="menu-titlebar" data-caption="{t('Egyebek')}" data-refcontrol="#EgyebTab"></div>
		<div id="EgyebTab">
            <div><a class="menupont" href="/admin/bizonylatstatusz/viewlist">{t('Bizonylat státuszok')}</a></div>
            <div><a class="menupont" href="/admin/dolgozo/viewlist">{t('Felhasználók')}</a></div>
            <div><a class="menupont" href="/admin/egyebtorzs/view">{t('Egyéb adatok')}</a></div>
            <div><a class="menupont" href="/admin/export/view">{t('Termék exportok')}</a></div>
            <div><a class="menupont" href="/admin/import/view">{t('Importok')}</a></div>
            <div><a class="menupont" href="/admin/setup/view">{t('Beállítások')}</a></div>
            <div><a class="menupont" href="/admin/regeneratekarkod">{t('Termék kat. rendezése')}</a></div>
        </div>
		<div>
			<select id="ThemeSelect">
				{foreach $uithemes as $_uitheme}
				<option value="{$_uitheme}"{if ($uitheme==$_uitheme)} selected="selected"{/if}>{$_uitheme}</option>
				{/foreach}
			</select>
		</div>
		<div><a class="menupont" href="/admin/">{t('Főoldal')}</a></div>
	</div>
    {/if}
	<div id="kozep">
	{block "kozep"}
	{/block}
	</div>
	</div>
	</body>
</html>