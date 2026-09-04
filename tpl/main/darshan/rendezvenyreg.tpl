{extends "basestone.tpl"}

{block "script"}
    <script src="/js/main/darshan/rendezvenyreg.js?v=3"></script>
{/block}

{block "stonebody"}
    <div class="container">
        <div class="row">
            <div class="col">
                <h4 class="color-darshan">JELENTKEZÉS</h4>
                <div class="color-darshan">{$rendezvenynev}</div>
                {if ($szabadhelykovetes)}
                <div class="color-darshan{if ($varolistavan && !$szabadhelyszam)} bold{/if}">
                    {if ($varolistavan)}
                        {if ($szabadhelyszam)}
                            Szabad helyek száma: {$szabadhelyszam}
                        {else}
                            Várólistára tudunk felvenni.
                        {/if}
                    {else}
                        {if ($szabadhelyszam)}
                            Szabad helyek száma: {$szabadhelyszam}
                        {else}
                            Sajnáljuk, nincs szabad hely.
                        {/if}
                    {/if}
                </div>
                {/if}
                {if ($hiba|default)}
                    <div class="alert alert-danger topmargin10" role="alert">{$hiba|escape}</div>
                {/if}
            </div>
        </div>
        {if ($szabadhelyszam || !$szabadhelykovetes || $varolistavan)}
        <form id="rendezvenyregform" action="/rendezveny/reg/save" method="post">
            <div class="form-group row">
                <label for="vnevedit" class="col-sm-2 col-form-label">Vezetéknév</label>
                <div class="col">
                    <input type="text" class="form-control" id="vnevedit" name="vezeteknev" value="{$egyed.vezeteknev|default|escape}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="knevedit" class="col-sm-2 col-form-label">Keresztnév</label>
                <div class="col">
                    <input type="text" class="form-control" id="knevedit" name="keresztnev" value="{$egyed.keresztnev|default|escape}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="emailedit" class="col-sm-2 col-form-label">Email</label>
                <div class="col">
                    <input type="email" class="form-control" id="emailedit" name="email" value="{$egyed.email|default|escape}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="telefonedit" class="col-sm-2 col-form-label">Telefon</label>
                <div class="col">
                    <input type="text" class="form-control" id="telefonedit" name="telefon" value="{$egyed.telefon|default|escape}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="ujdonsaghirlevelkell"{if ($egyed.ujdonsaghirlevelkell|default)} checked{/if}>
                        Kérek értesítést a stúdió programjairól
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck2" name="lemondasi" required>
                        A lemondási feltételeket elfogadom
                    </div>
                </div>
            </div>
            {if ($kellszamlazasiadat)}
                <div class="row">
                    <div class="col topmargin10">
                        <h5 class="color-darshan">Számlázási adatok</h5>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nevedit" class="col-sm-2 col-form-label">Név/Cégnév</label>
                    <div class="col">
                        <input type="text" class="form-control" id="nevedit" name="nev" value="{$egyed.nev|default|escape}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="adoszamedit" class="col-sm-2 col-form-label">Adószám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="adoszamedit" name="adoszam" value="{$egyed.adoszam|default|escape}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="irszamedit" class="col-sm-2 col-form-label">Irányítószám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="irszamedit" name="irszam" value="{$egyed.irszam|default|escape}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="varosedit" class="col-sm-2 col-form-label">Város</label>
                    <div class="col">
                        <input type="text" class="form-control" id="varosedit" name="varos" value="{$egyed.varos|default|escape}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="utcaedit" class="col-sm-2 col-form-label">Utca</label>
                    <div class="col">
                        <input type="text" class="form-control" id="utcaedit" name="utca" value="{$egyed.utca|default|escape}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hszedit" class="col-sm-2 col-form-label">Házszám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="hszedit" name="hazszam" value="{$egyed.hazszam|default|escape}" required>
                    </div>
                </div>
            {/if}
            {* A rendezvény kérdőíve – ugyanaz a mezőnév-séma, mint az időpontfoglaló űrlapon (kerdes_<i>, kerdes_<i>[]) *}
            {if ($kerdoiv.kerdesek|default)}
                <div class="row">
                    <div class="col topmargin10">
                        <h5 class="color-darshan">{if ($kerdoiv.cim)}{$kerdoiv.cim|escape}{else}Előzetes kérdések{/if}</h5>
                        {if ($kerdoiv.leiras)}<div>{$kerdoiv.leiras|escape|nl2br}</div>{/if}
                    </div>
                </div>
                {foreach $kerdoiv.kerdesek as $_i => $_k}
                    <div class="form-group row">
                        <div class="col">
                            <div class="col-form-label">{$_i + 1}. {$_k.szoveg|escape}{if ($_k.kotelezo)} *{/if}</div>
                            {if ($_k.tipus == 'tobb')}
                                <small class="form-text text-muted">Több válasz is megjelölhető.</small>
                            {/if}
                            {if ($_k.tipus == 'szoveg')}
                                <textarea class="form-control" name="kerdes_{$_i}" rows="3" maxlength="2000"{if ($_k.kotelezo)} required{/if}>{$_k.ertek|escape}</textarea>
                            {else}
                                {foreach $_k.opciok as $_o}
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="{if ($_k.tipus == 'tobb')}checkbox{else}radio{/if}"
                                                   name="kerdes_{$_i}{if ($_k.tipus == 'tobb')}[]{/if}" value="{$_o.szoveg|escape}"
                                                   {if ($_o.checked)} checked{/if}{if ($_k.kotelezo && $_k.tipus == 'egy')} required{/if}>
                                            {$_o.szoveg|escape}
                                        </label>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                {/foreach}
            {/if}
            <div class="form-group row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck3" name="gdpr" required>
                        Adatkezelési hozzájárulás
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 topmargin10">
                    <input type="hidden" name="r" value="{$uid}">
                    <input type="hidden" name="kellszamlazasiadat" value="{$kellszamlazasiadat}">
                    <button type="submit" class="btn btn-darshan">Regisztrálok</button>
                    <button class="js-lemond btn">Lemondom</button>
                </div>
            </div>
        </form>
        {/if}
    </div>
{/block}