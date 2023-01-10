{extends "basestone.tpl"}

{block "script"}
    <script src="/js/main/darshan/rendezvenyreg.js?v=1"></script>
{/block}

{block "stonebody"}
    <div class="container">
        <div class="row">
            <div class="col">
                <h4 class="color-darshan">JELENTKEZÉS</h4>
                <div class="color-darshan">{$rendezvenynev}</div>
                {if ($szabadhelykovetes)}
                <div class="color-darshan">
                    {if ($varolistavan)}
                        {if ($szabadhelyszam)}
                            Szabad helyek száma: {$szabadhelyszam}
                        {else}
                            Sajnos csak várólistára tudunk felvenni.
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
            </div>
        </div>
        {if ($szabadhelyszam || !$szabadhelykovetes || $varolistavan)}
        <form id="rendezvenyregform" action="/rendezveny/reg/save" method="post">
            <div class="form-group row">
                <label for="vnevedit" class="col-sm-2 col-form-label">Vezetéknév</label>
                <div class="col">
                    <input type="text" class="form-control" id="vnevedit" name="vezeteknev" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="knevedit" class="col-sm-2 col-form-label">Keresztnév</label>
                <div class="col">
                    <input type="text" class="form-control" id="knevedit" name="keresztnev" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="emailedit" class="col-sm-2 col-form-label">Email</label>
                <div class="col">
                    <input type="email" class="form-control" id="emailedit" name="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="telefonedit" class="col-sm-2 col-form-label">Telefon</label>
                <div class="col">
                    <input type="text" class="form-control" id="telefonedit" name="telefon" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="ujdonsaghirlevelkell">
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
                        <input type="text" class="form-control" id="nevedit" name="nev" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="adoszamedit" class="col-sm-2 col-form-label">Adószám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="adoszamedit" name="adoszam">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="irszamedit" class="col-sm-2 col-form-label">Irányítószám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="irszamedit" name="irszam" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="varosedit" class="col-sm-2 col-form-label">Város</label>
                    <div class="col">
                        <input type="text" class="form-control" id="varosedit" name="varos" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="utcaedit" class="col-sm-2 col-form-label">Utca</label>
                    <div class="col">
                        <input type="text" class="form-control" id="utcaedit" name="utca" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hszedit" class="col-sm-2 col-form-label">Házszám</label>
                    <div class="col">
                        <input type="text" class="form-control" id="hszedit" name="hazszam" required>
                    </div>
                </div>
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