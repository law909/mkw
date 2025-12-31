<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:13
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkouttetellist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c79e758d2_72062540',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bbd1cff97c25b07434aaae93474dd698a306b4ee' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/checkouttetellist.tpl',
      1 => 1765835469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546c79e758d2_72062540 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('osszesen', 0);
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tetellista']->value, 'tetel');
$_smarty_tpl->tpl_vars['tetel']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tetel']->value) {
$_smarty_tpl->tpl_vars['tetel']->do_else = false;
?>
	<?php $_smarty_tpl->_assignInScope('osszesen', $_smarty_tpl->tpl_vars['osszesen']->value+$_smarty_tpl->tpl_vars['tetel']->value['bruttohuf']);?>
	<div class="checkout-order-list-item flex-tb clickable" data-href="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['link'];?>
">
		<div class="checkout-order-list-item__image">
			<img src="//shop.mugenrace.com/<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['tetel']->value['minikepurl'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['caption'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['caption'];?>
">
			<div class="checkout-order-list-item__quantity textaligncenter">
				<?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['mennyiseg'],0,'','');?>

			</div>
		</div>
		<div class="checkout-order-list-item__details">
			<div class="checkout-order-list-item__caption"><?php echo $_smarty_tpl->tpl_vars['tetel']->value['caption'];?>
</div>
			<div class="checkout-order-list-item__variants">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tetel']->value['valtozatok'], 'valtozat');
$_smarty_tpl->tpl_vars['valtozat']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['valtozat']->value) {
$_smarty_tpl->tpl_vars['valtozat']->do_else = false;
echo t($_smarty_tpl->tpl_vars['valtozat']->value['nev']);?>
: <?php echo $_smarty_tpl->tpl_vars['valtozat']->value['ertek'];?>
&nbsp;<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
			<div class="checkout-order-list-item__sku"><?php echo $_smarty_tpl->tpl_vars['tetel']->value['cikkszam'];?>
</div>
		</div>
		<div class="checkout-order-list-item__price">
						<div class="checkout-order-list-item__total-price">
				<?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>

			</div>
		</div>
	</div>

	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<div class="checkout-order-list__total flex-cb">
		<div class="checkout-order-list__total-label"><?php echo t('Összesen');?>
:</div>
		<div class="checkout-order-list__total-value"><?php echo number_format($_smarty_tpl->tpl_vars['osszesen']->value,0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</div>
</div>
<?php }
}
