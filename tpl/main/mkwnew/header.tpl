<div class="headTop">
	<div class="container">
		<div class="row">
			{if (!$user.loggedin)}
			<div class="span8 accountBlock">
				<a class="accountSignInTop" rel="nofollow" href="/login">{t('Jelentkezzen be')}</a>
				|
				<a rel="nofollow" href="/regisztracio">{t('Regisztráljon')}</a>
			</div>
			{else}
			<div class="span8 accountBlock">
				<a class="accountSignInTop" rel="nofollow" href="/fiok" title="{$user.nev}">{t('Fiókom')}</a>
				|
				<a rel="nofollow" href="/logout">{t('Kijelentkezés')}</a>
			</div>
			{/if}
			<div class="span4">
				<div class="row cartTop">
					<div class="span1 offset1"><a href="/kosar/get" class="cartIconTop"></a></div>
					<div class="span2"><a id="minikosar" href="/kosar/get" rel="nofollow" class="hidden-phone">
							{include "minikosar.tpl"}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="headMiddle">
	<div class="container">
		<div class="row">
			<div class="span4"><a href="/"><img alt="Mindentkapni Webáruház" src="/themes/main/mkwnew/logo.png"></a></div>
			<div class="span5 searchBox">
				<form id="searchform" name="searchbox" method="get" action="/kereses">
				<input id="KeresoEdit" class="siteSearch" type="text" title="{t('Mit vásárolna?')}" placeholder="{t('Mit vásárolna?')}" accesskey="k" value="" maxlength="300" name="keresett">
				<input type="submit" value="Go">
				<div class="clear"></div>
				</form>
			</div>
			<div class="span3"></div>
		</div>
	</div>
</div>
<div class="headNav">
	<div class="container">
		<div class="row">
			<div class="span12">
				<nav>
					<ul id="navMain">
						{foreach $menu1 as $_menupont}
							<li><a href="/termekfa/{$_menupont.slug}" data-cnt="{$_menupont.childcount}">{$_menupont.caption} <i class="icon-chevron-down icon-white"></i></a>
							<div class="sub">
								{foreach $_menupont.children as $_focsoport}
								<ul>
									<li class="categoryTitle">{$_focsoport.caption}</li>
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