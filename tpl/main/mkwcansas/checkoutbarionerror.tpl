{extends "base.tpl"}

{block "script"}
{/block}

{block "kozep"}
<div class="container">
    <div class="row">
        <div class="span10 offset1">
            <h3>Sikertelen fizetés!</h3>
            <p>A megrendelés kifizetése sajnos nem sikerült!</p>
            <h5>Az Ön megrendelésének az azonosítója: <b>{$megrendelesszam}</b></h5>
            <p>Kérjük írja fel az itt látható rendelési számot és erre hivatkozva keressen minket, hogy segíteni tudjunk Önnek.</p>
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