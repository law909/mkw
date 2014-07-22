{include "headerfirstrow.tpl"}
<div class="headertop">
	<div class="container">
		<div class="row headercartcontainer">
			{if (!$user.loggedin)}
			<div class="span8">
				<div class="headerbutton firstheaderbutton">
					<a rel="nofollow" href="{$showloginlink}" class="headerloginicon">{t('Jelentkezzen be')}</a>
				</div>
				<div class="headerbutton lastheaderbutton">
					<a rel="nofollow" href="{$showregisztraciolink}">{t('Hozza létre saját fiókját')}</a>
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
				<a href="{$kosargetlink}" class="headercarticon pull-right" rel="nofollow"></a>
			</div>
		</div>
	</div>
</div>
<div class="container whitebg headbgtakaro">
    <div class="headermid container whitebg">
        <div class="row">
            <div class="span12">
            <div class="pull-left">
                <a href="/"><img src="{$logo}" alt="Mindent Kapni Webáruház logo" title="Mindent Kapni Webáruház"></a>
            </div>
            <div class="pull-left">
                <form id="searchform" name="searchbox" method="get" action="/kereses" autocomplete="off">
                <div class="searchinputbox">
                    <input id="searchinput" class="siteSearch" type="text" title="{t('Keressen a termékeink között!')}" placeholder="{t('Keressen a termékeink között!')}" accesskey="k" value="" maxlength="300" name="keresett">
                    <input id="searchbutton" type="submit" value="">
                </div>
                </form>
            </div>
            <div class="pull-right">
<!-- SHOP OF THE COUNTRY CODE BEGIN-->
<script type="text/javascript">
    /*<![CDATA[*/
    var __akn=new Date();
    var __ake=new Date(1405323060000);
    var __akl=Math.ceil((__ake.getTime()-__akn.getTime())/86400000);
    if(__akl<=0) {
        document.write('<a href="//www.orszagboltja.hu/szavazas/415" target="_blank"><img src="//assets2.orszagboltja.hu/soc/widget/hu/vote-banner-220x50-2014.png?p=415" style="border-style:none;" alt="Az ország boltja 2014 - szavazok" /><\/a>');
    }
    else {
        document.write('<div style="margin:0; padding:0; width:220px;height:50px;background:url(\'//assets2.orszagboltja.hu/soc/widget/hu/vote-banner-220x50-1-2014.png?p=415\');color:#FFF;font-size:16px;font-weight:bold;font-family:Arial,sans-serif;text-align:center;overflow:hidden;"><a href="//www.orszagboltja.hu/szavazas/415" target="_blank" style="display:block; margin:0; padding:4px 80px 0 5px; color:#FFF; text-decoration:none;">m&eacute;g <span style="font-size:18px; color:#fe5e0d;">'+__akl+'<\/span> nap<br \/>a szavaz&aacute;sig<\/a><\/div>');
    }/*]]>*/
</script>
<!-- SHOP OF THE COUNTRY CODE END-->
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
</div>