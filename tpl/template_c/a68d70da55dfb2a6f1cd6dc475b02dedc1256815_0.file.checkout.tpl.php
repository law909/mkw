<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:13
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c790a0f16_80545072',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a68d70da55dfb2a6f1cd6dc475b02dedc1256815' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkout.tpl',
      1 => 1766349436,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546c790a0f16_80545072 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_210884805769546c79082594_41998116', "stonebody");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "checkoutbase.tpl");
}
/* {block "stonebody"} */
class Block_210884805769546c79082594_41998116 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'stonebody' => 
  array (
    0 => 'Block_210884805769546c79082594_41998116',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <div class="container-full whitebg js-checkout">
        <div class="row">
            <div class="col flex-cc">

                <?php if (($_smarty_tpl->tpl_vars['checkouterrors']->value)) {?>
                    <div class="row">
                        <div class=" checkouterrorblock">
                            <div class="checkouterrorblockinner">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['checkouterrors']->value, '_ce');
$_smarty_tpl->tpl_vars['_ce']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_ce']->value) {
$_smarty_tpl->tpl_vars['_ce']->do_else = false;
?>
                                    <div class="checkouterror"><?php echo $_smarty_tpl->tpl_vars['_ce']->value;?>
</div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <form id="LoginForm" method="post" action="/login/ment"></form>
                <form id="CheckoutForm" class="checkout-form" action="/checkout/ment" method="post">
                    <fieldset>
                        <div class="row ">
                            <div class="col checkout-form__left flex-cr ">
                                <div class="checkout-form__content">
                                    <?php $_smarty_tpl->_assignInScope('sorszam', 1);?>
                                    <?php if ((!$_smarty_tpl->tpl_vars['user']->value['loggedin'])) {?>
                                        <div class="row">
                                            <div class="">
                                                <div class="form-header chk-datagroupheader js-chkdatagroupheader" data-container=".js-chklogin">
                                                    <h3 class="title-header">
                                                        <span><?php echo $_smarty_tpl->tpl_vars['sorszam']->value++;?>
. <?php echo t('Bejelentkezés');?>
</span>
                                                    </h3>
                                                </div>
                                                <div class="js-chklogin js-chkdatacontainer row chk-columncontainer">
                                                    <div class="">
                                                        <div class="chk-loginrightborder pull-left checkout-form__section">
                                                            <h5><?php echo t('Új vásárló');?>
</h5>
                                                            <div class="  flex-col gap-base">
                                                                <label class="radio">
                                                                    <input name="regkell" id="regkell" type="radio" value="1" <?php if (($_smarty_tpl->tpl_vars['regkell']->value == 1)) {?>checked="checked"<?php }?>>
                                                                    <?php echo t('Vásárlás vendégként (regisztráció nélkül)');?>

                                                                </label>
                                                                <label class="radio">
                                                                    <input name="regkell" id="regkell" type="radio" value="2" <?php if (($_smarty_tpl->tpl_vars['regkell']->value == 2)) {?>checked="checked"<?php }?>>
                                                                    <?php echo t('Vásárlás regisztrációval');?>

                                                                    • </label>
                                                            </div>
                                                            <div class="row chk-actionrow span"><a href="#block2" class="button bordered okbtn pull-right js-chkopenbtn"
                                                                                                data-datagroupheader=".js-chkszallitasiadatokgh"><?php echo t('Tovább');?>
</a></div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-col gap-base checkout-form__section">
                                                        <h5><?php echo t('Regisztrált vásárló');?>
</h5>
                                                        <?php if (($_smarty_tpl->tpl_vars['showerror']->value)) {?>
                                                            <h4><?php echo t('A bejelentkezés nem sikerült');?>
...</h4>
                                                        <?php }?>
                                                        <div class="row">
                                                            <div class="controls chk-controloffset">
                                                                <label class=" nomargin"><?php echo t('Email');?>
</label>
                                                                <input name="email" type="text" form="LoginForm" class=" nomargin" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['user']->value['email'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
                                                            </div>
                                                            <div class="controls chk-controloffset">
                                                                <label class=" nomargin"><?php echo t('Jelszó');?>
</label>
                                                                <input name="jelszo" type="password" form="LoginForm" class=" nomargin" value="">
                                                            </div>
                                                        </div>
                                                        <div class="row chk-actionrow span">
                                                            <input name="c" type="hidden" form="LoginForm" value="c">
                                                            <input type="submit" form="LoginForm" class="button bordered okbtn pull-right js-chkloginbtn" value="<?php echo t('Belépés');?>
">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <div class="row">
                                        <div class="">
                                            <div id="block2" class="form-header chk-datagroupheader js-chkdatagroupheader js-chkszallitasiadatokgh"
                                                data-container=".js-chkszallitasiadatok">
                                                <h3 class="title-header">
                                                    <span><?php echo $_smarty_tpl->tpl_vars['sorszam']->value++;?>
. <?php echo t('Szállítási és számlázási adatok');?>
<a class="button bordered small"><?php echo t('Módosít');?>
</a></span>
                                                </h3>
                                            </div>
                                            <div class="js-chkszallitasiadatok js-chkdatacontainer checkout-form__section">
                                                <h5><?php echo t('Kapcsolati adatok');?>
</h5>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin"><?php echo t('Vezetéknév');?>
 *</label>
                                                        <input name="vezeteknev" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['vezeteknev']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin"><?php echo t('Keresztnév');?>
 *</label>
                                                        <input name="keresztnev" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['keresztnev']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin chk-relative">
                                                        <label class=" nomargin"><?php echo t('Telefon');?>
 *</label>
                                                        <input name="telefon" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['telefon']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin"><?php echo t('Email');?>
 *</label>
                                                        <input name="kapcsemail" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['email']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            <?php if (($_smarty_tpl->tpl_vars['user']->value['loggedin'])) {?>readonly <?php }?> data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <?php if ((!$_smarty_tpl->tpl_vars['user']->value['loggedin'])) {?>
                                                    <div class="js-checkoutpasswordcontainer">
                                                        <div class="controls controls-row chk-controloffset js-checkoutpasswordrow">
                                                            <div class=" nomargin">
                                                                <label class=" nomargin"><?php echo t('Jelszó');?>
 1 *</label>
                                                                <input name="jelszo1" type="password" class=" nomargin" value=""
                                                                    data-container=".js-chkszallitasiadatok">
                                                            </div>
                                                            <div class=" chk-relative">
                                                                <label class=" nomargin"><?php echo t('Jelszó');?>
 2 *</label>
                                                                <input name="jelszo2" type="password" class=" nomargin" value=""
                                                                    data-container=".js-chkszallitasiadatok">
                                                                <i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn"
                                                                title="<?php echo t('Adja meg kétszer jelszavát, így elkerülheti az elgépelést');?>
"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                <h5><?php echo t('Szállítási adatok');?>
</h5>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin"><?php echo t('Szállítási ország');?>
 *</label>
                                                        <select name="szallorszag" class="js-chkrefresh" required="required">
                                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['szallorszaglist']->value, 'f');
$_smarty_tpl->tpl_vars['f']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->do_else = false;
?>
                                                                <option value="<?php echo $_smarty_tpl->tpl_vars['f']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['orszag']->value == $_smarty_tpl->tpl_vars['f']->value['id'])) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['f']->value['caption'];?>
</option>
                                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin"><?php echo t('Szállítási név');?>
</label>
                                                        <input name="szallnev" type="text" class=" js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szallnev']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls controls-row chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin"><?php echo t('Ir.szám');?>
 *</label>
                                                        <input name="szallirszam" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szallirszam']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="">
                                                        <label class=" nomargin"><?php echo t('Város');?>
 *</label>
                                                        <input name="szallvaros" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szallvaros']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <label class=" nomargin"><?php echo t('Utca');?>
 *</label>
                                                    <input name="szallutca" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szallutca']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                        data-container=".js-chkszallitasiadatok">
                                                </div>

                                                <h5 class="clearboth"><?php echo t('Számlázási adatok');?>
</h5>
                                                <div class="controls chk-controloffset">
                                                    <label class="checkbox">
                                                        <input name="szamlaeqszall" type="checkbox"<?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?> checked<?php }?>>
                                                        <?php echo t('Megegyezik a szállítási adatokkal');?>

                                                    </label>
                                                </div>
                                                <div class="controls chk-controloffset">
                                                    <div class=" nomargin">
                                                        <label class=" nomargin"><?php echo t('Számlázási ország');?>
 *</label>
                                                        <select name="orszag" class="js-chkrefresh" required="required">
                                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['szallorszaglist']->value, 'f');
$_smarty_tpl->tpl_vars['f']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->do_else = false;
?>
                                                                <option value="<?php echo $_smarty_tpl->tpl_vars['f']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['orszag']->value == $_smarty_tpl->tpl_vars['f']->value['id'])) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['f']->value['caption'];?>
</option>
                                                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="js-chkszamlaadatok<?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?> notvisible<?php }?>  flex-col gap-base">
                                                    <div class="controls chk-controloffset">
                                                        <div class=" nomargin">
                                                            <label class=" nomargin"><?php echo t('Számlázási név');?>
</label>
                                                            <input name="szamlanev" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szamlanev']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                                <?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>disabled <?php }?>data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                    </div>
                                                    <div class="controls controls-row chk-controloffset">
                                                        <div class=" nomargin">
                                                            <label class=" nomargin"><?php echo t('Ir.szám');?>
 *</label>
                                                            <input name="szamlairszam" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szamlairszam']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                                <?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>disabled <?php }?>data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                        <div class="">
                                                            <label class=" nomargin"><?php echo t('Város');?>
 *</label>
                                                            <input name="szamlavaros" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szamlavaros']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                                <?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>disabled <?php }?>data-container=".js-chkszallitasiadatok">
                                                        </div>
                                                    </div>
                                                    <div class="controls chk-controloffset">
                                                        <label class=" nomargin"><?php echo t('Utca');?>
 *</label>
                                                        <input name="szamlautca" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['szamlautca']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"
                                                            <?php if (((($tmp = $_smarty_tpl->tpl_vars['szamlaeqszall']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>disabled <?php }?>data-container=".js-chkszallitasiadatok">
                                                    </div>
                                                    <div class="controls controls-row chk-controloffset">
                                                        <div class=" nomargin chk-relative">
                                                            <label class=" nomargin"><?php echo t('Adószám');?>
</label>
                                                            <input name="adoszam" type="text" class=" nomargin js-chkrefresh" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['adoszam']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row chk-actionrow"><a href="#block3" class="button bordered okbtn pull-right js-chkopenbtn"
                                                                                data-datagroupheader=".js-chkszallmoddgh"><?php echo t('Tovább');?>
</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div id="block3" class="form-header chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh"
                                                data-container=".js-chkszallmod">
                                                <h3 class="title-header">
                                                    <span><?php echo $_smarty_tpl->tpl_vars['sorszam']->value++;?>
. <?php echo t('Szállítás és fizetés');?>
<a class="button bordered small"><?php echo t('Módosít');?>
</a></span>
                                                </h3>
                                            </div>
                                            <div class="js-chkszallmod js-chkdatacontainer  checkout-form__section">
                                                <div class="row flex-col gap-base">
                                                    <div class=""><label class="chk-controllabel bold"><?php echo t('Szállítási mód');?>
:</label></div>
                                                    <div class=" controls js-chkszallmodlist flex-col gap-base">
                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['szallitasimodlist']->value, 'szallitasimod');
$_smarty_tpl->tpl_vars['szallitasimod']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['szallitasimod']->value) {
$_smarty_tpl->tpl_vars['szallitasimod']->do_else = false;
?>
                                                            <label class="radio">
                                                                <input type="radio" name="szallitasimod"
                                                                    class="js-chkrefresh<?php if (($_smarty_tpl->tpl_vars['szallitasimod']->value['foxpost'])) {?> js-foxpostchk<?php }?>"
                                                                    value="<?php echo $_smarty_tpl->tpl_vars['szallitasimod']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['szallitasimod']->value['selected'])) {?> checked<?php }?>
                                                                    data-caption="<?php echo $_smarty_tpl->tpl_vars['szallitasimod']->value['caption'];?>
">
                                                                <?php echo $_smarty_tpl->tpl_vars['szallitasimod']->value['caption'];?>

                                                            </label>
                                                            <?php if (($_smarty_tpl->tpl_vars['szallitasimod']->value['leiras'])) {?>
                                                                <div class="chk-courierdesc folyoszoveg"><?php echo $_smarty_tpl->tpl_vars['szallitasimod']->value['leiras'];?>
</div>
                                                            <?php }?>
                                                            <?php if (($_smarty_tpl->tpl_vars['szallitasimod']->value['foxpost'])) {?>
                                                                <div class="js-foxpostterminalcontainer chk-foxpostcontainer"></div>
                                                            <?php }?>
                                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                    </div>
                                                    <div class=""><label class="chk-controllabel bold"><?php echo t('Fizetési mód');?>
:</label></div>
                                                    <div class=" controls js-chkfizmodlist  flex-col gap-base">
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col flex-col">
                                                        <label for="KuponEdit"><?php echo t('Kuponkód');?>
:</label>
                                                        <input id="KuponEdit" class="" type="text" name="kupon">
                                                        <div class="js-kuponszoveg"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col flex-col">
                                                        <label for="WebshopMessageEdit" class="bold"><?php echo t('Üzenet a webáruháznak');?>
:</label>
                                                        <textarea id="WebshopMessageEdit" class=" js-chkrefresh" name="webshopmessage" rows="2"><?php echo $_smarty_tpl->tpl_vars['webshopmessage']->value;?>
</textarea>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col flex-col">
                                                        <label for="CourierMessageEdit" class="bold"><?php echo t('Üzenet a futár részére');?>
:</label>
                                                        <textarea id="CourierMessageEdit" class=" js-chkrefresh" name="couriermessage" rows="2"><?php echo $_smarty_tpl->tpl_vars['couriermessage']->value;?>
</textarea>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="flex-col gap-base ">
                                                    <label class="checkbox">
                                                        <input name="akciohirlevel" type="checkbox">
                                                        <?php echo t('Igen, értesítsenek az akciókról');?>

                                                    </label>
                                                    <label class="checkbox">
                                                        <input name="ujdonsaghirlevel" type="checkbox">
                                                        <?php echo t('Igen, értesítsenek az újdonságokról');?>

                                                    </label>
                                                </div>
                                                <div class="flex-col gap-base ">
                                                    <div class="chk-savecontainer flex-col gap-base ">
                                                        <div>
                                                            <label class="checkbox">
                                                                <input name="aszfready" type="checkbox">
                                                                <?php if (($_smarty_tpl->tpl_vars['locale']->value === 'hu')) {?>
                                                                    Tudomásul veszem és elfogadom az
                                                                    <a href="<?php echo $_smarty_tpl->tpl_vars['showaszflink']->value;?>
" target="empty" class="js-chkaszf">ÁSZF</a>
                                                                    -et
                                                                    <br>
                                                                    és a rendeléssel járó fizetési kötelezettséget
                                                                <?php } elseif (($_smarty_tpl->tpl_vars['locale']->value === 'en_us')) {?>
                                                                    I have read and agree to the terms of the agreement.
                                                                <?php }?>
                                                            </label>
                                                        </div>
                                                        <div><input type="submit" class="button primary large full-width cartbtn chk-sendorderbtn js-chksendorderbtn"
                                                                    value="<?php echo t('Megrendelés elküldése');?>
"></div>
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
                                                    <span><?php echo $_smarty_tpl->tpl_vars['sorszam']->value++;?>
. <?php echo t('Megrendelés áttekintése');?>
</span>
                                                </h3>
                                            </div>
                                            <div class="js-chkattekintes js-chkdatacontainer">
                                                <div class="chk-columncontainer pull-left width100percent">
                                                    <div class="row">
                                                        <div class="col col30percent">
                                                            <div class="chk-colheader"><?php echo t('Számlázási adatok');?>
</div>
                                                            <div class="js-chkszamlanev"></div>
                                                            <div class="chk-coldatabottom js-chkadoszam"></div>
                                                            <div><span class="js-chkszamlairszam"></span>&nbsp;<span class="js-chkszamlavaros"></span></div>
                                                            <div class="js-chkszamlautca"></div>
                                                            <div class="chk-colheader"><?php echo t('Kapcsolati adatok');?>
</div>
                                                            <div><span class="js-chkvezeteknev"></span>&nbsp;<span class="js-chkkeresztnev"></span></div>
                                                            <div class="js-chktelefon"></div>
                                                            <div class="js-chkkapcsemail"></div>
                                                        </div>
                                                        <div class="col col30percent chk-colleftborder chk-colmargin">
                                                            <div class="chk-colheader"><?php echo t('Szállítási adatok');?>
</div>
                                                            <div class="chk-coldatabottom js-chkszallnev"></div>
                                                            <div><span class="js-chkorszag"></span></div>
                                                            <div><span class="js-chkszallirszam"></span>&nbsp;<span class="js-chkszallvaros"></span></div>
                                                            <div class="chk-coldatabottom js-chkszallutca"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col30percent chk-colleftborder chk-colmargin">
                                                        <div class="chk-colheader"><?php echo t('Szállítás és fizetés');?>
</div>
                                                        <div class="js-chkszallitasimod"></div>
                                                        <div class="js-chkfoxpostterminal"></div>
                                                        <div class="chk-coldatabottom js-chkfizmod"></div>
                                                        <div class="chk-coldatabottom folyoszoveg js-chkwebshopmessage"></div>
                                                        <div class="folyoszoveg js-chkcouriermessage"></div>
                                                    </div>
                                                </div>
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
<?php
}
}
/* {/block "stonebody"} */
}
