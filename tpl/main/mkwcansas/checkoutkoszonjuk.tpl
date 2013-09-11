{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<h3>Sikeres megrendelés!</h3>
			<p>Köszönjük, hogy a <b>Mindent Kapni Webáruházat</b> választotta!</p>
			<h4>Az Ön megrendelésének az azonosítója: <b>{$megrendelesszam}</b></h4>
                        <p>Kérjük jegyezze föl ezt a számot. A megrendelés megerősítéseként elküldtünk Önnek a megadott emailcímre egy visszaigazolást, amelyet hamarosan meg kell kapnia.</p>
                        <h4>Mikor kapom meg a megrendelésemet?</h4>
                        <p>Ha a megrendelésében szereplő összes termék készletünkön van, akkor legkorábban a következő munkanapon juthat a csomagjához, ha a megrendelése déli 12 óráig beérkezett hozzánk.</p>
                        <p>Ha valamelyik termék nincs készleten, annak a beszerzése néhány napot igénybe szokott venni, de általában 8 munkanapon belül tudjuk teljesíteni a megrendelését. Amennyiben ezt a határidőt meghaladná a szállítás, erről Önt feltétlenül tájékoztatni fogjuk.</p>
                        <p>A csomag kiküldése előtt még ügyfélszolgálatunk telefonon keresni fogja Önt, és egyeztet a szállítás idejéről.</p>
                        <h4>Nem írtam el véletlenül a méretet/színt/mennyiséget?</h4>
                        <p>Mostanra már biztosan megérkezett postafiókjába a visszaigazoló email a megrendeléséről. Ebben részletesen fel van sorolva minden egyes termék adata, így tudja ellenőrizni a megrendelés helyességét.</p>
                        <p>Ha valami miatt mégsem kapta volna meg ezt az emailt, kérjük először <b>ellenőrizze a levélszemetek mappáját</b>. Előfordulhat, hogy véletlenül odakerült a levelünk. Ha nincs ott, kérjük keressen minket elérhetőségeinken, és újból kiküldjük ezt a visszaigazolást.</p>
                        <h4>Változtathatok még a megrendelésemen?</h4>
                        <p>Természetesen igen, mindaddig, amíg ki nem küldjük, Önnek módjában áll változtatni a rendelésén. Ennek két módja van:</p>
                        <ul>
                            <li>telefonon: <b>20/342-1511</b></li>
                            <li>emailben: <b>info@mindentkapni.hu</b></li>
                        </ul>
                        <h4>Kérdéseim lennének, hol találok válaszokat?</h4>
                        <p>Csokorba gyűjtöttük a leggyakrabban felmerült kérdéseket, melyeket a következő oldalakon meg is válaszoltunk:</p>
                        <ul>
                            <li>Leggyakoribb kérdések és válaszok</li>
                            <li>Segítség a webáruház használatához</li>
                            <li>Általános szerződési feltételek</li>
                        </ul>
                        <p>Ha mégsem találja meg az Önt érdeklő témakört, természetesen bármikor hívhat minket, vagy küldhet emailt, boldogan állunk rendelkezésére!</p>
                        <a href="{$prevuri}" class="btn okbtn">Folytatom a vásárlást</a>
		</div>
	</div>
</div>
{/block}