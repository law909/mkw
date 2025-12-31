<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/kosartetellist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f8899ff9_15536928',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca66d05ba9baa3e9e10a2e67efdee83e9c97eee7' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/kosartetellist.tpl',
      1 => 1766444691,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_695466f8899ff9_15536928 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
?>
<thead>
	<tr>
		<th><div class="textaligncenter"><?php echo t('Termék');?>
</div></th>
		<th><?php echo t('Megnevezés, cikkszám, változat');?>
</th>
		<th><div class="textalignright"><?php echo t('Egységár');?>
</div></th>
		<th><div class="textaligncenter"><?php echo t('Mennyiség');?>
<i class="icon-question-sign cartheader-tooltipbtn hidden-phone js-tooltipbtn" title="<?php echo t('A mennyiség módosításához adja meg a kívánt mennyiséget, majd nyomja meg az Enter-t');?>
"></i></div></th>
		<th><div class="textalignright"><?php echo t('Érték');?>
</div></th>
		<th></th>
	</tr>
</thead>
<tbody>
	<?php $_smarty_tpl->_assignInScope('osszesen', 0);?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tetellista']->value, 'tetel');
$_smarty_tpl->tpl_vars['tetel']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tetel']->value) {
$_smarty_tpl->tpl_vars['tetel']->do_else = false;
?>
		<?php $_smarty_tpl->_assignInScope('osszesen', $_smarty_tpl->tpl_vars['osszesen']->value+$_smarty_tpl->tpl_vars['tetel']->value['bruttohuf']);?>
		<tr class="clickable cart-item" data-href="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['link'];?>
">
			<td><div class="textaligncenter">
                    <?php if (($_smarty_tpl->tpl_vars['tetel']->value['noedit'])) {?>
                    <img class="cart-item__image" src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['tetel']->value['kiskepurl'];?>
" alt="<?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>
" title="<?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>
">
                    <?php } else { ?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['link'];?>
"><img class="cart-item__image" src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['tetel']->value['kiskepurl'];?>
" alt="<?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>
" title="<?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>
"></a>
                    <?php }?>
                </div></td>
			<td><div>
                    <?php if (($_smarty_tpl->tpl_vars['tetel']->value['noedit'])) {?>
                    <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>

                    <?php } else { ?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['link'];?>
" class="cart-item__caption"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['tetel']->value['caption'], 'UTF-8'));?>
</a>
                    <?php }?>
                </div>
				<div class="cart-item__variants"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tetel']->value['valtozatok'], 'valtozat');
$_smarty_tpl->tpl_vars['valtozat']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['valtozat']->value) {
$_smarty_tpl->tpl_vars['valtozat']->do_else = false;
echo t($_smarty_tpl->tpl_vars['valtozat']->value['nev']);?>
: <?php echo $_smarty_tpl->tpl_vars['valtozat']->value['ertek'];?>
&nbsp;<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div>
				<div class="cart-item__sku">
					<?php echo $_smarty_tpl->tpl_vars['tetel']->value['cikkszam'];?>

				</div>
				<div class="textalignright cart-item__itemprice"><?php echo t('Ár');?>
 <?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['bruttoegysarhuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</div>
			</td>
			<td><div class="textalignright cart-item__itemprice"><?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['bruttoegysarhuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</div></td>
			<td>
				<div class="textaligncenter">
					<form class="kosarform" action="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['editlink'];?>
">
						<div><?php if (($_smarty_tpl->tpl_vars['tetel']->value['noedit'])) {?>
								<?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['mennyiseg'],0,'','');?>

								<?php } else { ?>
								<input id="mennyedit_<?php echo $_smarty_tpl->tpl_vars['tetel']->value['id'];?>
" class="cart-item__quantity" type="number" min="1" step="any" name="mennyiseg" value="<?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['mennyiseg'],0,'','');?>
" data-org="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['mennyiseg'];?>
">
								<?php }?>
						</div>
						<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['tetel']->value['id'];?>
">
					</form>
				</div>
			</td>
			<td><div id="ertek_<?php echo $_smarty_tpl->tpl_vars['tetel']->value['id'];?>
" class="textalignright"><?php echo number_format($_smarty_tpl->tpl_vars['tetel']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</div></td>
			<td><?php if ((!$_smarty_tpl->tpl_vars['tetel']->value['noedit'])) {?><div class="flex-cr "><a class="button bordered js-kosardelbtn" href="/kosar/del?id=<?php echo $_smarty_tpl->tpl_vars['tetel']->value['id'];?>
" rel="nofollow"><i class="icon trash icon__click"></i><span><?php echo t('Töröl');?>
</span></a></div><?php }?></td>
		</tr>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</tbody>
<tfoot>
	<tr>
		<th colspan="4"><div class="textalignright"><?php echo t('Összesen');?>
:</div></th>
		<th><div id="kosarsum" class="textalignright"><?php echo number_format($_smarty_tpl->tpl_vars['osszesen']->value,0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['valutanemnev']->value;?>
</div></th>
		<th></th>
	</tr>
</tfoot>
<?php }
}
