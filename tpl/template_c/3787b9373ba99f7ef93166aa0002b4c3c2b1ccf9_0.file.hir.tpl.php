<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:26:17
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/hir.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546da9689943_87138743',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3787b9373ba99f7ef93166aa0002b4c3c2b1ccf9' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/hir.tpl',
      1 => 1767140774,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69546da9689943_87138743 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1940990269546da967e663_89066063', "kozep");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "kozep"} */
class Block_1940990269546da967e663_89066063 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_1940990269546da967e663_89066063',
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
                <div class="col flex-cr">
                    <a href="/hirek/" class="button bordered"><?php echo t('Vissza a hírekhez');?>
</a>
                </div>
		</div>
</div>
<div class="container-sm  news-datasheet">
<article itemtype="http://schema.org/Article" itemscope="">
    <div class="row">
        <div class="col ">
            <h2 class="news-datasheet__title"><?php echo $_smarty_tpl->tpl_vars['hir']->value['cim'];?>
</h2>
            <div class="news-datasheet__meta">
                <div class="news-datasheet__date">
                    <?php echo $_smarty_tpl->tpl_vars['hir']->value['datum'];?>

                </div>
								<?php if (((isset($_smarty_tpl->tpl_vars['hir']->value['forras'])) && $_smarty_tpl->tpl_vars['hir']->value['forras'])) {?>
									<div class="news-datasheet__source">
											<?php echo $_smarty_tpl->tpl_vars['hir']->value['forras'];?>

									</div>
								<?php }?>
            </div>
            <div class="news-datasheet__content">
                <?php echo $_smarty_tpl->tpl_vars['hir']->value['szoveg'];?>

            </div>
        </div>
    </div>
</article>
</div>
<?php
}
}
/* {/block "kozep"} */
}
