<div id="termekertesitoModal" class="modal hide fade" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>{t('Termék értesítő')}</h3>
  </div>
  <div class="modal-body">
    <p>Adja meg emailcímét, és értesítjük amint megérkezik a termék.</p>
	<form id="termekertesitoform" class="form-horizontal" action="/" method="post">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="EmailEdit">{t('Emailcím')}:</label>
				<div class="controls">
					<input id="EmailEdit" name="email" type="email" class="input-large" value="{$email}" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
					<span id="EmailMsg" class="help-inline">{$hibak.email}</span>
				</div>
			</div>
			<input type="hidden" name="termekid">
		</fieldset>
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">{t('Mégsem')}</button>
    <button class="btn btn-primary termekertesitomodalok">{t('OK')}</button>
  </div>
</div>
