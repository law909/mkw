{extends "base.tpl"}

{block "script"}
    {if ($AKTrustedShopScript|default)}{$AKTrustedShopScript}{/if}
    <script>
        var _gravity = _gravity || [];
        // one buy event for each product bought
        {foreach $megrendelesadat as $ma}
        _gravity.push({type: "event", eventType: "BUY", itemId: "{$ma.id}", unitPrice: "{$ma.unitprice}", quantity: "{$ma.qty}", orderId: "{$megrendelesszam}"});
        {/foreach}
    </script>
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<h3>Sikeres megrendelés!</h3>
			<p>Köszönjük, hogy megtisztelt bizalmával! Bízunk benne, hogy újabb elégedett vásárlót üdvözölhetünk Önben.</p>
			<h5>Az Ön megrendelésének az azonosítója: <b>{$megrendelesszam}</b></h5>
                        <p>Hamarosan kapnia kell tőlünk egy emailt, melyben a megrendelését igazoljuk vissza. Kérjük írja fel az itt látható rendelési számot, hogy később erre hivatkozva hatékonyabban tudjunk segíteni Önnek.</p>
                        <h5>Nem kaptam meg az emailt, érvényes a rendelésem?</h5>
                        <p>Sajnos sokszor fordul elő főleg gmail-es és freemail-es emailcímeknél, hogy leveleink fennakadnak a spamszűrőkön. <b>Kérjük, ellenőrizze a levélszemét mappáját</b>, nagy valószínűséggel ott lesz a levelünk. A megrendelését természetesen megkaptuk, melyet a fiókjában nyomon követhet.</p>
                        <h5>Mikor kapom meg a megrendelésemet?</h5>
                        <p>Ha a megrendelésében szereplő összes termék készletünkön van, akkor legkorábban a következő munkanapon juthat a csomagjához, ha a megrendelése déli 12 óráig beérkezett hozzánk.</p>
                        <p>Ha valamelyik termék nincs készleten, annak a beszerzése néhány napot igénybe szokott venni, de általában 8 munkanapon belül tudjuk teljesíteni a megrendelését. Amennyiben ezt a határidőt meghaladná a szállítás, erről Önt feltétlenül tájékoztatni fogjuk.</p>
                        <p>A csomag kiküldése előtt még ügyfélszolgálatunk telefonon keresni fogja Önt, és egyeztet a szállítás idejéről.</p>
                        <h5>Nem írtam el véletlenül a méretet/színt/mennyiséget?</h5>
                        <p>Mostanra már biztosan megérkezett postafiókjába a visszaigazoló email a megrendeléséről. Ebben részletesen fel van sorolva minden egyes termék adata, így tudja ellenőrizni a megrendelés helyességét.</p>
                        <p>Ha valami miatt mégsem kapta volna meg ezt az emailt, kérjük először <b>ellenőrizze a levélszemetek mappáját</b>. Előfordulhat, hogy véletlenül odakerült a levelünk. Ha nincs ott, kérjük keressen minket elérhetőségeinken, és újból kiküldjük ezt a visszaigazolást.</p>
                        <h5>Változtathatok még a megrendelésemen?</h5>
                        <p>Természetesen igen, mindaddig, amíg ki nem küldjük, Önnek módjában áll változtatni a rendelésén. Ennek két módja van:</p>
                        <ul>
                            <li>telefonon: <b>20/342-1511</b></li>
                            <li>emailben: <a href="mailto:info@mindentkapni.hu?subject=Megrendelés módosítása ({$megrendelesszam})">info@mindentkapni.hu</a></li>
                        </ul>
                        <h5>Kérdéseim lennének, hol találok válaszokat?</h5>
                        <p>Csokorba gyűjtöttük a leggyakrabban felmerült kérdéseket, melyeket a következő oldalakon meg is válaszoltunk:</p>
                        <ul>
                            <li><a href="/statlap/gy-i-k-leggyakoribb-kerdesek">{t('Leggyakoribb kérdések és válaszok')}</a></li>
                            <li><a href="/statlap/szallitasi-feltetelek-es-tudnivalok">{t('Szállítási tudnivalók')}</a></li>
                            <li><a href="/statlap/aszf">{t('Általános szerződési feltételek')}</a></li>
                        </ul>
                        <p>Ha mégsem találja meg az Önt érdeklő témakört, természetesen bármikor hívhat minket, vagy küldhet emailt, boldogan állunk rendelkezésére!</p>
                        <a href="/" class="btn okbtn">Folytatom a vásárlást</a>
		</div>
	</div>
</div>
{/block}