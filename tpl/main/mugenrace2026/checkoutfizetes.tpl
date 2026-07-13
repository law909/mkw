{extends "checkoutbase.tpl"}
{block "stonebody"}
    <header class="checkout">
        <div class="headermid whitebg">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <a href="/"><img src="/themes/main/mugenrace2026/img/colorlogo.png" alt="Mugenrace webshop" title="Mugenrace webshop"></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container whitebg js-checkout">
        <div class="row">
            <div class="span10">
                {if ($checkouterrors)}
                    <div class="row">
                        <div class="span10 checkouterrorblock">
                            <div class="checkouterrorblockinner">
                                {foreach $checkouterrors as $_ce}
                                    <div class="checkouterror">{$_ce}</div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                {/if}
                {if $isStripe && $stripeClientSecret}
                    <div class="row">
                        <div class="span10">
                            <div id="block1" class="chk-datagroupheader js-chkdatagroupheader js-chkfizetesiadatokgh"
                                 data-container=".js-chkfizetesiadatok">{$fizmodnev} {t('adatok')}</div>
                            <div class="js-chkfizetesiadatok js-chkdatacontainer">
                                <h5>{t('Adja meg fizetési adatait, és nyomja meg a Fizetés gombot.')}</h5>
                                <div>{t('Megrendelés szám')}: <span class="bold">{$megrendelesszam}</span></div>
                                <div>{t('Fizetendő')}: <span class="bold">{number_format($fizetendo,0,',',' ')} {$valutanemnev}</span></div>
                                <div class="controls controls-row chk-controloffset">
                                    <div class="span8 nomargin" style="margin-top:20px;">
                                        <div id="stripe-payment-element"></div>
                                        <div id="stripe-payment-errors" role="alert" style="color:#dc3545;margin-top:10px;"></div>
                                    </div>
                                </div>
                                <div class="pull-right" style="margin-top:20px;">
                                    <div class="chk-savecontainer">
                                        <button id="stripe-submit-btn" class="cartbtn chk-sendorderbtn" type="button">{t('Fizetés')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {else}
                    <form id="CheckoutPayForm" class="" action="/checkout/pay/ment" method="post">
                        <fieldset>
                            <div class="row">
                                <div class="span10">
                                    <div id="block1" class="chk-datagroupheader js-chkdatagroupheader js-chkfizetesiadatokgh"
                                         data-container=".js-chkfizetesiadatok">{$fizmodnev} {t('adatok')}</div>
                                    <div class="js-chkfizetesiadatok js-chkdatacontainer">
                                        <h5>Adja meg fizetési adatait, és nyomja meg a Fizetés gombot.</h5>
                                        <div>{t('Megrendelés szám')}: <span class="bold">{$megrendelesszam}</span></div>
                                        <div>{t('Fizetendő')}: <span class="bold">{number_format($fizetendo,0,',',' ')} {$valutanemnev}</span></div>
                                        <div class="controls controls-row chk-controloffset">
                                            <div class="span4 nomargin">
                                                <label class="span4 nomargin">Mobil telefonszám</label>
                                                <input name="mobilszam" type="text" class="span4 nomargin js-chkrefresh" value="{$mobilszam|default}"
                                                       data-container=".js-chkfizetesiadatok">
                                            </div>
                                            <div class="span4">
                                                <label class="span4 nomargin">Mobil fizetési azonosító</label>
                                                <input name="fizazon" type="text" class="span4 nomargin js-chkrefresh" value="{$keresztnev|default}"
                                                       data-container=".js-chkfizetesiadatok">
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="chk-savecontainer">
                                                <input name="megrendelesszam" type="hidden" value="{$megrendelesszam}">
                                                <div><input type="submit" class="btn cartbtn chk-sendorderbtn" value="Fizetés"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                {/if}
            </div>
        </div>
    </div>
    {if $isStripe && $stripeClientSecret}
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            (function () {
                let stripe = Stripe('{$stripePublishableKey}');
                let elements = stripe.elements({
                    clientSecret: '{$stripeClientSecret}',
                    appearance: {
                        theme: 'stripe'
                    }
                });
                let paymentElement = elements.create('payment');
                paymentElement.mount('#stripe-payment-element');

                let submitBtn = document.getElementById('stripe-submit-btn');
                let errorDiv = document.getElementById('stripe-payment-errors');

                submitBtn.addEventListener('click', async function () {
                    submitBtn.disabled = true;
                    submitBtn.textContent = '{t("Feldolgozás...")}';
                    errorDiv.textContent = '';

                    let result = await stripe.confirmPayment({
                        elements: elements,
                        confirmParams: {
                            return_url: window.location.origin + '/stripe/success'
                        }
                    });

                    if (result.error) {
                        errorDiv.textContent = result.error.message;
                        submitBtn.disabled = false;
                        submitBtn.textContent = '{t("Fizetés")}';
                    }
                });
            })();
        </script>
    {/if}
{/block}
