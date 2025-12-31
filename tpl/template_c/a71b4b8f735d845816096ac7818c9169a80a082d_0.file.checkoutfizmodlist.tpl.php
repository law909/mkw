<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:14
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkoutfizmodlist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c7a0d1f96_09847037',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a71b4b8f735d845816096ac7818c9169a80a082d' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkoutfizmodlist.tpl',
      1 => 1764057952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546c7a0d1f96_09847037 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['fizmodlist']->value, 'fizmod');
$_smarty_tpl->tpl_vars['fizmod']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['fizmod']->value) {
$_smarty_tpl->tpl_vars['fizmod']->do_else = false;
?>
<label class="radio">
	<input type="radio" name="fizetesimod" class="js-chkrefresh" value="<?php echo $_smarty_tpl->tpl_vars['fizmod']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['fizmod']->value['selected'])) {?> checked<?php }?> data-caption="<?php echo $_smarty_tpl->tpl_vars['fizmod']->value['caption'];?>
">
	<?php echo $_smarty_tpl->tpl_vars['fizmod']->value['caption'];?>

</label>
<?php if (($_smarty_tpl->tpl_vars['fizmod']->value['leiras'])) {?>
<div class="chk-courierdesc folyoszoveg"><?php echo $_smarty_tpl->tpl_vars['fizmod']->value['leiras'];?>
</div>
<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
