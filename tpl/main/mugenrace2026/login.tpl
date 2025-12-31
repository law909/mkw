{extends "base.tpl"}

{block "kozep"}
<div class="container-full login-page">
	<div class="row flex-cc">
		<div class="col login-page__left">
			
		</div>
		<div class="col flex-cc flex-col login-page__right">


			<div class="form-header">
				<h3 class="title-header"><span>{t('Jelentkezzen be')}</span></h3>
			</div>
			{if ($sikertelen)}
			<div class="error">
				<h4>{t('A bejelentkezés nem sikerült')}</h4>
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
                            <button type="submit" class="button primary okbtn">{t('Belépés')}</button>
                        </div>
					</div>
                    <div class="row chk-actionrow">
                        <div class="span">
                            <a class="span js-passreminder" href>{t('Kattintson ide, ha elfelejtette a jelszavát')}!</a>
                        </div>
                    </div>
				</fieldset>
			</form>

			{* <div class="divider"></div> *}

			<div class="loginform chk-loginrightborder">
				<div class="form-header">
          <h2 class="title-header"><span>{t('Regisztráljon')}</span></h2>
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
								<input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="{t('jelszó')} 1 *" data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
							</div>
							<div class="chk-relative pull-left">
								<i class="span inputiconhack"></i>
								<input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="{t('jelszó')} 2 *">
							</div>
						</div>
						<div class="row chk-actionrow span">
							<button type="submit" class="button primary okbtn">{t('Regisztráció')}</button>
						</div>
					</fieldset>
				</form>
			</div>


			
		</div>
	</div>
</div>
{/block}
