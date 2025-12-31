<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/headerfirstrow.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f887d4e8_81659280',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0a379853659edb143986a1758e8216caf91cd0df' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/headerfirstrow.tpl',
      1 => 1766258034,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_695466f887d4e8_81659280 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/vendor/smarty/smarty/libs/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),));
?>
<div class="top-header container-full__with-padding">  
  <div class="row">
    <div class="col flex-cl"></div>
    <div class="col flex-cr">
      <?php if ((!$_smarty_tpl->tpl_vars['user']->value['loggedin'])) {?>
      
        <nav class="top-header__menu flex-cc">
          <ul id="" class="flex-cc">
                        <li>
              <a rel="nofollow" href="<?php echo $_smarty_tpl->tpl_vars['showaccountlink']->value;?>
" title="<?php echo t('Fiókom');?>
"><?php echo t('Fiókom');?>
</a>
            </li>
                      </ul>
        </nav>
			<?php } else { ?>
        <nav class="top-header__menu flex-cc">
          <ul id="" class="flex-cc">
            <li>
              <a rel="nofollow" href="<?php echo $_smarty_tpl->tpl_vars['showaccountlink']->value;?>
" title="<?php echo t('Fiókom');?>
"><?php echo $_smarty_tpl->tpl_vars['user']->value['nev'];?>
</a>
            </li>
            <li>
              <a rel="nofollow" href="<?php echo $_smarty_tpl->tpl_vars['dologoutlink']->value;?>
"><?php echo t('Kijelentkezés');?>
</a>
            </li>
          </ul>
        </nav>
			<?php }?>  

      <div class="header-country-wrapper">
        <select name="headerorszag" class="headerorszag">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orszaglist']->value, 'f');
$_smarty_tpl->tpl_vars['f']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->do_else = false;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['f']->value['id'];?>
"<?php if (($_smarty_tpl->tpl_vars['f']->value['selected'])) {?> selected="selected"<?php }?>><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['f']->value['caption'], 'UTF-8'));?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
        <div id="countryTrigger" class="country-trigger">
          English
        </div>

        <div id="countryModal" class="country-modal">
          <div class="country-modal__content">
                        <i class="icon close country-modal__close icon__click"></i>

            <h2><?php echo t('Válassz országot');?>
</h2>

            <div class="country-list">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['orszaglist']->value, 'f');
$_smarty_tpl->tpl_vars['f']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
$_smarty_tpl->tpl_vars['f']->do_else = false;
?>
                <?php if (($_smarty_tpl->tpl_vars['f']->value['caption'])) {?><button class="button bordered <?php if (($_smarty_tpl->tpl_vars['f']->value['selected'])) {?> selected<?php }?>" data-value="<?php echo $_smarty_tpl->tpl_vars['f']->value['id'];?>
"><?php echo smarty_modifier_capitalize(mb_strtolower($_smarty_tpl->tpl_vars['f']->value['caption'], 'UTF-8'));?>
</button><?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>
    <?php }
}
