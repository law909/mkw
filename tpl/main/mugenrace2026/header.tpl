{include "headerfirstrow.tpl"}
{* {$mugenracetermekmenu} *}

{* Demo content amíg nem sikerül lekérdezni a termékmenüt *}
{* {$menu1[0]['childcount'] = 10 }
{$menu1[0]['children'] = $menu1} *}
{* Demo content amíg nem sikerül lekérdezni a termékmenüt *}

<div class="header container-full__with-padding">
    <div class="row">
        <div class="col flex-cl header__left">
            <a href="/"><img class="header__logo" src="/themes/main/mugenrace2026/img/mugen-logo-white.svg" alt="Mugenrace Webshop"
                             title="Mugenrace Webshop"></a>
        </div>
        <div class="col flex-cc header__center">
            <nav class="main-menu flex-cc">
                <ul id="" class="flex-cc">
                    {foreach $menu1[0]['children'] as $_menupont}
                        <li{if ($_menupont@last)} class="last"{/if}{if ($_menupont@first)} class="first"{/if}><a href="/categories/{$_menupont.slug}" data-cnt="{count($_menupont.children)}">{$_menupont.nev}</a>
                        {if (count($_menupont.children)>0)}
                            <i class="icon arrow-down white main-menu__arrow icon__click"></i>
                            <div class="sub">
                                <div class="sub__wrapper">
                                    {foreach $_menupont.children as $_focsoport}
                                    <ul>
                                        <li class="categorytitle"><a href="/categories/{$_focsoport.slug}">{$_focsoport.nev}</a></li>
                                        {foreach $_focsoport.children as $_alcsoport}
                                            <li><a href="/categories/{$_alcsoport.slug}">{$_alcsoport.nev}</a></li>
                                        {/foreach}
                                    </ul>
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                        </li>
                    {/foreach}
                    
                    <li><a href="/news" title="{t('Legfrissebb híreink')}">{t('Legfrissebb híreink')}</a></li>
                    <li><a href="/statlap/about-us" title="{t('Rólunk')}">{t('Rólunk')}</a></li>
                    <li><a href="/riders" title="{t('Szponzorált versenyzők')}">{t('Szponzorált versenyzők')}</a></li>
                    <li><a href="/teams" title="{t('Csapatok')}">{t('Csapatok')}</a></li>
                </ul>
                <i class="icon close white main-menu__close icon__click"></i>
            </nav>
        </div>
        <div class="col flex-cr header__right">

            <nav class="right-menu flex-cc">
                <ul id="" class="flex-cc">
                    <li><i class="icon search white icon__click"></i></li>
                    <li>
                        {* <a id="minikosar" class="pull-right" href="{$kosargetlink}" rel="nofollow"> *}
                        {include "minikosar.tpl"}
                        {* </a> *}
                    </li>
                    <li>
                        <i class="icon menu white menu-toggle icon__click"></i>
                    </li>
                </ul>
            </nav>

            <form id="searchform" class="header__searchform flex-cc" name="searchbox" method="get" action="/search" autocomplete="off">
                <div class="searchinputbox flex-cc">
                    <input id="searchinput" class="siteSearch span2" type="text" title="{t('Keressen a termékeink között!')}"
                           placeholder="{t('Keressen a termékeink között!')}" accesskey="k" value="" maxlength="300" name="keresett">
                    {* <input id="searchbutton" type="submit" value=""> *}
                </div>
                <i class="icon close white header__searchform-close icon__click"></i>
            </form>
        </div>
    </div>
</div>
{* <pre> *}
    {* {json_encode($menu1, JSON_PRETTY_PRINT)} *}
    {* {var_dump($menu1)} *}
{* </pre> *}

{*
<div class="headertop">
	<div class="container">
		<div class="row headercartcontainer">
			{if (!$user.loggedin)}
			<div class="span8">
				<div class="headerbutton firstheaderbutton">
					<a rel="nofollow" href="{$showloginlink}" class="headerloginicon">{t('Jelentkezzen be')}</a>
				</div>
				<div class="headerbutton">
					<a rel="nofollow" href="{$showregisztraciolink}">{t('Hozza létre saját fiókját')}</a>
				</div>
                <div class="headerbutton lastheaderbutton">
                    <select name="headerorszag" class="headerorszag">
                        {foreach $orszaglist as $f}
                            <option value="{$f.id}"{if ($f.selected)} selected="selected"{/if}>{$f.caption}</option>
                        {/foreach}
                    </select>
                </div>
			</div>
			{else}
			<div class="span8">
				<div class="headerbutton">
					<a rel="nofollow" href="{$showaccountlink}" title="{t('Fiókom')}">{$user.nev}</a>
				</div>
				<div class="headerbutton lastheaderbutton">
					<a rel="nofollow" href="{$dologoutlink}">{t('Kijelentkezés')}</a>
				</div>
			</div>
			{/if}
			<div class="headercart">
				<a href="{$kosargetlink}" class="btn cartbtn pull-right" rel="nofollow">{t('Kosár')}</a>
				<a id="minikosar" class="pull-right" href="{$kosargetlink}" rel="nofollow">
					{include "minikosar.tpl"}
				</a>
			</div>
		</div>
	</div>
</div>
<div class="header-ingyenes">
    <div id="minikosaringyenes" class="container">
        {include "minikosaringyenes.tpl"}
    </div>
</div>
<div class="container whitebg headbgtakaro">
    <div class="headermid container whitebg">
        <div class="row">
            <div class="span12">
                <div class="span2">
                    <a href="/"><img src="{$imagepath}{$mugenracelogo}" class="headerlogo" alt="Mugenrace webshop" title="Mugenrace webshop"></a>
                </div>
                <div class="span2">
                    <form id="searchform" name="searchbox" method="get" action="/kereses" autocomplete="off">
                    <div class="searchinputbox">
                        <input id="searchinput" class="siteSearch span2" type="text" title="{t('Keressen a termékeink között!')}" placeholder="{t('Keressen a termékeink között!')}" accesskey="k" value="" maxlength="300" name="keresett">
                        <input id="searchbutton" type="submit" value="">
                    </div>
                    </form>
                </div>
                <div class="span7 fejleckep">
                    <img src="{$imagepath}{$mugenracefejleckep}">
                </div>
            </div>
        </div>
    </div>
    <div class="container headernav">
        <div class="row">
            <div class="span16">
                <nav>
                    <ul id="navmain">
                        {foreach $menu1 as $_menupont}
                            <li{if ($_menupont@last)} class="last"{/if}{if ($_menupont@first)} class="first"{/if}><a href="/termekfa/{$_menupont.slug}" data-cnt="{$_menupont.childcount}">{$_menupont.caption}</a>
                            <div class="sub">
                                {foreach $_menupont.children as $_focsoport}
                                <ul>
                                    <li class="categorytitle">{$_focsoport.caption}</li>
                                    {foreach $_focsoport.children as $_alcsoport}
                                        <li><a href="/termekfa/{$_alcsoport.slug}">{$_alcsoport.caption}</a></li>
                                    {/foreach}
                                </ul>
                                {/foreach}
                            </div>
                            </li>
                        {/foreach}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div> *}