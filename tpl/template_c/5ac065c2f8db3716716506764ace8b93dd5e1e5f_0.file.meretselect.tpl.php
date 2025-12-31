<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:00
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/meretselect.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c6c970d16_81657013',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5ac065c2f8db3716716506764ace8b93dd5e1e5f' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/meretselect.tpl',
      1 => 1765275620,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546c6c970d16_81657013 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="meret<?php echo $_smarty_tpl->tpl_vars['termekid']->value;?>
" class="pull-left gvaltozatcontainer">
    <div class="pull-left gvaltozatnev termekvaltozat"><?php echo t('Méret');?>
:</div>
    <div class="pull-left gvaltozatselect">
        <div class="option-selector size-selector" data-termek="<?php echo $_smarty_tpl->tpl_vars['termekid']->value;?>
">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['meretek']->value, '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                <div class="select-option <?php if (($_smarty_tpl->tpl_vars['_v']->value['keszlet'] <= 0)) {?> disabled<?php }?>" data-value="<?php echo $_smarty_tpl->tpl_vars['_v']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_v']->value['caption'];?>
</div>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
        <select class="js-meretvaltozatedit valtozatselect" data-termek="<?php echo $_smarty_tpl->tpl_vars['termekid']->value;?>
">
            <option value=""><?php echo t('Válasszon');?>
</option>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['meretek']->value, '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['_v']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['_v']->value['keszlet'] <= 0)) {?> disabled="disabled" class="piros"<?php }?>><?php echo $_smarty_tpl->tpl_vars['_v']->value['caption'];?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
    </div>
</div>
<?php }
}
