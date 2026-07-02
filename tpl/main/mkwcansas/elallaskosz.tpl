{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<h2>{t('Köszönjük, hogy kitöltötte az elállási űrlapot!')}</h2>
			<p>{t('Hamarosan automatikus visszaigazoló e-mailt küldünk a megadott e-mail-címre. Kollégáink a bejelentését feldolgozzák, és rövidesen felveszik Önnel a kapcsolatot a további teendőkkel kapcsolatban.')}</p>
			<p>{t('Köszönjük türelmét!')}</p>
			<a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}
