{extends "base.tpl"}

{block "script"}
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
            <h3>Successful order!</h3>
            <h5>Order No.: <b>{$megrendelesszam}</b></h5>
            <a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}