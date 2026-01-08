{extends "checkoutbase.tpl"}

{block "stonebody"}
    {* <header class="checkout">
        <div class="headermid whitebg">
            <div class="container">
                <div class="row">
                    <div class="">
                        <a href="/"><img src="{$imagepath}{$mugenracelogo}" alt="Mugenrace webshop" title="Mugenrace webshop"></a>
                    </div>
                </div>
            </div>
        </div>
    </header> *}
    <div class="container-full whitebg js-checkout">
        <div class="row">
            <div class="col flex-cc">

                {if ($checkouterrors)}
                    <div class="row">
                        <div class=" checkouterrorblock">
                            <div class="checkouterrorblockinner">
                                {foreach $checkouterrors as $_ce}
                                    <div class="checkouterror">{$_ce}</div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                {/if}
                <form id="LoginForm" method="post" action="/login/ment"></form>
                <form id="CheckoutForm" class="checkout-form" action="/checkout/ment" method="post">
                    <fieldset>
                        <div class="row ">
                            <div class="col checkout-form__left flex-cr ">
                                <div class="checkout-form__content">
                                    {$sorszam=1}
                                    {if (!$user.loggedin)}
                                        <div class="row">
                                            <div class="">
                                                <div class="form-header chk-datagroupheader js-chkdatagroupheader" data-container=".js-chklogin">
                                                    <h3 class="title-header">
                                                        <span>{$sorszam++}. {t('Bejelentkezés')}</span>
                                                    </h3>
                                                </div>
                                                <div class="js-chklogin js-chkdatacontainer row chk-columncontainer">
                                                    <div class="">
                                                        <div class="chk-loginrightborder pull-left checkout-form__section">
                                                            <h5>{t('Új vásárló')}</h5>
                                                            <div class="  flex-col gap-base">
                                                                <label class="radio">
                                                                    <input name="regkell" id="regkell" type="radio" value="1" {if ($regkell==1)}checked="checked"{/if}>
                                                                    {t('Vásárlás vendégként (regisztráció nélkül)')}
                                                                </label>
                                                                <label class="radio">
                                                                    <input name="regkell" id="regkell" type="radio" value="2" {if ($regkell==2)}checked="checked"{/if}>
                                                                    {t('Vásárlás regisztrációval')}
                                                                    • </label>
                                                            </div>
                                                            <div class="row chk-actionrow span"><a href="#block2" class="button bordered okbtn pull-right js-chkopenbtn"
                                                                                                data-datagroupheader=".js-chkszallitasiadatokgh">{t('Tovább')}</a></div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-col gap-base checkout-form__section">
                                                        <h5>{t('Regisztrált vásárló')}</h5>
                                                        {if ($showerror)}
                                                            <h4>{t('A bejelentkezés nem sikerült')}...</h4>
                                                        {/if}
                                                        <div class="row">
                                                            <div class="controls chk-controloffset">
                                                                <label class=" nomargin">{t('Email')}</label>
                                                                <input name="email" type="text" form="LoginForm" class=" nomargin" value="{$user.email|default}">
                                                            </div>
                                                            <div class="controls chk-controloffset">
                                                                <label class=" nomargin">{t('Jelszó')}</label>
                                                                <input name="jelszo" type="password" form="LoginForm" class=" nomargin" value="">
                                                            </div>
                                                        </div>
                                                        <div class="row chk-actionrow span">
                                                            <input name="c" type="hidden" form="LoginForm" value="c">
                                                            <input type="submit" form="LoginForm" class="button bordered okbtn pull-right js-chkloginbtn" value="{t('Belépés')}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="row">
                                        <div class="">
                                            <div id="block2" class="form-header chk-datagroupheader js-chkdatagroupheader js-chkszallitasiadatokgh"
                                                data-container=".js-chkszallitasiadatok">
                                                <h3 class="title-header">
                                                    <span>{$sorszam++}. {t('Szállítási és számlázási adatok')}<a class="button bordered small">{t('Módosít')}</a></span>
                                                </h3>
                                            </div>
                                            <div class="js-chkszallitasiadatok js-chkdatacontainer checkout-form__section">
                                                <h5>{t('Kapcsolati adatok')}</h5>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin">{t('Vezetéknév')} *</label>
                                                        <input name="vezeteknev" type="text" class=" nomargin js-chkrefresh" value="{$vezeteknev|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin">{t('Keresztnév')} *</label>
                                                        <input name="keresztnev" type="text" class=" nomargin js-chkrefresh" value="{$keresztnev|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin chk-relative">
                                                        <label class=" nomargin">{t('Telefon')} *</label>
                                                        <input name="telefon" type="text" class=" nomargin js-chkrefresh" value="{$telefon|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin">{t('Email')} *</label>
                                                        <input name="kapcsemail" type="text" class=" nomargin js-chkrefresh" value="{$email|default}"
                                                            {if ($user.loggedin)}readonly {/if} data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                {if (!$user.loggedin)}
                                                    <div class="js-checkoutpasswordcontainer">
                                                        <div class="controls controls-row chk-controloffset js-checkoutpasswordrow">
                                                            <div class=" nomargin">
                                                                <label class=" nomargin">{t('Jelszó')} 1 *</label>
                                                                <input name="jelszo1" type="password" class=" nomargin" value=""
                                                                    data-container=".js-chkszallitasiadatok">
                                                            </div>
                                                            <div class=" chk-relative">
                                                                <label class=" nomargin">{t('Jelszó')} 2 *</label>
                                                                <input name="jelszo2" type="password" class=" nomargin" value=""
                                                                    data-container=".js-chkszallitasiadatok">
                                                                <i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn"
                                                                title="{t('Adja meg kétszer jelszavát, így elkerülheti az elgépelést')}"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {/if}
                                                <h5>{t('Szállítási adatok')}</h5>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin">{t('Szállítási ország')} *</label>
                                                        <select name="szallorszag" class="js-chkrefresh" required="required">
                                                            {foreach $szallorszaglist as $f}
                                                                <option value="{$f.id}"{if ($orszag == $f.id)} selected="selected"{/if}>{$f.caption}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin">{t('Szállítási név')}</label>
                                                        <input name="szallnev" type="text" class=" js-chkrefresh" value="{$szallnev|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin">{t('Ir.szám')} *</label>
                                                        <input name="szallirszam" type="text" class=" nomargin js-chkrefresh" value="{$szallirszam|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin">{t('Város')} *</label>
                                                        <input name="szallvaros" type="text" class=" nomargin js-chkrefresh" value="{$szallvaros|default}"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <label class=" nomargin">{t('Utca')} *</label>
                                                    <input name="szallutca" type="text" class=" nomargin js-chkrefresh" value="{$szallutca|default}"
                                                        data-container=".js-chkszallitasiadatok">
                                                </div>

                                                <h5 class="clearboth">{t('Számlázási adatok')}</h5>
                                                <div class="controls chk-controloffset">
                                                    <label class="checkbox">
                                                        <input name="szamlaeqszall" type="checkbox"{if ($szamlaeqszall|default)} checked{/if}>
                                                        {t('Megegyezik a szállítási adatokkal')}
                                                    </label>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin">{t('Számlázási ország')} *</label>
                                                        <select name="orszag" class="js-chkrefresh" required="required">
                                                            {foreach $szallorszaglist as $f}
                                                                <option value="{$f.id}"{if ($orszag == $f.id)} selected="selected"{/if}>{$f.caption}</option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="js-chkszamlaadatok{if ($szamlaeqszall|default)} notvisible{/if}  flex-col gap-base">
                                                    <div class="controls chk-controloffset">
                                                        <div class=" nomargin">
                                                            <label class=" nomargin">{t('Számlázási név')}</label>
                                                            <input name="szamlanev" type="text" class=" nomargin js-chkrefresh" value="{$szamlanev|default}"
                                                                {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                    </div>
                                                    <div class="controls controls-row chk-controloffset">
                                                        <div class=" nomargin">
                                                            <label class=" nomargin">{t('Ir.szám')} *</label>
                                                            <input name="szamlairszam" type="text" class=" nomargin js-chkrefresh" value="{$szamlairszam|default}"
                                                                {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                        <div class="">
                                                            <label class=" nomargin">{t('Város')} *</label>
                                                            <input name="szamlavaros" type="text" class=" nomargin js-chkrefresh" value="{$szamlavaros|default}"
                                                                {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                    </div>
                                                    <div class="controls chk-controloffset">
                                                        <label class=" nomargin">{t('Utca')} *</label>
                                                        <input name="szamlautca" type="text" class=" nomargin js-chkrefresh" value="{$szamlautca|default}"
                                                            {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="controls controls-row chk-controloffset">
                                                        <div class=" nomargin chk-relative">
                                                            <label class=" nomargin">{t('Adószám')}</label>
                                                            <input name="adoszam" type="text" class=" nomargin js-chkrefresh" value="{$adoszam|default}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row chk-actionrow"><a href="#block3" class="button bordered okbtn pull-right js-chkopenbtn"
                                                                                data-datagroupheader=".js-chkszallmoddgh">{t('Tovább')}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div id="block3" class="form-header chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh"
                                                data-container=".js-chkszallmod">
                                                <h3 class="title-header">
                                                    <span>{$sorszam++}. {t('Szállítás és fizetés')}<a class="button bordered small">{t('Módosít')}</a></span>
                                                </h3>
                                            </div>
                                            <div class="js-chkszallmod js-chkdatacontainer  checkout-form__section">
                                                <div class="row flex-col gap-base">
                                                    <div class=""><label class="chk-controllabel bold">{t('Szállítási mód')}:</label></div>
                                                    <div class=" controls js-chkszallmodlist flex-col gap-base">
                                                        {foreach $szallitasimodlist as $szallitasimod}
                                                            <label class="radio">
                                                                <input type="radio" name="szallitasimod"
                                                                    class="js-chkrefresh{if ($szallitasimod.foxpost)} js-foxpostchk{/if}"
                                                                    value="{$szallitasimod.id}"{if ($szallitasimod.selected)} checked{/if}
                                                                    data-caption="{$szallitasimod.caption}">
                                                                {$szallitasimod.caption}
                                                            </label>
                                                            {if ($szallitasimod.leiras)}
                                                                <div class="chk-courierdesc folyoszoveg">{$szallitasimod.leiras}</div>
                                                            {/if}
                                                            {if ($szallitasimod.foxpost)}
                                                                <div class="js-foxpostterminalcontainer chk-foxpostcontainer"></div>
                                                            {/if}
                                                        {/foreach}
                                                    </div>
                                                    <div class=""><label class="chk-controllabel bold">{t('Fizetési mód')}:</label></div>
                                                    <div class=" controls js-chkfizmodlist  flex-col gap-base">
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col flex-col">
                                                        <label for="KuponEdit">{t('Kuponkód')}:</label>
                                                        <input id="KuponEdit" class="" type="text" name="kupon">
                                                        <div class="js-kuponszoveg"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col flex-col">
                                                        <label for="WebshopMessageEdit" class="bold">{t('Üzenet a webáruháznak')}:</label>
                                                        <textarea id="WebshopMessageEdit" class=" js-chkrefresh" name="webshopmessage" rows="2">{$webshopmessage}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col flex-col">
                                                        <label for="CourierMessageEdit" class="bold">{t('Üzenet a futár részére')}:</label>
                                                        <textarea id="CourierMessageEdit" class=" js-chkrefresh" name="couriermessage" rows="2">{$couriermessage}</textarea>
                                                    </div>
                                                </div>
                                                
                                                {* <div class="row chk-actionrow"><a href="#block4" class="button bordered okbtn pull-right js-chkopenbtn"
                                                                                data-datagroupheader=".js-chkattekintesdgh">{t('Tovább')}</a></div> *}

                                                <div class="flex-col gap-base ">
                                                    <label class="checkbox">
                                                        <input name="akciohirlevel" type="checkbox">
                                                        {t('Igen, értesítsenek az akciókról')}
                                                    </label>
                                                    <label class="checkbox">
                                                        <input name="ujdonsaghirlevel" type="checkbox">
                                                        {t('Igen, értesítsenek az újdonságokról')}
                                                    </label>
                                                </div>
                                                <div class="flex-col gap-base ">
                                                    <div class="chk-savecontainer flex-col gap-base ">
                                                        <div>
                                                            <label class="checkbox">
                                                                <input name="aszfready" type="checkbox">
                                                                {if ($locale === 'hu')}
                                                                    Tudomásul veszem és elfogadom az
                                                                    <a href="{$showaszflink}" target="empty" class="js-chkaszf">ÁSZF</a>
                                                                    -et
                                                                    <br>
                                                                    és a rendeléssel járó fizetési kötelezettséget
                                                                {elseif ($locale === 'en_us')}
                                                                    I have read and agree to the terms of the agreement.
                                                                {/if}
                                                            </label>
                                                        </div>
                                                        <div><input type="submit" class="button primary large full-width cartbtn chk-sendorderbtn js-chksendorderbtn"
                                                                    value="{t('Megrendelés elküldése')}"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col checkout-form__right ">
                                <div class="checkout-form__summary">
                                    <div class="row">
                                        <div class="">
                                            <div id="block4" class="form-header chk-datagroupheader js-chkdatagroupheader js-chkattekintesdgh"
                                                data-container=".js-chkattekintes">
                                                <h3 class="title-header">
                                                    <span>{$sorszam++}. {t('Megrendelés áttekintése')}</span>
                                                </h3>
                                            </div>
                                            <div class="js-chkattekintes js-chkdatacontainer">
                                                <div class="chk-columncontainer pull-left width100percent">
                                                    <div class="row">
                                                        <div class="col col30percent">
                                                            <div class="chk-colheader">{t('Számlázási adatok')}</div>
                                                            <div class="js-chkszamlanev"></div>
                                                            <div class="chk-coldatabottom js-chkadoszam"></div>
                                                            <div><span class="js-chkszamlairszam"></span>&nbsp;<span class="js-chkszamlavaros"></span></div>
                                                            <div class="js-chkszamlautca"></div>
                                                            <div class="chk-colheader">{t('Kapcsolati adatok')}</div>
                                                            <div><span class="js-chkvezeteknev"></span>&nbsp;<span class="js-chkkeresztnev"></span></div>
                                                            <div class="js-chktelefon"></div>
                                                            <div class="js-chkkapcsemail"></div>
                                                        </div>
                                                        <div class="col col30percent chk-colleftborder chk-colmargin">
                                                            <div class="chk-colheader">{t('Szállítási adatok')}</div>
                                                            <div class="chk-coldatabottom js-chkszallnev"></div>
                                                            <div><span class="js-chkorszag"></span></div>
                                                            <div><span class="js-chkszallirszam"></span>&nbsp;<span class="js-chkszallvaros"></span></div>
                                                            <div class="chk-coldatabottom js-chkszallutca"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col30percent chk-colleftborder chk-colmargin">
                                                        <div class="chk-colheader">{t('Szállítás és fizetés')}</div>
                                                        <div class="js-chkszallitasimod"></div>
                                                        <div class="js-chkfoxpostterminal"></div>
                                                        <div class="chk-coldatabottom js-chkfizmod"></div>
                                                        <div class="chk-coldatabottom folyoszoveg js-chkwebshopmessage"></div>
                                                        <div class="folyoszoveg js-chkcouriermessage"></div>
                                                    </div>
                                                </div>
                                                {* <table class="table table-bordered js-chktetellist">
                                                </table> *}
                                                <div class="js-chktetellist checkout-order-list flex-col">
                                                </div>

                                                
                                            </div>
                                        </div>
                                    </div>                        
                                </div>                        
                            </div>
                        </div>

                        
                        
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
{/block}