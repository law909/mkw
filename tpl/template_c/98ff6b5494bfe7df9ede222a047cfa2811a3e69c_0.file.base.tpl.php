<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/base.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f883c4e4_85641990',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '98ff6b5494bfe7df9ede222a047cfa2811a3e69c' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/base.tpl',
      1 => 1764839164,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_695466f883c4e4_85641990 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2094006404695466f883b917_50288344', "stonebody");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "basestone.tpl");
}
/* {block "kozep"} */
class Block_135198652695466f883be37_25979053 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php
}
}
/* {/block "kozep"} */
/* {block "stonebody"} */
class Block_2094006404695466f883b917_50288344 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'stonebody' => 
  array (
    0 => 'Block_2094006404695466f883b917_50288344',
  ),
  'kozep' => 
  array (
    0 => 'Block_135198652695466f883be37_25979053',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<header>
	<?php $_smarty_tpl->_subTemplateRender('file:header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</header>
<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_135198652695466f883be37_25979053', "kozep", $this->tplIndex);
?>

<footer>
	<?php $_smarty_tpl->_subTemplateRender('file:footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
</footer>
<?php
}
}
/* {/block "stonebody"} */
}
