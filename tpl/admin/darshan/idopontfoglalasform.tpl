<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt">
    {if ($hiba)}
        <div class="foglalashiba">{$hiba}</div>
    {/if}
    {if ($idopontid && $foglalhato)}
        <div class="foglalasfejlec">
            <div><strong>{$temanev}</strong></div>
            <div>{$napnev} - {$datum} {$idotartam}</div>
            <div>{$tanar}</div>
            {if ($helyszin)}
                <div>{$helyszin}{if ($helyszincim)} ({$helyszincim}){/if}</div>
            {/if}
            {if ($ar > 0)}
                <div>{$ar|number_format:0:",":" "} Ft</div>
            {/if}
            {if ($varolista)}
                <div>Az alkalom betelt, várólistára tudunk felvenni.</div>
            {else}
                <div>{$szabadhely} szabad hely</div>
            {/if}
        </div>
        <form id="idopontfoglalasform" method="post" action="/idopont/foglalas/ment">
            <div class="form-group">
                <label class="form-label" for="nevedit">Teljes név *</label>
                <input class="form-control" id="nevedit" type="text" name="nev" maxlength="255" value="{$egyed.nev|escape}" required>
                <div class="form-hint">Vezeték- és keresztnév.</div>
            </div>
            <div class="form-group">
                <label class="form-label" for="emailedit">Email *</label>
                <input class="form-control" id="emailedit" type="email" name="email" maxlength="255" value="{$egyed.email|escape}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="telefonedit">Telefonszám *</label>
                <input class="form-control" id="telefonedit" type="text" name="telefon" maxlength="50" value="{$egyed.telefon|escape}" required>
            </div>
            <p class="form-hint js-cimszoveg">Ha először jössz, add meg a címed a számlához. A már megadott adataidat nem írjuk felül.</p>
            <div class="js-cimblokk">
                <div class="form-group">
                    <label class="form-label" for="irszamedit">Irányítószám</label>
                    <input class="form-control" id="irszamedit" type="text" name="irszam" maxlength="10" value="{$egyed.irszam|escape}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="varosedit">Város</label>
                    <input class="form-control" id="varosedit" type="text" name="varos" maxlength="255" value="{$egyed.varos|escape}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="utcaedit">Utca, házszám</label>
                    <input class="form-control" id="utcaedit" type="text" name="utca" maxlength="255" value="{$egyed.utca|escape}">
                </div>
            </div>
            {if ($kerdoiv.kerdesek)}
                <div class="kerdoiv">
                    {if ($kerdoiv.cim)}<div class="kerdoivcim">{$kerdoiv.cim|escape}</div>{/if}
                    {if ($kerdoiv.leiras)}<div class="kerdoivleiras">{$kerdoiv.leiras|escape|nl2br}</div>{/if}
                    {foreach $kerdoiv.kerdesek as $_i => $_k}
                        <div class="form-group kerdoivkerdes">
                            <span class="form-label">{$_i + 1}. {$_k.szoveg|escape}{if ($_k.kotelezo)} *{/if}</span>
                            {if ($_k.tipus == 'tobb')}
                                <div class="kerdoivhint">Több válasz is megjelölhető.</div>
                            {/if}
                            {if ($_k.tipus == 'szoveg')}
                                <textarea class="form-control kerdoivszoveg" name="kerdes_{$_i}" rows="3" maxlength="2000"{if ($_k.kotelezo)} required{/if}>{$_k.ertek|escape}</textarea>
                            {else}
                                {foreach $_k.opciok as $_o}
                                    <label class="kerdoivvalasz">
                                        <input type="{if ($_k.tipus == 'tobb')}checkbox{else}radio{/if}"
                                               name="kerdes_{$_i}{if ($_k.tipus == 'tobb')}[]{/if}" value="{$_o.szoveg|escape}"
                                               {if ($_o.checked)} checked{/if}{if ($_k.kotelezo && $_k.tipus == 'egy')} required{/if}>
                                        {$_o.szoveg|escape}
                                    </label>
                                {/foreach}
                            {/if}
                        </div>
                    {/foreach}
                </div>
            {/if}
            {if ($onlinevalaszthato)}
                <div class="form-group">
                    <span class="form-label">Hogyan veszel részt?</span>
                    <label>
                        <input type="radio" name="reszvetel" value="elo"{if ($egyed.reszvetel !== 'online')} checked="checked"{/if}>
                        Élőben
                    </label>
                    <label>
                        <input type="radio" name="reszvetel" value="online"{if ($egyed.reszvetel === 'online')} checked="checked"{/if}>
                        Online
                    </label>
                </div>
            {/if}
            <input type="hidden" name="id" value="{$idopontid}">
            <input type="hidden" name="d" value="{$datumparam}">
            <input type="hidden" name="t" value="{$tanarkod}">
            <input type="hidden" name="tema" value="{$temakod}">
            <div class="form-group">
                <button class="foglalasbtn" type="submit">{if ($varolista)}Várólistára iratkozom{else}Foglalok{/if}</button>
            </div>
        </form>
    {/if}
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
<script type="text/javascript">
{literal}
    (function () {
        const form = document.getElementById('idopontfoglalasform');
        if (!form) {
            return;
        }
        const emailEdit = form.querySelector('input[name="email"]'),
            cimblokk = form.querySelector('.js-cimblokk'),
            cimszoveg = form.querySelector('.js-cimszoveg'),
            EMAILMINTA = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // csak azt tudjuk meg, kell-e címet kérni: nevet, címet a szerver nem ad ki
        function checkEmail() {
            const email = emailEdit.value.trim();
            if (!EMAILMINTA.test(email)) {
                return;
            }
            fetch('/jelentkezes/emailellenor', { method: 'POST', body: new URLSearchParams({ email: email }) })
                .then((res) => res.json())
                .then((adat) => {
                    if (emailEdit.value.trim() !== email) {
                        return;
                    }
                    const megvan = adat.ismert && !adat.cimhianyos;
                    cimblokk.hidden = megvan;
                    if (megvan) {
                        cimblokk.querySelectorAll('input').forEach((input) => {
                            input.value = '';
                        });
                        cimszoveg.textContent = 'Ezzel az emailcímmel már jártál nálunk, a számlázási adataid megvannak.';
                    } else {
                        cimszoveg.textContent = adat.ismert
                            ? 'Ezzel az emailcímmel már jártál nálunk, de a számládhoz hiányzik a címed. Kérjük, add meg.'
                            : 'Első alkalom? Add meg a címed, hogy ki tudjuk állítani a számlát a foglalásról.';
                    }
                })
                .catch(() => {});
        }

        emailEdit.addEventListener('change', checkEmail);
        // hibával visszatöltött űrlapon az email már ki van töltve
        if (emailEdit.value.trim()) {
            checkEmail();
        }
    })();
{/literal}
</script>
</body>
</html>
