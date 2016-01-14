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
    <script type="text/javascript" src="/js/admin/default/accounting.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/tools.js"></script>
    <script type="text/javascript" src="/js/admin/default/mkwcomp.js"></script>
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
                {if (!$setup.kisszamlazo)}
                {if (haveJog(20))}
                    <div><a class="menupont" href="/admin/bevetfej/viewlist">{t('Bevételezések')}</a></div>
                {/if}
                <div><a class="menupont" href="/admin/kivetfej/viewlist">{t('Kivétek')}</a></div>
                {if (($maintheme == 'superzone') && haveJog(20))}
                    <div><a class="menupont" href="/admin/egyebfej/viewlist">{t('Egyéb mozgások')}</a></div>
                {/if}
                {if (haveJog(20))}
                    <div><a class="menupont" href="/admin/megrendelesfej/viewlist">{t('Megrendelések')}</a></div>
                {/if}
                {if (($maintheme != 'mkwcansas') && haveJog(20))}
                    <div><a class="menupont" href="/admin/szallitofej/viewlist">{t('Szállítólevelek')}</a></div>
                {/if}
                {/if}
                <div><a class="menupont" href="/admin/szamlafej/viewlist">{t('Számlák')}</a></div>
                <div><a class="menupont" href="/admin/keziszamlafej/viewlist">{t('Kézi számlák')}</a></div>
                <div><a class="menupont" href="/admin/partner/viewlist">{t('Partnerek')}</a></div>
                <div><a class="menupont" href="/admin/termek/viewlist">{t('Termékek')}</a></div>
                {if (haveJog(20))}
                    <div><a class="menupont" href="/admin/termekfa/viewlist">{t('Termék kategóriák')}</a></div>
                    <div><a class="menupont" href="/admin/termekcimke/viewlist">{t('Termékcímkék')}</a></div>
                    <div><a class="menupont" href="/admin/partnercimke/viewlist">{t('Partnercímkék')}</a></div>
                {/if}
            </div>
            {if (($setup.bankpenztar) && haveJog(20))}
                <div class="menu-titlebar" data-caption="{t('Bank, pénztár')}" data-refcontrol="#BankTab"></div>
                <div id="BankTab">
                    <div><a class="menupont" href="/admin/bankbizonylatfej/viewlist">{t('Bank')}</a></div>
                </div>
            {/if}
            {if (haveJog(20))}
                {if (!$setup.kisszamlazo)}
                <div class="menu-titlebar" data-caption="{t('Webáruház')}" data-refcontrol="#WebTab"></div>
                <div id="WebTab">
                    <div><a class="menupont" href="/admin/kosar/viewlist">{t('Kosár')}</a></div>
                    <div><a class="menupont" href="/admin/korhinta/viewlist">{t('Körhinta')}</a></div>
                    <div><a class="menupont" href="/admin/hir/viewlist">{t('Hírek')}</a></div>
                    <div><a class="menupont" href="/admin/statlap/viewlist">{t('Statikus lapok')}</a></div>
                    <div><a class="menupont" href="/admin/emailtemplate/viewlist">{t('Email sablonok')}</a></div>
                    <div><a class="menupont" href="/admin/template/viewlist">{t('Sablonszerkesztő')}</a></div>
                    <div><a class="menupont" href="/admin/sitemap/view">{t('Sitemap')}</a></div>
                    <div><a class="menupont" href="/admin/keresoszolista/view">{t('Keresések')}</a></div>
                </div>
                {/if}
                <div class="menu-titlebar" data-caption="{t('Kimutatások')}" data-refcontrol="#ReportTab"></div>
                <div id="ReportTab">
                    <div><a class="menupont" href="/admin/navadatexport/view">{t('NAV adatexport')}</a></div>
                    {if ($setup.bankpenztar)}
                        {if (!$setup.kisszamlazo)}
                        <div><a class="menupont" href="/admin/jutaleklista/view">{t('Jutalék elszámolás')}</a></div>
                        <div><a class="menupont" href="/admin/penzbelista/view">{t('Beérkezett pénz')}</a></div>
                        {/if}
                        <div><a class="menupont" href="/admin/kintlevoseglista/view">{t('Kintlevőség')}</a></div>
                    {/if}
                    {if (!$setup.kisszamlazo)}
                    <div><a class="menupont" href="/admin/keszletlista/view">{t('Készlet')}</a></div>
                    <div><a class="menupont" href="/admin/fifo/view">{t('Készletérték')}</a></div>
                    {/if}
                </div>
                {if (!$setup.kisszamlazo)}
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
                {/if}
                <div class="menu-titlebar" data-caption="{t('Egyebek')}" data-refcontrol="#EgyebTab"></div>
                <div id="EgyebTab">
                    <div><a class="menupont" href="/admin/szallitasimod/viewlist">{t('Szállítási módok')}</a></div>
                    <div><a class="menupont" href="/admin/fizetesimod/viewlist">{t('Fizetési módok')}</a></div>
                    <div><a class="menupont" href="/admin/bizonylatstatusz/viewlist">{t('Bizonylat státuszok')}</a></div>
                    <div><a class="menupont" href="/admin/dolgozo/viewlist">{t('Felhasználók')}</a></div>
                    <div><a class="menupont" href="/admin/egyebtorzs/view">{t('Egyéb adatok')}</a></div>
                    <div><a class="menupont" href="/admin/export/view">{t('Termék exportok')}</a></div>
                    <div><a class="menupont" href="/admin/import/view">{t('Importok')}</a></div>
                    <div><a class="menupont js-regeneratekarkod" href="#">{t('Termék kat. rendezése')}</a></div>
                </div>
                <div><a class="menupont" href="/admin/setup/view">{t('Beállítások')}</a></div>
            {/if}
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