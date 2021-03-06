{extends "base.tpl"}

{block "meta"}
{/block}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span8 offset1">
			<div class="form-header">
				<h2>Küldjön nekünk üzenetet</h2>
				<h4>Információra lenne szüksége a megrendelésével, vagy valamely termékkel kapcsolatban?</h4>
                                <h5>Munkatársaink örömmel állnak rendelkezésére! Töltse ki a következő adatokat, és hamarosan felvesszük Önnel a kapcsolatot.</h5>
			</div>
			<form id="Kapcsolatform" class="form-horizontal" action="/kapcsolat/ment" method="post">
				<fieldset>
					<div class="control-group{if ($hibak.nev|default)} error{/if}">
						<label class="control-label" for="NevEdit">{t('Név')}:</label>
						<div class="controls">
							<input id="NevEdit" name="nev" type="text" class="input-large" value="{$nev|default}" required data-errormsg="{t('Adja meg a nevét')}">
							<span id="NevMsg" class="help-inline">{$hibak.nev|default}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.email|default)} error{/if}">
						<label class="control-label" for="Email1Edit">{t('Emailcím')}:</label>
						<div class="controls">
							<input id="Email1Edit" name="email1" type="email" class="input-large" value="{$email1|default}" required data-errormsg1="{t('Adja meg az emailcímét')}" data-errormsg2="{t('Kérjük emailcímet adjon meg.')}" data-errormsg3="{t('A két emailcím nem egyezik meg.')}">
							<span id="Email1Msg" class="help-inline">{$hibak.email|default}</span>
						</div>
						<label class="control-label" for="Email2Edit">{t('Emailcím megerősítése')}:</label>
						<div class="controls">
							<input id="Email2Edit" name="email2" type="email" class="input-large" value="{$email2|default}" required>
							<span id="Email2Msg" class="help-inline">{$hibak.email|default}</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="TelefonEdit">{t('Telefonszám')}:</label>
						<div class="controls">
							<input id="TelefonEdit" name="telefon" type="text" class="input-large" value="{$telefon|default}">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="RendelesszamEdit">{t('Megrendelés száma')}:</label>
						<div class="controls">
							<input id="RendelesszamEdit" name="rendelesszam" type="text" class="input-large" value="{$rendelesszam|default}">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="TemaEdit">{t('Témakör')}:</label>
						<div class="controls">
							<select id="TemaEdit" class="input-large" name="tema" required data-errormsg="{t('Adjon meg témát')}">
								<option value="">{t('válasszon')}</option>
								{foreach $temalista as $_tema}
								<option value="{$_tema.id}"{if ($_tema.selected)} selected="selected"{/if}>{$_tema.caption|default}</option>
								{/foreach}
							</select>
							<span id="TemaMsg" class="help-inline">{$hibak.tema|default}</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="SzovegEdit">{t('Megjegyzés')}:</label>
						<div class="controls">
							<textarea id="SzovegEdit" name="szoveg" class="input-large" required>{$szoveg|default}</textarea>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn okbtn">{t('Üzenet küldése')}</button>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="span3">
			<div class="keret">
				<div class="szurofej">{t('Kérdése van?')}</div>
				<div class="szurodoboz korbepadding">
					<ul>
						<li><a href="/statlap/gy-i-k-leggyakoribb-kerdesek">{t('Gy.I.K. - Gyakori kérdések')}</a></li>
						<li><a href="/statlap/szallitasi-feltetelek-es-tudnivalok">{t('Szállítási tudnivalók')}</a></li>
						<li><a href="/statlap/fizetesi-feltetelek">{t('Fizetési tudnivalók')}</a></li>
						<li><a href="/statlap/husegpontok">{t('Hűségpontok')}</a></li>
					</ul>
				</div>
			</div>
			<div class="keret">
				<div class="szurofej">{t('Biztonságos vásárlás')}</div>
				<div class="szurodoboz korbepadding">
					<ul>
						<li><a href="/statlap/penzvisszafizetesi-garancia">{t('Pénzvisszafizetési garancia')}</a></li>
						<li><a href="/statlap/vasarloink-visszajelzesei">{t('Vásárlóink visszajelzései')}</a></li>
						<li><a href="/statlap/vasarloi-adatok-kezelese">{t('Személyes adatok védelme')}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}