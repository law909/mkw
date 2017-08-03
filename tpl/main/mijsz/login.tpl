{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span6">
			<div class="form-header">
				<h3>{t('Jelentkezz be')}</h3>
			</div>
			{if ($sikertelen)}
			<div class="error">
				<h4>{t('A bejelentkezés nem sikerült')}</h4>
			</div>
			{/if}
			<form id="Loginform" action="/login/ment" method="post">
				<fieldset>
					<div class="controls chk-controloffset">
						<input name="email" type="email" class="" placeholder="{t('email')} *" required data-errormsg1="{t('Add meg az emailcímed')}" data-errormsg2="{t('Kérjük emailcímet adj meg.')}">
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
                            <a class="span js-passreminder" href>{t('Kattints ide, ha elfelejtetted a jelszavad')}!</a>
                        </div>
                    </div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
{/block}
