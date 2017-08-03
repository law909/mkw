{include "headerfirstrow.tpl"}
<div class="headertop">
	<div class="container">
		<div class="row headercartcontainer">
			{if (!$user.loggedin)}
			<div class="span8">
				<div class="headerbutton firstheaderbutton">
					<a rel="nofollow" href="{$showloginlink}" class="headerloginicon">{t('Jelentkezz be')}</a>
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
		</div>
	</div>
</div>
<div class="container whitebg headbgtakaro">
    <div class="headermid container whitebg">
        <div class="row">
            <div class="span12">
            </div>
        </div>
    </div>
</div>