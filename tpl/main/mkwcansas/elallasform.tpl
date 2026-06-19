{extends "base.tpl"}

{block "meta"}
{/block}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span8 offset1">
			<div class="form-header">
				<h2>{t('Elállás a szerződéstől')}</h2>
				<h4>{t('Ha el kíván állni a vásárlástól, töltse ki az alábbi űrlapot.')}</h4>
				<h5>{t('Az elállás beérkezéséről a megadott email címre átvételi elismervényt küldünk.')}</h5>
			</div>
			<form id="Elallasform" class="form-horizontal" action="/elallas/ment" method="post">
				<fieldset>
					<div class="control-group{if ($hibak.nev|default)} error{/if}">
						<label class="control-label" for="NevEdit">{t('Név')}:</label>
						<div class="controls">
							<input id="NevEdit" name="nev" type="text" class="input-large" value="{$nev|default}" required data-errormsg="{t('Adja meg a nevét')}">
							<span id="NevMsg" class="help-inline">{$hibak.nev|default}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.email|default)} error{/if}">
						<label class="control-label" for="EmailEdit">{t('Emailcím')}:</label>
						<div class="controls">
							<input id="EmailEdit" name="email" type="email" class="input-large" value="{$email|default}" required data-errormsg="{t('Adja meg az emailcímét')}">
							<span id="EmailMsg" class="help-inline">{$hibak.email|default}</span>
						</div>
					</div>
					<div class="control-group{if ($hibak.bizonylat|default)} error{/if}">
						<label class="control-label" for="BizonylatEdit">{t('Bizonylat / megrendelés száma')}:</label>
						<div class="controls">
							<input id="BizonylatEdit" name="bizonylat" type="text" class="input-large" value="{$bizonylat|default}" required data-errormsg="{t('Adja meg a bizonylat számát')}">
							<span id="BizonylatMsg" class="help-inline">{$hibak.bizonylat|default}</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="SzovegEdit">{t('Megjegyzés')}:</label>
						<div class="controls">
							<textarea id="SzovegEdit" name="szoveg" class="input-large">{$szoveg|default}</textarea>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn okbtn">{t('Elállás megerősítése')}</button>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="span3">
			<div class="keret">
				<div class="szurofej">{t('Hasznos információk')}</div>
				<div class="szurodoboz korbepadding">
					<ul>
						<li><a href="/statlap/aszf">{t('Általános szerződési feltételek')}</a></li>
						<li><a href="/statlap/penzvisszafizetesi-garancia">{t('Pénzvisszafizetési garancia')}</a></li>
						<li><a href="/kapcsolat">{t('Kapcsolat')}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}
