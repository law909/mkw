<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="/themes/ui/{$uitheme}/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/style.css?v=2"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/matt.css"/>
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
    <title>{$pagetitle|default} - {t('Billy Admin')}</title>
</head>
<body>
{if (!$arfolyamriasztas)}
    <h1 id="arfolyamriasztas">Túl régi az utolsó árfolyam. CSINÁLJ EGY ÁRFOLYAMLETÖLTÉST!</h1>
{/if}
{if ($bekuldetlenszamlacnt > 0)}
    <h1 id="naveredmenyriasztas">{$bekuldetlenszamlacnt} db számla nincs beküldve a NAV-nak!</h1>
{/if}
{if ($nominkeszlet)}
    <h2 id="nominkeszletriasztas">Minimum készlet figyelés ki van kapcsolva!</h2>
{/if}
<div id="messagecenter"></div>
<div id="dialogcenter"></div>
<div class="screen">
    {if ($userloggedin)}
        <div class="menu-container ui-widget ui-widget-content ui-corner-all">
            <div class="textaligncenter">{$tulajnev}</div>
            <div class="textaligncenter">{$loggedinuser.name}</div>
            {$cscikl = 0}
            {$mdb = count($menu)}
            {while ($cscikl < $mdb)}
                {if ($menu[$cscikl]['mcsnev'])}
                    <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all">
                        <span class="ui-jqgrid-title">{t($menu[$cscikl]['mcsnev'])}</span>
                    </div>
                {/if}
                {$mcs = $menu[$cscikl]['mcsid']}
                {while ($cscikl < $mdb) && ($menu[$cscikl]['mcsid'] == $mcs)}
                    <div><a
                            class="menupont ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only {$menu[$cscikl]['class']}"
                            href="{$menu[$cscikl]['url']}"><span class="ui-button-text">{t($menu[$cscikl]['nev'])}</span></a>
                    </div>
                    {$cscikl = $cscikl + 1}
                {/while}
            {/while}
            <div>
                <select id="ThemeSelect">
                    {foreach $uithemes as $_uitheme}
                        <option value="{$_uitheme}"{if ($uitheme==$_uitheme)} selected="selected"{/if}>{$_uitheme}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    {/if}
    <div class="content-container">
        {block "kozep"}
        {/block}
    </div>
</div>
</body>
</html>