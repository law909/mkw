<?php
/* Smarty version 4.3.0, created on 2025-12-31 00:57:44
  from '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termekertesitomodal.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_695466f88b3334_40809902',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7ce2159817bd0de9336675dcaa8087c29483080a' => 
    array (
      0 => '/Users/nemethtamas/Sites/Local/mugenrace.local/app/public/tpl/main/mugenrace2026/termekertesitomodal.tpl',
      1 => 1764057952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_695466f88b3334_40809902 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="termekertesitoModal" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3><?php echo t('Termékértesítő');?>
</h3>
  </div>
  <div class="modal-body">
    <p>
        <?php if (($_smarty_tpl->tpl_vars['locale']->value === 'hu')) {?>
            Ez a termék sajnos most nincs készleten.<br>Adja meg emailcímét, és <strong>azonnal értesítjük</strong>, amint ismét elérhető lesz!
        <?php } elseif (($_smarty_tpl->tpl_vars['locale']->value === 'en')) {?>
            Sorry, we don't have this product in stock.<br>Subscribe to our advice note!
        <?php }?>
    </p>
    <form id="termekertesitoform" class="form-horizontal" action="/termekertesito/save" method="post">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="EmailEdit"><?php echo t('Email');?>
:</label>
				<div class="controls">
					<input id="EmailEdit" name="email" type="email" class="input-large" value="<?php echo (($tmp = $_smarty_tpl->tpl_vars['user']->value['email'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" required data-errormsg1="<?php echo t('Adja meg az emailcímét');?>
" data-errormsg2="<?php echo t('Kérjük, emailcímet adjon meg.');?>
">
					<span id="EmailMsg" class="help-inline"><?php echo (($tmp = $_smarty_tpl->tpl_vars['hibak']->value['email'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</span>
				</div>
			</div>
			<input type="hidden" name="termekid">
			<input type="hidden" name="oper" value="add">
		</fieldset>
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><?php echo t('Mégsem');?>
</button>
    <button class="btn okbtn js-termekertesitomodalok"><?php echo t('OK');?>
</button>
  </div>
</div><?php }
}
