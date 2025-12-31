<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f886e219_78078694',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '663d2e08c0d3f231ceabaa252b8dad448407ae7c' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/header.tpl',
      1 => 1767111979,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:headerfirstrow.tpl' => 1,
    'file:minikosar.tpl' => 1,
  ),
),false)) {
function content_695466f886e219_78078694 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
$_smarty_tpl->_subTemplateRender("file:headerfirstrow.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['menu1']) ? $_smarty_tpl->tpl_vars['menu1']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[0]['childcount'] = 10;
$_smarty_tpl->_assignInScope('menu1', $_tmp_array);
$_tmp_array = isset($_smarty_tpl->tpl_vars['menu1']) ? $_smarty_tpl->tpl_vars['menu1']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[0]['children'] = $_smarty_tpl->tpl_vars['menu1']->value;
$_smarty_tpl->_assignInScope('menu1', $_tmp_array);?>

<div class="header container-full__with-padding">
  <div class="row">
    <div class="col flex-cl header__left">
      <a href="/"><img class="header__logo" src="/themes/main/mugenrace2026/img/mugen-logo-white.svg" alt="Mugenrace Webshop" title="Mugenrace Webshop"></a>
    </div>
    <div class="col flex-cc header__center">
        <nav class="main-menu flex-cc">
            <ul id="" class="flex-cc">
                                <li>
                    <a href="#" title="<?php echo t('Férfi');?>
"><?php echo t('Férfi');?>
</a>
                    <i class="icon arrow-down white main-menu__arrow icon__click"></i>
                    <div class="sub">
                        <div class="sub__wrapper">
                            <ul>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu1']->value, '_menupont', true);
$_smarty_tpl->tpl_vars['_menupont']->iteration = 0;
$_smarty_tpl->tpl_vars['_menupont']->index = -1;
$_smarty_tpl->tpl_vars['_menupont']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_menupont']->value) {
$_smarty_tpl->tpl_vars['_menupont']->do_else = false;
$_smarty_tpl->tpl_vars['_menupont']->iteration++;
$_smarty_tpl->tpl_vars['_menupont']->index++;
$_smarty_tpl->tpl_vars['_menupont']->first = !$_smarty_tpl->tpl_vars['_menupont']->index;
$_smarty_tpl->tpl_vars['_menupont']->last = $_smarty_tpl->tpl_vars['_menupont']->iteration === $_smarty_tpl->tpl_vars['_menupont']->total;
$__foreach__menupont_7_saved = $_smarty_tpl->tpl_vars['_menupont'];
?>
                                <li<?php if (($_smarty_tpl->tpl_vars['_menupont']->last)) {?> class="last"<?php }
if (($_smarty_tpl->tpl_vars['_menupont']->first)) {?> class="first"<?php }?>><a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['slug'];?>
" data-cnt="<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['childcount'];?>
"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_menupont']->value['caption'], 'UTF-8'));?>
</a></li>
                            <?php
$_smarty_tpl->tpl_vars['_menupont'] = $__foreach__menupont_7_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        </div>        
                    </div>
                </li>
                <li>
                    <a href="#" title="<?php echo t('Női');?>
"><?php echo t('Női');?>
</a>
                    <i class="icon arrow-down white main-menu__arrow icon__click"></i>
                    <div class="sub">
                        <div class="sub__wrapper">
                            <ul>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu1']->value, '_menupont', true);
$_smarty_tpl->tpl_vars['_menupont']->iteration = 0;
$_smarty_tpl->tpl_vars['_menupont']->index = -1;
$_smarty_tpl->tpl_vars['_menupont']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_menupont']->value) {
$_smarty_tpl->tpl_vars['_menupont']->do_else = false;
$_smarty_tpl->tpl_vars['_menupont']->iteration++;
$_smarty_tpl->tpl_vars['_menupont']->index++;
$_smarty_tpl->tpl_vars['_menupont']->first = !$_smarty_tpl->tpl_vars['_menupont']->index;
$_smarty_tpl->tpl_vars['_menupont']->last = $_smarty_tpl->tpl_vars['_menupont']->iteration === $_smarty_tpl->tpl_vars['_menupont']->total;
$__foreach__menupont_8_saved = $_smarty_tpl->tpl_vars['_menupont'];
?>
                                <li<?php if (($_smarty_tpl->tpl_vars['_menupont']->last)) {?> class="last"<?php }
if (($_smarty_tpl->tpl_vars['_menupont']->first)) {?> class="first"<?php }?>><a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['slug'];?>
" data-cnt="<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['childcount'];?>
"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_menupont']->value['caption'], 'UTF-8'));?>
</a></li>
                            <?php
$_smarty_tpl->tpl_vars['_menupont'] = $__foreach__menupont_8_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        </div>        
                    </div>
                </li>
                <li>
                    <a href="#" title="<?php echo t('Gyerek');?>
"><?php echo t('Gyerek');?>
</a>
                    <i class="icon arrow-down white main-menu__arrow icon__click"></i>
                    <div class="sub">
                        <div class="sub__wrapper">
                            <ul>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu1']->value, '_menupont', true);
$_smarty_tpl->tpl_vars['_menupont']->iteration = 0;
$_smarty_tpl->tpl_vars['_menupont']->index = -1;
$_smarty_tpl->tpl_vars['_menupont']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_menupont']->value) {
$_smarty_tpl->tpl_vars['_menupont']->do_else = false;
$_smarty_tpl->tpl_vars['_menupont']->iteration++;
$_smarty_tpl->tpl_vars['_menupont']->index++;
$_smarty_tpl->tpl_vars['_menupont']->first = !$_smarty_tpl->tpl_vars['_menupont']->index;
$_smarty_tpl->tpl_vars['_menupont']->last = $_smarty_tpl->tpl_vars['_menupont']->iteration === $_smarty_tpl->tpl_vars['_menupont']->total;
$__foreach__menupont_9_saved = $_smarty_tpl->tpl_vars['_menupont'];
?>
                                <li<?php if (($_smarty_tpl->tpl_vars['_menupont']->last)) {?> class="last"<?php }
if (($_smarty_tpl->tpl_vars['_menupont']->first)) {?> class="first"<?php }?>><a href="/termekfa/<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['slug'];?>
" data-cnt="<?php echo $_smarty_tpl->tpl_vars['_menupont']->value['childcount'];?>
"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['_menupont']->value['caption'], 'UTF-8'));?>
</a></li>
                            <?php
$_smarty_tpl->tpl_vars['_menupont'] = $__foreach__menupont_9_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        </div>
                    </div>
                </li>
                <li><a href="/hirek" title="<?php echo t('Legfrissebb híreink');?>
"><?php echo t('Legfrissebb híreink');?>
</a></li>
                <li><a href="/statlap/about-us" title="<?php echo t('Rólunk');?>
"><?php echo t('Rólunk');?>
</a></li>
                <li><a href="/statlap/sponsored-riders" title="<?php echo t('Szponzorált versenyzők');?>
"><?php echo t('Szponzorált versenyzők');?>
</a></li>
                <li><a href="/statlap/csapatok" title="<?php echo t('Csapatok');?>
"><?php echo t('Csapatok');?>
</a></li>
            </ul>
            <i class="icon close white main-menu__close icon__click"></i>
        </nav>
    </div>
    <div class="col flex-cr header__right">
        
        <nav class="right-menu flex-cc">
            <ul id="" class="flex-cc">
                <li><i class="icon search white icon__click"></i></li>
                <li>
                                            <?php $_smarty_tpl->_subTemplateRender("file:minikosar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
                                    </li>
                <li>
                    <i class="icon menu white menu-toggle icon__click"></i>
                </li>
            </ul>
        </nav>

        <form id="searchform" class="header__searchform flex-cc" name="searchbox" method="get" action="/kereses" autocomplete="off">
            <div class="searchinputbox flex-cc">
                <input id="searchinput" class="siteSearch span2" type="text" title="<?php echo t('Keressen a termékeink között!');?>
" placeholder="<?php echo t('Keressen a termékeink között!');?>
" accesskey="k" value="" maxlength="300" name="keresett">
                            </div>
            <i class="icon close white header__searchform-close icon__click"></i>
        </form>
    </div>
  </div>
</div>


<?php }
}
