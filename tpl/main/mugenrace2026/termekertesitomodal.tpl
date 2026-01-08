<div id="termekertesitoModal" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>{t('Termékértesítő')}</h3>
  </div>
  <div class="modal-body">
    <p>
        {if ($locale === 'hu')}
            Ez a termék sajnos most nincs készleten.<br>Adja meg emailcímét, és <strong>azonnal értesítjük</strong>, amint ismét elérhető lesz!
        {elseif ($locale === 'en')}
            Sorry, we don't have this product in stock.<br>Subscribe to our advice note!
        {/if}
    </p>
    <form id="termekertesitoform" class="form-horizontal" action="/termekertesito/save" method="post">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="EmailEdit">{t('Email')}:</label>
				<div class="controls">
					<input id="EmailEdit" name="email" type="email" class="input-large" value="{$user.email|default}" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük, emailcímet adjon meg.')}">
					<span id="EmailMsg" class="help-inline">{$hibak.email|default}</span>
				</div>
			</div>
			<input type="hidden" name="termekid">
			<input type="hidden" name="oper" value="add">
		</fieldset>
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">{t('Mégsem')}</button>
    <button class="btn okbtn js-termekertesitomodalok">{t('OK')}</button>
  </div>
</div>