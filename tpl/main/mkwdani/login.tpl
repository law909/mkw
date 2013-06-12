{extends "base.tpl"}

{block "script"}
<script src="/js/main/mkwdani/h5f.js"></script>
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span14 offset1">
			<div class="form-header">
				<h2>Üdvözöljük itthon</h2>
				<h4>Jelentkezzen be újra, és vásároljon.</h4>
			</div>
			{if ($sikertelen)}
			<div class="error">
				<h4>A bejelentkezés nem sikerült</h4>
			</div>
			{/if}
			<form id="Loginform" class="form-horizontal" action="/login/ment" method="post">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="EmailEdit">{t('Emailcím')}:</label>
						<div class="controls">
							<input id="EmailEdit" name="email" type="email" class="input-large" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="JelszoEdit">{t('Jelszó')}:</label>
						<div class="controls">
							<input id="JelszoEdit" name="jelszo" type="password" class="input-medium">
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn okbtn">{t('Bejelentkezés')}</button>
					</div>
				</fieldset>
			</form>
			<div>
				<div>{t('Új vásárló')}</div>
				<a href="/regisztracio" title="Regisztráció" class="btn btn-mini">{t('Regisztráció')}</a>
			</div>
		</div>
	</div>
</div>
{/block}