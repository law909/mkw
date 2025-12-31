<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/minikosar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f888c071_05642262',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9e2eee9bc42849cf8ac8ff5d1cae32e2708708e3' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/minikosar.tpl',
      1 => 1767111837,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:kosartetellist.tpl' => 1,
  ),
),false)) {
function content_695466f888c071_05642262 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.count.php','function'=>'smarty_modifier_count',),));
?>
<div id="minikosar" class="side-cart__open pull-right" rel="nofollow">
    <?php if (($_smarty_tpl->tpl_vars['kosar']->value['termekdb'])) {?>
        <div class="mini-cart" data-empty="0">
            <span class="mini-cart__counter">
                <?php echo number_format($_smarty_tpl->tpl_vars['kosar']->value['termekdb'],0,',',' ');?>

            </span>
            <i class="icon cart white"></i>
                    </div>
            <?php } else { ?>
        <div class="mini-cart" data-empty="1">
            <i class="icon cart white"></i>
                    </div>
    <?php }?>
</div>

<div class="side-cart">
    <div class="side-cart__header">
        <h3><?php echo t('Kosár');?>
</h3>
        <i class="icon close side-cart__close icon__click"></i>
    </div>
    <div class="side-cart__body">
        <?php if (smarty_modifier_count($_smarty_tpl->tpl_vars['kosar']->value['tetellista']) > 0) {?>
            <?php $_smarty_tpl->_assignInScope('tetellista', $_smarty_tpl->tpl_vars['kosar']->value['tetellista']);?>
            <?php if ((count($_smarty_tpl->tpl_vars['tetellista']->value) > 0)) {?>
                <table class="cart-page__table table table-bordered">
                    <?php $_smarty_tpl->_subTemplateRender('file:kosartetellist.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                </table>
            <?php }?>
        <?php }?>
    </div>
    <div class="side-cart__footer">
        <div class="megrendelemcontainer flex-cb">
            <a href="<?php echo $_smarty_tpl->tpl_vars['prevuri']->value;?>
" class="button bordered okbtn"><?php echo t('Folytatom a vásárlást');?>
</a>
            <a href="<?php echo $_smarty_tpl->tpl_vars['showcheckoutlink']->value;?>
" rel="nofollow" class="button primary cartbtn pull-right">
                <i class="icon cart icon__click"></i>
                <?php echo t('Megrendelem');?>

            </a>
        </div>
    </div>
</div><?php }
}
