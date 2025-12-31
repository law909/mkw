<?php
/* Smarty version 4.3.0, created on 2025-12-31 01:20:27
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termeklista.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_69546c4b54f4f3_17014477',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca582f990ba6366c16c9861ab3f42af880d8bda4' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termeklista.tpl',
      1 => 1767140424,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:termekertesitomodal.tpl' => 1,
  ),
),false)) {
function content_69546c4b54f4f3_17014477 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_90990612669546c4b51e4f9_97537423', "kozep");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "base.tpl");
}
/* {block "kozep"} */
class Block_90990612669546c4b51e4f9_97537423 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'kozep' => 
  array (
    0 => 'Block_90990612669546c4b51e4f9_97537423',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),1=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),));
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
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['navigator']->value, '_navi', true);
$_smarty_tpl->tpl_vars['_navi']->iteration = 0;
$_smarty_tpl->tpl_vars['_navi']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_navi']->value) {
$_smarty_tpl->tpl_vars['_navi']->do_else = false;
$_smarty_tpl->tpl_vars['_navi']->iteration++;
$_smarty_tpl->tpl_vars['_navi']->last = $_smarty_tpl->tpl_vars['_navi']->iteration === $_smarty_tpl->tpl_vars['_navi']->total;
$__foreach__navi_0_saved = $_smarty_tpl->tpl_vars['_navi'];
?>
                        <?php if (((($tmp = $_smarty_tpl->tpl_vars['_navi']->value['url'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
                            <span typeof="v:Breadcrumb">
                                <a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_navi']->value['url'];?>
" rel="v:url" property="v:title">
                                    <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_navi']->value['caption'], 'UTF-8'));?>

                                </a>
                            </span>
                            <i class="icon arrow-right"></i>
                        <?php } else { ?>
                            <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_navi']->value['caption'], 'UTF-8'));?>

                        <?php }?>
                    <?php
$_smarty_tpl->tpl_vars['_navi'] = $__foreach__navi_0_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                <?php }?>
            </span>
		</div>
	</div>
    <div class="row">
        <div class="col">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['navigator']->value, '_navi', true);
$_smarty_tpl->tpl_vars['_navi']->iteration = 0;
$_smarty_tpl->tpl_vars['_navi']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_navi']->value) {
$_smarty_tpl->tpl_vars['_navi']->do_else = false;
$_smarty_tpl->tpl_vars['_navi']->iteration++;
$_smarty_tpl->tpl_vars['_navi']->last = $_smarty_tpl->tpl_vars['_navi']->iteration === $_smarty_tpl->tpl_vars['_navi']->total;
$__foreach__navi_1_saved = $_smarty_tpl->tpl_vars['_navi'];
?>
                <?php if (($_smarty_tpl->tpl_vars['_navi']->last)) {?>
                    <h1 class="page-header__title" typeof="v:Breadcrumb">
                        <a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_navi']->value['url'];?>
" rel="v:url" property="v:title">
                            <?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_navi']->value['caption'], 'UTF-8'));?>

                        </a>
                    </h1>
                <?php }?>
            <?php
$_smarty_tpl->tpl_vars['_navi'] = $__foreach__navi_1_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
        <div class="col flex-cr">
            <button class="bordered product-filter__toggle">
                <span><?php echo t('Szűrők');?>
</span>
                <i class="icon filter"></i>
            </button>
        </div>
    </div>

</div>
<div class="container whitebg">

    <div class="product-filter">
        <div class="product-filter__header flex-lc">
            <span class="product-filter__title bold"><?php echo t('Szűrőfeltételek');?>
</span>
            <span class="product-filter__close js-filterclose"><i class="icon close icon__click"></i></span>
        </div>

        <select name="elemperpage" class="elemperpageedit">
            <?php $_smarty_tpl->_assignInScope('elemszam', array(10,20,30,40,$_smarty_tpl->tpl_vars['lapozo']->value['elemcount']));?>
            <?php $_smarty_tpl->_assignInScope('elemnev', array(("10 ").(t('darab')),("20 ").(t('darab')),("30 ").(t('darab')),("40 ").(t('darab')),t("Mind")));?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elemszam']->value, 'c');
$_smarty_tpl->tpl_vars['c']->index = -1;
$_smarty_tpl->tpl_vars['c']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->do_else = false;
$_smarty_tpl->tpl_vars['c']->index++;
$__foreach_c_2_saved = $_smarty_tpl->tpl_vars['c'];
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['c']->value;?>
"<?php if (($_smarty_tpl->tpl_vars['c']->value == $_smarty_tpl->tpl_vars['lapozo']->value['elemperpage'])) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['elemnev']->value[$_smarty_tpl->tpl_vars['c']->index];?>
</option>
            <?php
$_smarty_tpl->tpl_vars['c'] = $__foreach_c_2_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>

        <select name="order" class="orderedit">
            <option value="nevasc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'nevasc')) {?> selected="selected"<?php }?>><?php echo t('Név szerint növekvő');?>
</option>
            <option value="nevdesc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'nevdesc')) {?> selected="selected"<?php }?>><?php echo t('Név szerint csökkenő');?>
</option>
            <option value="arasc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'arasc')) {?> selected="selected"<?php }?>><?php echo t('Legolcsóbb elől');?>
</option>
            <option value="ardesc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'ardesc')) {?> selected="selected"<?php }?>><?php echo t('Legdrágább elől');?>
</option>
            <option value="idasc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'idasc')) {?> selected="selected"<?php }?>><?php echo t('Legrégebbi elől');?>
</option>
            <option value="iddesc"<?php if (($_smarty_tpl->tpl_vars['order']->value == 'iddesc')) {?> selected="selected"<?php }?>><?php echo t('Legújabb elől');?>
</option>
        </select>
        <input class="KeresettEdit" type="hidden" name="keresett" value="<?php echo $_smarty_tpl->tpl_vars['keresett']->value;?>
">
        <input id="ListviewEdit" type="hidden" name="vt" value="<?php echo $_smarty_tpl->tpl_vars['vt']->value;?>
">
        <input id="CsakakciosEdit" type="hidden" name="csakakcios" value="<?php echo $_smarty_tpl->tpl_vars['csakakcios']->value;?>
">

        <div class="szurofej szurokontener js-filterclear bold">
            <?php echo t('Szűrőfeltételek törlése');?>

        </div>

        
        <form id="szuroform">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['szurok']->value, '_szuro');
$_smarty_tpl->tpl_vars['_szuro']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_szuro']->value) {
$_smarty_tpl->tpl_vars['_szuro']->do_else = false;
?>
            <div class="szurokontener">
                <div class="szurofej closeupbutton" data-refcontrol="#SzuroFej<?php echo $_smarty_tpl->tpl_vars['_szuro']->value['id'];?>
"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_szuro']->value['caption'], 'UTF-8'));?>
 <i class="icon-chevron-up"></i></div>
                <div id="SzuroFej<?php echo $_smarty_tpl->tpl_vars['_szuro']->value['id'];?>
" class="szurodoboz">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_szuro']->value['cimkek'], '_ertek');
$_smarty_tpl->tpl_vars['_ertek']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_ertek']->value) {
$_smarty_tpl->tpl_vars['_ertek']->do_else = false;
?>
                        <div>
                            <label class="checkbox" for="SzuroEdit<?php echo $_smarty_tpl->tpl_vars['_ertek']->value['id'];?>
">
                                <input id="SzuroEdit<?php echo $_smarty_tpl->tpl_vars['_ertek']->value['id'];?>
" name="szuro_<?php echo $_smarty_tpl->tpl_vars['_szuro']->value['id'];?>
_<?php echo $_smarty_tpl->tpl_vars['_ertek']->value['id'];?>
" type="checkbox"<?php if (($_smarty_tpl->tpl_vars['_ertek']->value['selected'])) {?> checked="checked"<?php }?>><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_ertek']->value['caption'], 'UTF-8'));
if (((($tmp = $_smarty_tpl->tpl_vars['_ertek']->value['termekdb'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?> (<?php echo $_smarty_tpl->tpl_vars['_ertek']->value['termekdb'];?>
)<?php }?>
                            </label>
                        </div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </div>
            </div>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </form>
    </div>


	<div class="row">
				<div class="col">
			<div class="category-description">
				<?php echo $_smarty_tpl->tpl_vars['kategoria']->value['leiras2'];?>

			</div>
        <?php $_smarty_tpl->_assignInScope('lntcnt', count($_smarty_tpl->tpl_vars['kiemelttermekek']->value));?>
        <?php if (($_smarty_tpl->tpl_vars['lntcnt']->value > 0)) {?>
            <div class="lapozo">
                <span class="bold">Kiemelt termékeink</span>
            </div>
            <div>
            <?php $_smarty_tpl->_assignInScope('step', min(3,$_smarty_tpl->tpl_vars['lntcnt']->value));?>
            <?php if (($_smarty_tpl->tpl_vars['step']->value == 0)) {?>
                <?php $_smarty_tpl->_assignInScope('step', 1);?>
            <?php }?>
            <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lntcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['lntcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                <div>
                <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                    <?php if (($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value < $_smarty_tpl->tpl_vars['lntcnt']->value)) {?>
                    <?php $_smarty_tpl->_assignInScope('_termek', $_smarty_tpl->tpl_vars['kiemelttermekek']->value[$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                    <div class="textaligncenter pull-left" style="width:<?php echo 100/$_smarty_tpl->tpl_vars['step']->value;?>
%">
                        <div class="o404TermekInner">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['link'];?>
">
                                <div class="o404ImageContainer">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_termek']->value['kiskepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
">
                                </div>
                                <div><?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
</div>
                                <h5 class="termeklista">
                                    <?php if (($_smarty_tpl->tpl_vars['_termek']->value['akcios'])) {?>
                                    <span><span class="akciosar"><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['eredetibruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>
</span> helyett <?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>
</span>
                                    <?php } else { ?>
                                    <span><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>
</span>
                                    <?php }?>
                                </h5>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['link'];?>
" class="btn okbtn">Részletek</a>
                            </a>
                        </div>
                    </div>
                    <?php }?>
                <?php }
}
?>
                </div>
            <?php }
}
?>
            </div>
        <?php }?>
			<div class="lapozo">
				<form class="lapozoform" action="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" method="post" data-url="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" data-pageno="<?php echo $_smarty_tpl->tpl_vars['lapozo']->value['pageno'];?>
">
					<table><tbody><tr>
																				</tr></tbody></table>
				</form>
			</div>

			<?php if (($_smarty_tpl->tpl_vars['lapozo']->value['elemcount'] > 0)) {?>
                <?php $_smarty_tpl->_assignInScope('termekcnt', count($_smarty_tpl->tpl_vars['termekek']->value));?>
                <?php $_smarty_tpl->_assignInScope('step', 4);?>
                <div class="product-list">
                <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = $_smarty_tpl->tpl_vars['step']->value;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['termekcnt']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['termekcnt']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?>
                                        <?php
$_smarty_tpl->tpl_vars['j'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['j']->step = 1;$_smarty_tpl->tpl_vars['j']->total = (int) ceil(($_smarty_tpl->tpl_vars['j']->step > 0 ? $_smarty_tpl->tpl_vars['step']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['step']->value-1)+1)/abs($_smarty_tpl->tpl_vars['j']->step));
if ($_smarty_tpl->tpl_vars['j']->total > 0) {
for ($_smarty_tpl->tpl_vars['j']->value = 0, $_smarty_tpl->tpl_vars['j']->iteration = 1;$_smarty_tpl->tpl_vars['j']->iteration <= $_smarty_tpl->tpl_vars['j']->total;$_smarty_tpl->tpl_vars['j']->value += $_smarty_tpl->tpl_vars['j']->step, $_smarty_tpl->tpl_vars['j']->iteration++) {
$_smarty_tpl->tpl_vars['j']->first = $_smarty_tpl->tpl_vars['j']->iteration === 1;$_smarty_tpl->tpl_vars['j']->last = $_smarty_tpl->tpl_vars['j']->iteration === $_smarty_tpl->tpl_vars['j']->total;?>
                    <?php if (((isset($_smarty_tpl->tpl_vars['termekek']->value[$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value])))) {?>
                        <?php $_smarty_tpl->_assignInScope('_termek', $_smarty_tpl->tpl_vars['termekek']->value[$_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value]);?>
                    <?php }?>
                    <?php if (($_smarty_tpl->tpl_vars['_termek']->value)) {?>
                        <div class=" product-list-item spanmkw3 gtermek<?php if ((($_smarty_tpl->tpl_vars['j']->value == $_smarty_tpl->tpl_vars['step']->value-1) || ($_smarty_tpl->tpl_vars['i']->value+$_smarty_tpl->tpl_vars['j']->value >= $_smarty_tpl->tpl_vars['termekcnt']->value))) {?> gtermekszelso<?php }?> itemscope itemtype="http://schema.org/Product">
                            <div class="gtermekinner"><div class="gtermekinnest product-list-item__inner">
                                <div class="textaligncenter product-list-item__image-container">
                                    <a href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><img class="product-list-item__image itemprop="image" src="<?php echo $_smarty_tpl->tpl_vars['imagepath']->value;
echo $_smarty_tpl->tpl_vars['_termek']->value['kepurl'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['caption'];?>
"></a>

                                                                    </div>
                                <div class="textaligncenter product-list-item__content product-list-item__title">
                                    <a itemprop="url" href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><span class="gtermekcaption" itemprop="name"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_termek']->value['caption'], 'UTF-8'));?>
</span></a>
                                </div>
                                <div class="textaligncenter product-list-item__content product-list-item__code">
                                    <a href="/termek/<?php echo $_smarty_tpl->tpl_vars['_termek']->value['slug'];?>
"><?php echo $_smarty_tpl->tpl_vars['_termek']->value['cikkszam'];?>
</a>
                                </div>
                                <div class="textaligncenter product-list-item__content">
                                    <?php if (($_smarty_tpl->tpl_vars['_termek']->value['szallitasiido'] && (!$_smarty_tpl->tpl_vars['_termek']->value['nemkaphato']))) {?>
                                    <div class="textaligncenter"><span class="bold">Szállítási idő: </span><?php echo $_smarty_tpl->tpl_vars['_termek']->value['szallitasiido'];?>
 munkanap</div>
                                    <?php }?>
                                    <?php if (((($tmp = $_smarty_tpl->tpl_vars['_termek']->value['szinek'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp))) {?>
                                        <div class="js-valtozatbox product-list-item__variations-container">
                                                                                        <div class="pull-left gvaltozatcontainer product-list-item__variations">
                                                <div class="pull-left gvaltozatnev termekvaltozat"><?php echo t('Szín');?>
:</div>
                                                <div class="pull-left gvaltozatselect">
                                                
                                                    <div class="option-selector color-selector" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_termek']->value['szinek'], '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                                                            <div class="select-option <?php echo smarty_modifier_replace(mb_strtolower($_smarty_tpl->tpl_vars['_v']->value, 'UTF-8'),'/','-');?>
" data-value="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
"></div>
                                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                    </div>
                                                
                                                    <select class="js-szinvaltozatedit valtozatselect" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                                        <option value=""><?php echo t('Válasszon');?>
</option>
                                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_termek']->value['szinek'], '_v');
$_smarty_tpl->tpl_vars['_v']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_v']->value) {
$_smarty_tpl->tpl_vars['_v']->do_else = false;
?>
                                                            <option value="<?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_v']->value;?>
</option>
                                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="flex-tb ">
                                    <div class="termekprice pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <?php if (($_smarty_tpl->tpl_vars['_termek']->value['akcios'])) {?>
                                            <span class="akciosarszoveg">Eredeti ár: <span class="akciosar"><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['eredetibruttohuf'],0,',',' ');?>
 <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>
</span></span>
                                        <?php }?>
                                        <?php if (($_smarty_tpl->tpl_vars['_termek']->value['nemkaphato'])) {?>
                                            <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                        <?php } else { ?>
                                            <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                        <?php }?>
                                        <span class="product-list-item__price" itemprop="price"><?php echo number_format($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'],0,',',' ');?>

                                         <?php echo $_smarty_tpl->tpl_vars['_termek']->value['valutanemnev'];?>

                                        </span>
                                    </div>
                                    <div class="pull-right">
                                    <?php if (($_smarty_tpl->tpl_vars['_termek']->value['nemkaphato'])) {?>
                                        <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                            <?php echo t('Elfogyott');?>

                                        </a>
                                    <?php } else { ?>
                                        <?php if (($_smarty_tpl->tpl_vars['_termek']->value['bruttohuf'] > 0)) {?>
                                        <a href="/kosar/add?id=<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
" rel="nofollow" class="js-kosarbaszinvaltozat button bordered small cartbtn pull-right" data-termek="<?php echo $_smarty_tpl->tpl_vars['_termek']->value['id'];?>
">
                                            <?php echo t('Kosárba');?>

                                        </a>
                                        <?php }?>
                                    <?php }?>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php }?>
                    <?php }
}
?>
                                    <?php }
}
?>
                </div>
			<?php } else { ?>
				<?php echo t('Nincs ilyen termék');?>

			<?php }?>
			<div class="lapozo">
				<form class="lapozoform" action="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" method="post" data-url="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" data-pageno="<?php echo $_smarty_tpl->tpl_vars['lapozo']->value['pageno'];?>
">
					<table><tbody><tr>
					<td class="lapozooldalak">
						<?php if (($_smarty_tpl->tpl_vars['lapozo']->value['pageno'] > 1)) {?><a href="#" class="pageedit" data-pageno="<?php echo $_smarty_tpl->tpl_vars['lapozo']->value['pageno']-1;?>
">< <?php echo t('Előző');?>
</a><?php }?>
						<?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['lapozo']->value['pagecount']+1 - (1) : 1-($_smarty_tpl->tpl_vars['lapozo']->value['pagecount'])+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration === 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration === $_smarty_tpl->tpl_vars['i']->total;?> <?php if (($_smarty_tpl->tpl_vars['i']->value == $_smarty_tpl->tpl_vars['lapozo']->value['pageno'])) {?><span class="aktualislap"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</span><?php } else { ?><a href="#" class="pageedit" data-pageno="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</a><?php }
}
}
?>
						<?php if (($_smarty_tpl->tpl_vars['lapozo']->value['pageno'] < $_smarty_tpl->tpl_vars['lapozo']->value['pagecount'])) {?><a href="#" class="pageedit" data-pageno="<?php echo $_smarty_tpl->tpl_vars['lapozo']->value['pageno']+1;?>
"><?php echo t('Következő');?>
 ></a><?php }?>
					</td>
					</tr></tbody></table>
				</form>
			</div>
		</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender('file:termekertesitomodal.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
/* {/block "kozep"} */
}
