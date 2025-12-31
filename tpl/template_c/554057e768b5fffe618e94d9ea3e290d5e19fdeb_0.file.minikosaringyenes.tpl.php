<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:21:04
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/minikosaringyenes.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c70402dc9_80926316',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '554057e768b5fffe618e94d9ea3e290d5e19fdeb' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/minikosaringyenes.tpl',
      1 => 1764057952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546c70402dc9_80926316 (Smarty_Internal_Template $_smarty_tpl) {
if (($_smarty_tpl->tpl_vars['kosar']->value['megingyeneshez'] > 0)) {?>
    <div class="pull-right">Már csak <span class="bold"><?php echo number_format($_smarty_tpl->tpl_vars['kosar']->value['megingyeneshez'],0,',',' ');?>
</span> forintért kell vásárolnia az ingyenes szállításhoz!</div>
<?php }
}
}
