{extends "base.tpl"}

{block "script"}
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
            {if ($locale === 'hu')}
                <h3>Sikeres megrendelés!</h3>
                <p>Köszönjük, hogy megtisztelt bizalmával! Bízunk benne, hogy újabb elégedett vásárlót üdvözölhetünk Önben.</p>
                <h5>Az Ön megrendelésének az azonosítója: <b>{$megrendelesszam}</b></h5>
                <p>Hamarosan kapnia kell tőlünk egy emailt, melyben a megrendelését igazoljuk vissza. Kérjük írja fel az itt látható rendelési számot, hogy később erre hivatkozva hatékonyabban tudjunk segíteni Önnek.</p>
            {elseif ($locale === 'en')}
                <h3>Successful order!</h3>
                <h5>Order No.: <b>{$megrendelesszam}</b></h5>

            {/if}
            <a href="/" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
		</div>
	</div>
</div>
{/block}