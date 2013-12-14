{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span14 offset1">
			<div class="form-header">
				<h2>Regisztráljon</h2>
				<h3>Hozza létre saját felhasználói fiókját.</h3>
				<h4>A Mindent Kapni fiók előnyei:</h4>
				<ul>
					<li>gyorsabb rendelés, mert vásárláskor már nem kell kitöltenie adatait</li>
					<li>nyomon követheti rendeléseit</li>
					<li>összeállíthatja kívánságlistáját jövőbeni vásárlásaihoz</li>
					<li>hűségpontokat gyűjthet és válthat be</li>
					<li>megírhatja véleményét termékeinkről</li>
					<li>elsők között értesülhet akciós termékeinkről, legfrissebb híreinkről</li>
				</ul>
			</div>
			<form id="Regform" class="form-horizontal" action="/regisztracio/ment" method="post">
				<fieldset>
					<div class="control-group{if ($hibak.vezeteknev||$hibak.keresztnev)} error{/if}">
						<label class="control-label" for="VezeteknevEdit">{t('Név')}:</label>
						<div class="controls">
							<input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" value="{$vezeteknev|default}" placeholder="{t('vezetéknév')}" required data-errormsg="{t('Adja meg a nevét')}">
							<input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" value="{$keresztnev|default}" placeholder="{t('keresztnév')}" required>
							<span id="NevMsg" class="help-inline">{$hibak.keresztnev|default}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.email|default)} error{/if}">
						<label class="control-label" for="EmailEdit">{t('Emailcím')}:</label>
						<div class="controls">
							<input id="EmailEdit" name="email" type="email" class="input-large" value="{$email|default}" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük, emailcímet adjon meg.')}">
							<span id="EmailMsg" class="help-inline">{$hibak.email|default}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.jelszo|default)} error{/if}">
						<label class="control-label" for="Jelszo1Edit">{t('Jelszó')}:</label>
						<div class="controls">
							<input id="Jelszo1Edit" name="jelszo1" type="password" class="input-medium" required data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
							<input id="Jelszo2Edit" name="jelszo2" type="password" class="input-medium" required>
							<span id="JelszoMsg" class="help-inline">{$hibak.jelszo|default}</span>
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