{extends "base.tpl"}

{block "script"}
	<script>
		fbq('track', 'Purchase', {
			content_ids: {$orderProductIds},
			content_type: 'product',
			value: {$fizetendo},
			currency: 'HUF'
		});
	</script>
{/block}

{block "kozep"}
<div class="container checkout-page-thankyou whitebg">
	<div class="row">
		<div class="col flex-cc flex-col offset1">
		{$lasttermekids}

            <h3>{t('Köszönjük a megrendelést!')}</h3>
            <h5>{t('Megrendelésszám:')} <b>{$megrendelesszam}</b></h5>
            <a href="/" class="button primary okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}