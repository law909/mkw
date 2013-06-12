{extends "base.tpl"}

{block "script"}
<script src="/js/main/mkwnew/bootstrap-tab.js"></script>
<script src="/js/main/mkwnew/h5f.js"></script>
{/block}

{block "kozep"}
<div class="row">
	<div class="span10 offset1">
		<div class="form-header">
			<h2>Módosítsa adatait</h2>
			<h4>Vagy menjen inkább <a href="/" title="Vásárolok">VÁSÁROLNI</a></h4>
		</div>
		<div id="adatmodositasTabbable" class="tabbable tabs-left">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#adataim" data-toggle="tab">Adataim</a></li>
				<li><a href="#jelszo" data-toggle="tab">Jelszó módosítás</a></li>
				<li><a href="#megrend" data-toggle="tab">Eddigi megrendelések</a></li>
				<li><a href="#csomag" data-toggle="tab">Csomagkövetés</a></li>
				<li><a href="#visszajel" data-toggle="tab">Visszajelzések</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="adataim">
					<form class="form-horizontal" action="/regisztral/ment" method="post">
						<fieldset>
							<div class="control-group">
								<label class="control-label" for="VezeteknevEdit">{t('Név')}:</label>
								<div class="controls">
									<input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" placeholder="{t('vezetéknév')}" required>
									<input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" placeholder="{t('keresztnév')}" required>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="EmailEdit">{t('Email cím')}:</label>
								<div class="controls">
									<input id="EmailEdit" name="email" type="email" class="input-large" required>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary">OK</button>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="tab-pane" id="jelszo">
					Jelszó
				</div>
				<div class="tab-pane" id="megrend">
					Eddigi megrendelések
				</div>
				<div class="tab-pane" id="csomag">
					Csomagkövetés
				</div>
				<div class="tab-pane" id="visszajel">
					Visszajelzések
				</div>
			</div>
		</div>
	</div>
</div>
{/block}