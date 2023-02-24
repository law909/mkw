{extends "basestone.tpl"}

{block "script"}
    <script src="/js/main/superzoneb2b/checkout.js"></script>
{/block}

{block "bodyclass"}class="body"{/block}

{block "stonebody"}
    <div class="container content-back checkout-content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a href="/"><img src="{$imagepath}{$logo}"></a><span class="checkout-header">Checkout</span>
            </div>
        </div>
        <div class="row">
            <form id="CheckoutForm" class="col-md-8 col-md-offset-2 form-horizontal" action="/checkout/ment" method="post">
                <fieldset>
                    <h3>Billing address</h3>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiNevEdit" class="col-md-2 control-label">Name</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiNevEdit" type="text" class="form-control" name="szamlanev" value="{$szamlanev|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiUtcaEdit" class="col-md-2 control-label">Address</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiUtcaEdit" type="text" class="form-control" name="szamlautca" value="{$szamlautca|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiVarosEdit" class="col-md-2 control-label">City</label>
                        <div class="col-md-10">
                            <input id="SzamlazasiVarosEdit" type="text" class="form-control" name="szamlavaros" value="{$szamlavaros|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzamlazasiIrszamEdit" class="col-md-2 control-label">Postal code</label>
                        <div class="col-md-2">
                            <input id="SzamlazasiIrszamEdit" type="text" class="form-control" name="szamlairszam" value="{$szamlairszam|default}">
                        </div>
                    </div>

                    <h3>Delivery address</h3>
                    <div class="form-group col-md-12">
                        <label for="SzalleqszamlaEdit" class="col-md-2 control-label">Same as the billing address</label>
                        <div class="col-md-10">
                            <input id="SzalleqszamlaEdit" type="checkbox" class="form-checkbox" name="szalleqszamla"{if ($szalleqszamla|default)} checked{/if}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiNevEdit" class="col-md-2 control-label">Name</label>
                        <div class="col-md-10">
                            <input id="SzallitasiNevEdit" type="text" class="form-control" name="szallnev" value="{$szallnev|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiUtcaEdit" class="col-md-2 control-label">Address</label>
                        <div class="col-md-10">
                            <input id="SzallitasiUtcaEdit" type="text" class="form-control" name="szallutca" value="{$szallutca|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiVarosEdit" class="col-md-2 control-label">City</label>
                        <div class="col-md-10">
                            <input id="SzallitasiVarosEdit" type="text" class="form-control" name="szallvaros" value="{$szallvaros|default}">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiIrszamEdit" class="col-md-2 control-label">Postal code</label>
                        <div class="col-md-2">
                            <input id="SzallitasiIrszamEdit" type="text" class="form-control" name="szallirszam" value="{$szallirszam|default}">
                        </div>
                    </div>

                    <h3>Comments</h3>
                    <div class="form-group col-md-12">
                        <label for="MegjegyzesEdit" class="col-md-2 control-label">Comment for the shop</label>
                        <div class="col-md-10">
                            <textarea id="MegjegyzesEdit" class="form-control" name="webshopmessage" type="text" rows="2">{$webshopmessage}</textarea>
                        </div>
                    </div>

                    <h3>Shipping and payment</h3>
                    <div class="form-group col-md-12">
                        <label for="Hatarido" class="col-md-2 control-label">Requested delivery date</label>
                        <div class="col-md-10">
                            <input id="Hatarido" type="text" class="form-control" value="{$hatarido}" name="hatarido">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="SzallitasiMod" class="col-md-2 control-label">Shipping method</label>
                        <div class="col-md-10">
                            <input id="SzallitasiMod" class="form-control" value="{$partnerszallitasimod}" data-id="{$partnerszallitasimodid}" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="FizetesMod" class="col-md-2 control-label">Payment</label>
                        <div class="col-md-10">
                            <input id="FizetesiMod" class="form-control" value="{$partnerfizetesimod}" disabled>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="checkout-sendorder"><a class="btn btn-red js-checkoutsendorder">Send order</a></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>Your items</h3>
                <table class="table table-bordered js-chktetellist">
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="checkout-sendorder"><a class="btn btn-red js-checkoutsendorder">Send order</a></div>
            </div>
        </div>
    </div>
{/block}