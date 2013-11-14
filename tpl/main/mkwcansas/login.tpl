{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span6">
			<div class="loginform chk-loginrightborder">
				<div class="form-header">
				<h2>Regisztráljon</h2>
				<h4>Hozza létre saját felhasználói fiókját.</h4>
				<h5>A Mindent Kapni fiók előnyei:</h5>
				<ul>
					<li>gyorsabb rendelés, mert vásárláskor már nem kell kitöltenie adatait</li>
					<li>nyomon követheti rendeléseit</li>
					<li>összeállíthatja kívánságlistáját jövőbeni vásárlásaihoz</li>
					<li>hűségpontokat gyűjthet és válthat be</li>
					<li>megírhatja véleményét termékeinkről</li>
					<li>elsők között értesülhet akciós termékeinkről, legfrissebb híreinkről</li>
				</ul>
				</div>
				<form id="Regform" action="/regisztracio/ment" method="post">
					<fieldset>
						<div class="controls controls-row chk-controloffset">
							<input id="VezeteknevEdit" name="vezeteknev" type="text" class="span" value="{$vezeteknev|default}" placeholder="{t('vezetéknév')} *" required data-errormsg="{t('Adja meg a nevét')}">
							<input id="KeresztnevEdit" name="keresztnev" type="text" class="span" value="{$keresztnev|default}" placeholder="{t('keresztnév')} *" required>
						</div>
						<div class="controls controls-row chk-controloffset">
							<input id="EmailEdit" name="email" type="email" class="span" value="{$email|default}" placeholder="{t('email')} *" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
						</div>
						<div class="controls controls-row chk-controloffset">
							<div class="chk-relative pull-left">
								<input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="jelszó 1 *" data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-tooltipbtn" title="" data-original-title="Jelszó azért szükséges, hogy csak Ön férhessen hozzá személyes adataihoz. Használhat kis- és nagybetűket, valamint számokat is a jelszóban (erősen ajánlott)."></i>
							</div>
							<div class="chk-relative pull-left">
								<i class="span inputiconhack"></i>
								<input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="jelszó 2 *">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-tooltipbtn" title="" data-original-title="Azért kérjük kétszer a jelszót, hogy biztosan elkerülhessük az elgépelést."></i>
							</div>
						</div>
						<div class="row chk-actionrow span">
							<button type="submit" class="btn okbtn">Regisztráció</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<div class="span6">
			<div class="form-header">
				<h3>Jelentkezzen be</h3>
				<h5>Üdvözöljük újra itthon! A vásárláshoz jelentkezzen be:</h5>
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
					<div class="row chk-actionrow">
                        <div class="span">
                            <button type="submit" class="span btn okbtn">{t('Belépés')}</button>
                        </div>
					</div>
                    <div class="row chk-actionrow">
                        <div class="span">
                            <a class="span" href="">Elfelejtettem a jelszavam</a>
                        </div>
                    </div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
{/block}
