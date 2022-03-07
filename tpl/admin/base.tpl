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
    <title>{$pagetitle|default} - {t('Billy Admin')}</title>
</head>
<body>
{if ($bekuldetlenszamlacnt > 0)}
    <h1 id="naveredmenyriasztas">{$bekuldetlenszamlacnt} db számla nincs beküldve a NAV-nak!</h1>
{/if}
<div id="messagecenter"></div>
<div id="dialogcenter"></div>
<div>
    {if ($userloggedin)}
        <div id="menu" class="matt-container ui-widget ui-widget-content ui-corner-all">
            <div class="textaligncenter">{$loggedinuser.name}</div>
            {$tabcnt = 1}
            {$cscikl = 0}
            {$mdb = count($menu)}
            {while ($cscikl < $mdb)}
                {if ($menu[$cscikl]['mcsnev'])}
                    <div class="menu-titlebar" data-caption="{t($menu[$cscikl]['mcsnev'])}" data-refcontrol="#Tab{$tabcnt}"></div>
                {/if}
                <div id="Tab{$tabcnt}">
                {$mcs = $menu[$cscikl]['mcsid']}
                {while ($cscikl < $mdb) && ($menu[$cscikl]['mcsid'] == $mcs)}
                    <div><a class="menupont {$menu[$cscikl]['class']}" href="{$menu[$cscikl]['url']}">{t($menu[$cscikl]['nev'])}</a></div>
                    {$cscikl = $cscikl + 1}
                {/while}
                </div>
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
    <div id="kozep">
        {block "kozep"}
        {/block}
    </div>
</div>
</body>
</html>