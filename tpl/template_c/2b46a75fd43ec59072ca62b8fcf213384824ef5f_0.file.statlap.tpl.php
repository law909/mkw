<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:29:24
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/statlap.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546e645c9484_96580942',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b46a75fd43ec59072ca62b8fcf213384824ef5f' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/statlap.tpl',
      1 => 1767140957,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546e645c9484_96580942 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_81932406469546e645bc861_17313218', "kozep");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "kozep"} */
class Block_81932406469546e645bc861_17313218 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_81932406469546e645bc861_17313218',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
?>

<div class="container whitebg static-page">
	<article itemtype="http://schema.org/Article" itemscope="">

			<div class="container page-header static-page__header">
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
										<?php if (((isset($_smarty_tpl->tpl_vars['_navi']->value['url'])))) {?>
											<a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_navi']->value['url'];?>
" rel="v:url" property="v:title">
													<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['statlap']->value['oldalcim']);?>

											</a>
										<?php } else { ?>
											<a href="#" rel="v:url" property="v:title">
													<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['statlap']->value['oldalcim']);?>

											</a>
										<?php }?>
								</h1>
							</div>
					</div>
			</div>

			<div class="row static-page__content">
				<div class="col">
					<?php echo $_smarty_tpl->tpl_vars['statlap']->value['szoveg'];?>

				</div>
			</div>
	</article>
</div>
<?php
}
}
/* {/block "kozep"} */
}
