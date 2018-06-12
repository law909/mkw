{extends "base.tpl"}

{block "script"}
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
            <h3>We are sorry, there are some errors with your order!</h3>
            <h5>Your order no.: <b>{$megrendelesszam}</b></h5>
            <h5>Please contact us in email: <a href="mailto:"></a></h5>
            <a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}