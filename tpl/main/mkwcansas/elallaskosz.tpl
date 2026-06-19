{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<h2>{t('Köszönjük,')}</h2>
			<p>{t('elállási nyilatkozatát megkaptuk. Az átvételi elismervényt elküldtük a megadott email címre.')}</p>
			<a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}
