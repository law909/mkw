{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
            {if ($locale === 'hu')}
			<h2>Köszönjük,</h2><p>üzenetét megkaptuk. Kollégáink legkésőbb a következő munkanapon felveszik Önnel a kapcsolatot.</p>
            {elseif ($locale === 'en')}
                <h2>Thank You,</h2><p>we got your message. We will contact you on the next working day.</p>
            {/if}
                        <a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}