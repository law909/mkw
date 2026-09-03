{extends "basestone.tpl"}

{block "script"}
    <script src="/js/main/galad/checkout.js"></script>
{/block}

{block "bodyclass"}class="body"{/block}

{block "stonebody"}
    <div class="container content-back checkout-content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a href="/"><img src="{$imagepath}{$logo}"></a><span class="checkout-header">Megrendelés</span>
            </div>
        </div>
        <div class="row">
            <form id="CheckoutForm" class="col-md-8 col-md-offset-2 form-horizontal" action="/checkout/ment" method="post">
                <fieldset>
                    <h3>Számlázási cím</h3>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiNevEdit" class="col-md-2 control-label">Név</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiNevEdit" type="text" class="form-control" name="szamlanev" value="{$szamlanev|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiUtcaEdit" class="col-md-2 control-label">Cím</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiUtcaEdit" type="text" class="form-control" name="szamlautca" value="{$szamlautca|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiVarosEdit" class="col-md-2 control-label">Város</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiVarosEdit" type="text" class="form-control" name="szamlavaros" value="{$szamlavaros|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiIrszamEdit" class="col-md-2 control-label">Irányítószám</label>
                        <div class="col-md-2">
                            <input id="SzamlazasiIrszamEdit" type="text" class="form-control" name="szamlairszam" value="{$szamlairszam|default}">
                        </div>
                    </div>

                    <h3>Szállítási cím</h3>
                    <div class="form-group col-md-12">
                        <label for="SzalleqszamlaEdit" class="col-md-2 control-label">Megegyezik a számlázási címmel</label>
                        <div class="col-md-10">
                            <input id="SzalleqszamlaEdit" type="checkbox" class="form-checkbox" name="szalleqszamla"{if ($szalleqszamla|default)} checked{/if}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiNevEdit" class="col-md-2 control-label">Név</label>
                        <div class="col-md-10">
                            <input id="SzallitasiNevEdit" type="text" class="form-control" name="szallnev" value="{$szallnev|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiUtcaEdit" class="col-md-2 control-label">Cím</label>
                        <div class="col-md-10">
                            <input id="SzallitasiUtcaEdit" type="text" class="form-control" name="szallutca" value="{$szallutca|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiVarosEdit" class="col-md-2 control-label">Város</label>
                        <div class="col-md-10">
                            <input id="SzallitasiVarosEdit" type="text" class="form-control" name="szallvaros" value="{$szallvaros|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiIrszamEdit" class="col-md-2 control-label">Irányítószám</label>
                        <div class="col-md-2">
                            <input id="SzallitasiIrszamEdit" type="text" class="form-control" name="szallirszam" value="{$szallirszam|default}">
                        </div>
                    </div>

                    <h3>Megjegyzés</h3>
                    <div class="form-group col-md-12">
                        <label for="MegjegyzesEdit" class="col-md-2 control-label">Megjegyzés a webáruháznak</label>
                        <div class="col-md-10">
                            <textarea id="MegjegyzesEdit" class="form-control" name="webshopmessage" type="text" rows="2">{$webshopmessage}</textarea>
                        </div>
                    </div>

                    <h3>Szállítás és fizetés</h3>
                    <div class="form-group col-md-12">
                        <label for="Hatarido" class="col-md-2 control-label">Kért szállítási határidő</label>
                        <div class="col-md-10">
                            <input id="Hatarido" type="text" class="form-control" value="{$hatarido}" name="hatarido">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiMod" class="col-md-2 control-label">Szállítási mód</label>
                        <div class="col-md-10">
                            <input id="SzallitasiMod" class="form-control" value="{$partnerszallitasimod}" data-id="{$partnerszallitasimodid}" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="FizetesMod" class="col-md-2 control-label">Fizetési mód</label>
                        <div class="col-md-10">
                            <input id="FizetesiMod" class="form-control" value="{$partnerfizetesimod}" disabled>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="checkout-sendorder"><a class="btn btn-red js-checkoutsendorder">Megrendelés elküldése</a></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>A rendelés tételei</h3>
                <table class="table table-bordered js-chktetellist">
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="checkout-sendorder"><a class="btn btn-red js-checkoutsendorder">Megrendelés elküldése</a></div>
            </div>
        </div>
    </div>
{/block}