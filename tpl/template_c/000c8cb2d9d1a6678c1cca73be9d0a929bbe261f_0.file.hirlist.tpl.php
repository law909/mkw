<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:26:28
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/hirlist.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546db4c961f3_15781082',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '000c8cb2d9d1a6678c1cca73be9d0a929bbe261f' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/hirlist.tpl',
      1 => 1766139601,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546db4c961f3_15781082 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_33074831369546db4c649a3_20422324', "kozep");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "kozep"} */
class Block_33074831369546db4c649a3_20422324 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_33074831369546db4c649a3_20422324',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
?>

<div class="container page-header">
	<div class="row">
		<div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
				<span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
						<?php if (((($tmp = $_smarty_tpl->tpl_vars['navigator']->value ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
								<a href="/" rel="v:url" property="v:title">
										<?php echo t('Home');?>

								</a>
								<i class="icon arrow-right"></i>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['navigator']->value, '_navi');
$_smarty_tpl->tpl_vars['_navi']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_navi']->value) {
$_smarty_tpl->tpl_vars['_navi']->do_else = false;
?>
										<?php if (((($tmp = $_smarty_tpl->tpl_vars['_navi']->value['url'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
												<span typeof="v:Breadcrumb">
														<a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_navi']->value['url'];?>
" rel="v:url" property="v:title">
																<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_navi']->value['caption']);?>

														</a>
												</span>
												<i class="icon arrow-right"></i>
										<?php } else { ?>
												<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['_navi']->value['caption']);?>

										<?php }?>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
				</span>
		</div>
	</div>
		<div class="row">
				<div class="col">
					<h1 class="page-header__title" typeof="v:Breadcrumb">
							<a href="/hirek/" rel="v:url" property="v:title">
									<?php echo t('Hírek');?>

							</a>
					</h1>
				</div>
		</div>
</div>

<div class="container news-list">

	
	<div class="row">
		<div class="col news-list__items">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['children']->value, '_child');
$_smarty_tpl->tpl_vars['_child']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_child']->value) {
$_smarty_tpl->tpl_vars['_child']->do_else = false;
?>
				<div class="kat news-list__item" data-href="/hir/<?php echo $_smarty_tpl->tpl_vars['_child']->value['slug'];?>
">
					<div class="kattext news-list__item-content">
												<img src="https://picsum.photos/500/400" alt="<?php echo $_smarty_tpl->tpl_vars['_child']->value['cim'];?>
" class="news-list__item-image">
            <div class="hiralairas news-list__item-date"><?php echo $_smarty_tpl->tpl_vars['_child']->value['datum'];?>
</div>
						<div class="kattitle news-list__item-title"><a href="/hir/<?php echo $_smarty_tpl->tpl_vars['_child']->value['slug'];?>
"><?php echo $_smarty_tpl->tpl_vars['_child']->value['cim'];?>
</a></div>
						<div class="katcopy news-list__item-lead"><?php echo $_smarty_tpl->tpl_vars['_child']->value['lead'];?>
</div>
					</div>
				</div>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
	</div>
</div>
<?php
}
}
/* {/block "kozep"} */
}
