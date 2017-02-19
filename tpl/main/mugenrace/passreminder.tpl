{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span12">
			<div class="passreminderform">
				<div class="form-header">
				<h4>{t('Adja meg új jelszavát')}</h4>
				</div>
				<form id="passreminderform" action="/passreminder/ment" method="post">
					<fieldset>
						<div class="controls controls-row chk-controloffset">
							<div class="chk-relative pull-left">
								<input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="{t('jelszó')} 1 *" data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
							</div>
							<div class="chk-relative pull-left">
								<i class="span inputiconhack"></i>
								<input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="{t('jelszó')} 2 *">
							</div>
                                <input type="hidden" name="id" value="{$reminder}">
						</div>
						<div class="row chk-actionrow span">
							<button type="submit" class="btn okbtn">{t('OK')}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
{/block}
