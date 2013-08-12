{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span5 offset1">
			<div class="loginform chk-loginrightborder">
				<div class="form-header">
					<h3>Üdvözöljük itthon</h3>
					<h5>Jelentkezzen be újra, és vásároljon.</h5>
				</div>
				{if ($sikertelen)}
				<div class="error">
					<h4>A bejelentkezés nem sikerült</h4>
				</div>
				{/if}
				<form id="Loginform" action="/login/ment" method="post">
					<fieldset>
						<div class="controls chk-controloffset">
							<input name="email" type="email" class="" placeholder="{t('email')} *" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
						</div>
						<div class="controls chk-controloffset">
							<input name="jelszo" type="password" class="" placeholder="{t('jelszó')} *" value="">
						</div>
						<div class="row chk-actionrow span">
							<button type="submit" class="btn okbtn">{t('Belépés')}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<div class="span6">
			<div class="form-header">
				<h3>Regisztráljon</h3>
				<h5>Hozza létre saját felhasználói fiókját, mert:</h5>
				<ul>
					<li>vásárláskor már nem kell kitöltenie adatait;</li>
					<li>követheti rendeléseit;</li>
					<li>hűségpontokat gyűjthet és válthat be;</li>
					<li>megírhatja véleményét termékeinkről;</li>
					<li>személyre szabott ajánlatokkal bombázhatjuk önt.</li>
				</ul>
			</div>
			<form id="Regform" action="/regisztracio/ment" method="post">
				<fieldset>
					<div class="controls chk-controloffset">
						<input id="VezeteknevEdit" name="vezeteknev" type="text" class="" value="{$vezeteknev|default}" placeholder="{t('vezetéknév')} *" required data-errormsg="{t('Adja meg a nevét')}">
						<input id="KeresztnevEdit" name="keresztnev" type="text" class="" value="{$keresztnev|default}" placeholder="{t('keresztnév')} *" required>
					</div>
					<div class="controls chk-controloffset">
						<input id="EmailEdit" name="email" type="email" class="" value="{$email|default}" placeholder="{t('email')} *" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
					</div>
					<div class="controls chk-controloffset">
						<input id="Jelszo1Edit" name="jelszo1" type="password" class="" required placeholder="jelszó 1 *" data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
						<input id="Jelszo2Edit" name="jelszo2" type="password" class="" required placeholder="jelszó 2 *">
						<p class="help-block">Kétszer adja meg jelszavát, így elkerülheti az elgépelést.</p>
					</div>
					<div class="row chk-actionrow span">
						<button type="submit" class="btn okbtn">OK</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
{/block}