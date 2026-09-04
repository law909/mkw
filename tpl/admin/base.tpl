<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="/themes/ui/{$uitheme}/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/style.css"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/{$theme}/matt.css"/>
    <script type="text/javascript" src="/js/admin/default/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-ui-1.14.2.min.js"></script>
    {* datepicker magyar honosítás – kötelezően a jquery-ui után *}
    <script type="text/javascript" src="/js/admin/default/jquery-ui-i18n-hu.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.blockUI-2.70.js"></script>
    <script type="text/javascript" src="/js/admin/default/dmb.js"></script>
    <script type="text/javascript" src="/js/admin/default/accounting.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/tools.js"></script>
    <script type="text/javascript" src="/js/admin/default/mkwcomp.js"></script>
    {* A mattkarb configja: a lista (mattable karb:) és az önálló karb oldal is használja *}
    <script type="text/javascript" src="/js/admin/default/mattkarb-config.js"></script>
    {* Pontosan az egyik töltődik be – mindkettő window.CKFinder-t definiál. *}
    {if ($setup.mediatar|default:0)}
        <script type="text/javascript" src="/js/admin/default/mediatar.js"></script>
    {else}
        <script type="text/javascript" src="/ckfinder/ckfinder.js"></script>
    {/if}
    <script type="text/javascript">window.mattableMindigNyitva = {$mindignyitva|default:0};</script>
    {block "inhead"}
    {/block}
    <script type="text/javascript" src="/js/admin/default/appinit.js"></script>
    <title>{$pagetitle|default} - {t('Billy Admin')}</title>
</head>
<body>
{if ($arfolyamriasztas)}
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
{if ($szuletesnap|default:false) || ($sysadmin|default:false)}
    {* 30 másodperces tűzijáték: bejelentkezés után magától, sysadminként a menü gombjáról – lásd szuletesnap.js *}
    <style>
        #szuletesnap {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: #06060c;
            cursor: pointer;
            overflow: hidden;
        }

        .szuletesnap-kep {
            display: block;
            width: 100%;
            height: 100%;
        }

        .szuletesnap-felirat {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            text-align: center;
            font-family: monospace;
            font-size: 4vw;
            font-weight: bold;
            letter-spacing: 0.15em;
            color: #fff;
            text-shadow: 0 0 10px #ffd166, 0 0 26px #ef476f, 0 0 60px #ef476f;
            pointer-events: none;
            animation: szuletesnapVillog 1.8s ease-in-out infinite;
        }

        @keyframes szuletesnapVillog {
            0%, 100% {
                opacity: 1;
                color: #fff;
            }
            50% {
                opacity: 0.3;
                color: #ffd166;
            }
        }
    </style>
    <script type="text/javascript" src="/js/admin/default/szuletesnap.js"></script>
    {if ($szuletesnap|default:false)}
        <script type="text/javascript">szuletesnap.indit();</script>
    {/if}
{/if}
<div class="screen">
    {if ($userloggedin)}
        <div class="menu-container ui-widget ui-widget-content ui-corner-all">
            {if ($teszt)}
                <div class="textaligncenter teszt-uzemmod"
                     style="color:#fff;background:#c0392b;font-weight:bold;padding:3px;border-radius:3px;margin-bottom:3px;">
                    TESZT ÜZEMMÓD
                </div>
            {/if}
            <div class="textaligncenter">{$tulajnev}</div>
            <div class="textaligncenter">{$loggedinuser.name}</div>
            {* A menücsoportok fejlécére kattintva nyílnak/záródnak; a nyitott állapot
               dolgozónként mentődik (lásd appinit.js + adminController::setMenucsoportNyitva).
               Csoport nélküli menüpont nem kap fejlécet, és mindig látszik. *}
            {$cscikl = 0}
            {$mdb = count($menu)}
            {while ($cscikl < $mdb)}
                {$mcs = $menu[$cscikl]['mcsid']}
                {$mcsnyitva = $menu[$cscikl]['mcsnyitva']}
                {if ($menu[$cscikl]['mcsnev'])}
                    <div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all js-menucsoporttoggle"
                         data-mcsid="{$mcs}" title="{t('Nyitás/zárás')}">
                        <span class="ui-icon menu-titlebar-icon {if ($mcsnyitva)}ui-icon-circle-triangle-n{else}ui-icon-circle-triangle-s{/if}"></span>
                        <span class="ui-jqgrid-title">{t($menu[$cscikl]['mcsnev'])}</span>
                    </div>
                {/if}
                <div class="menu-csoport js-menucsoport" data-mcsid="{$mcs}"{if (!$mcsnyitva)} style="display:none;"{/if}>
                    {while ($cscikl < $mdb) && ($menu[$cscikl]['mcsid'] == $mcs)}
                        <div><a
                                class="menupont ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only {$menu[$cscikl]['class']}"
                                href="{$menu[$cscikl]['url']}"><span class="ui-button-text">{t($menu[$cscikl]['nev'])}</span></a>
                        </div>
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
                {if ($sysadmin|default:false)}
                    <a class="js-szuletesnapteszt ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                       href="#" title="{at('Születésnapi tűzijáték')}"><span class="ui-button-text">🎆</span></a>
                {/if}
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