{include "headerfirstrow.tpl"}
<div class="headertop">
	<div class="container">
		<div class="row headercartcontainer">
			{if (!$user.loggedin)}
			<div class="span8">
				<div class="headerbutton firstheaderbutton">
					<a rel="nofollow" href="/login" class="headerloginicon">{t('Jelentkezzen be')}</a>
				</div>
				<div class="headerbutton lastheaderbutton">
					<a rel="nofollow" href="/regisztracio">{t('Hozza létre saját fiókját')}</a>
				</div>
			</div>
			{else}
			<div class="span8">
				<div class="headerbutton">
					<a rel="nofollow" href="/fiok" title="{$user.nev}">{t('Fiókom')}</a>
				</div>
				<div class="headerbutton lastheaderbutton">
					<a rel="nofollow" href="/logout">{t('Kijelentkezés')}</a>
				</div>
			</div>
			{/if}
			<div class="headercart hidden-phone">
				<div><a rel="nofollow" href="/kosar/get" class="headercarticon"></a></div>
				<div>
					<div class="headercarttext">{t('KOSÁRBAN')}</div>
					<div>
						<a id="minikosar" href="/kosar/get" rel="nofollow">
							{include "minikosar.tpl"}
						</a>
					</div>
				</div>
				<div><a class="btn headercartbtn">{t('Pénztárhoz')}</a></div>
			</div>
		</div>
	</div>
</div>
<div class="headermid">
	<div class="container">
		<div class="row">
			<div class="span12">
			<div class="pull-left">
				<a href="/"><img src="/themes/main/mkwcansas/img/mkw-logo.png" alt="Mindent Kapni Webáruház logo" title="Mindent Kapni Webáruház"></a>
			</div>
			<div class="pull-left">
				<form id="searchform" name="searchbox" method="get" action="/kereses" autocomplete="off">
				<div class="searchinputbox">
					<input id="searchinput" class="siteSearch" type="text" title="{t('Keressen a termékeink között!')}" placeholder="{t('Keressen a termékeink között!')}" accesskey="k" value="" maxlength="300" name="keresett">
					<input id="searchbutton" type="submit" value="">
				</div>
				</form>
			</div>
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