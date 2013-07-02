{extends "base.tpl"}

{block "script"}
<script src="/js/main/mkwcansas/h5f.js"></script>
<script src="/js/main/mkwcansas/jquery.blockUI.js"></script>
<script src="/js/main/mkwcansas/bootstrap.min.js"></script>
{/block}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span14 offset1">
			<div class="form-header">
				<h2>Regisztráljon</h2>
				<h4>Hozza létre saját felhasználói fiókját, mert:</h4>
				<ul>
					<li>vásárláskor már nem kell kitöltenie adatait;</li>
					<li>követheti rendeléseit;</li>
					<li>hűségpontokat gyűjthet és válthat be;</li>
					<li>megírhatja véleményét termékeinkről;</li>
					<li>személyre szabott ajánlatokkal bombázhatjuk önt.</li>
				</ul>
			</div>
			<form id="Regform" class="form-horizontal" action="/regisztracio/ment" method="post">
				<fieldset>
					<div class="control-group{if ($hibak.vezeteknev||$hibak.keresztnev)} error{/if}">
						<label class="control-label" for="VezeteknevEdit">{t('Név')}:</label>
						<div class="controls">
							<input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" value="{$vezeteknev}" placeholder="{t('vezetéknév')}" required data-errormsg="{t('Adja meg a nevét')}">
							<input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" value="{$keresztnev}" placeholder="{t('keresztnév')}" required>
							<span id="NevMsg" class="help-inline">{$hibak.keresztnev}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.email)} error{/if}">
						<label class="control-label" for="EmailEdit">{t('Emailcím')}:</label>
						<div class="controls">
							<input id="EmailEdit" name="email" type="email" class="input-large" value="{$email}" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
							<span id="EmailMsg" class="help-inline">{$hibak.email}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.jelszo)} error{/if}">
						<label class="control-label" for="Jelszo1Edit">{t('Jelszó')}:</label>
						<div class="controls">
							<input id="Jelszo1Edit" name="jelszo1" type="password" class="input-medium" required data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
							<input id="Jelszo2Edit" name="jelszo2" type="password" class="input-medium" required>
							<span id="JelszoMsg" class="help-inline">{$hibak.jelszo}</span>
							<p class="help-block">Kétszer adja meg jelszavát, így elkerülheti az elgépelést.</p>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn okbtn">OK</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
{/block}