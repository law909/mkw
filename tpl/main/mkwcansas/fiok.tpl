{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>Módosítsa adatait</h2>
				<h4>Vagy főleg tűnjön inkább <a href="/" title="Vásárolok">vásárolni</a></h4>
			</div>
			<div id="adatmodositasTabbable" class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#adataim" data-toggle="tab">Adataim</a></li>
					<li><a href="#szamlaadatok" data-toggle="tab">Számlázási adatok</a></li>
					<li><a href="#szallitasiadatok" data-toggle="tab">Szállítási adatok</a></li>
					<li><a href="#megrend" data-toggle="tab">Megrendeléseim</a></li>
					<li><a href="#visszajel" data-toggle="tab">Visszajelzések</a></li>
					<li><a href="#csomag" data-toggle="tab">Csomagkövetés</a></li>
					<li><a href="#termekertesito" data-toggle="tab">Termékértesítők</a></li>
					<li><a href="#jelszo" data-toggle="tab">Jelszó módosítása</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="adataim">
						<form id="FiokAdataim" class="form-horizontal" action="/fiok/ment/adataim" method="post">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="VezeteknevEdit">{t('Név')}*:</label>
									<div class="controls">
										<input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" placeholder="{t('vezetéknév')}" value="{$user.vezeteknev}" required>
										<input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" placeholder="{t('keresztnév')}" value="{$user.keresztnev}" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="EmailEdit">{t('Email cím')}*:</label>
									<div class="controls">
										<input id="EmailEdit" name="email" type="email" class="input-large" value="{$user.email}" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="TelefonEdit">{t('Telefon')}:</label>
									<div class="controls">
										<input id="TelefonEdit" name="telefon" type="text" class="input-large" value="{$user.telefon}">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<label class="checkbox" for="AkciosHirlevelEdit">{t('Akciókról kérek hírlevelet')}
											<input id="AkciosHirlevelEdit" name="akcioshirlevelkell" type="checkbox" class="input-large"{if ($user.akcioshirlevelkell)} checked="checked"{/if}>
										</label>
									</div>
									<div class="controls">
										<label class="checkbox" for="UjdonsagHirlevelEdit">{t('Újdonságokról kérek hírlevelet')}
											<input id="UjdonsagHirlevelEdit" name="ujdonsaghirlevelkell" type="checkbox" class="input-large"{if ($user.ujdonsaghirlevelkell)} checked="checked"{/if}>
										</label>
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">Adatok módosítása</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="szamlaadatok">
						<form id="FiokSzamlaAdatok" class="form-horizontal" action="/fiok/ment/szamlaadatok" method="post">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="SzamlazasiNevEdit">{t('Név')}:</label>
									<div class="controls">
										<input id="SzamlazasiNevEdit" name="nev" type="text" class="input-xlarge" placeholder="{t('számlázási név')}" value="{$user.nev}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="SzamlazasiAdoszamEdit">{t('Adószám')}:</label>
									<div class="controls">
										<input id="SzamlazasiAdoszamEdit" name="adoszam" type="text" class="input-medium" placeholder="{t('adószám')}" value="{$user.adoszam}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="SzamlazasiCimEdit">{t('Számlázási cím')}:</label>
									<div class="controls">
										<input id="SzamlazasiCimEdit" name="irszam" type="text" class="input-mini" placeholder="{t('ir.szám')}" value="{$user.irszam}">
										<input name="varos" type="text" class="input-medium" placeholder="{t('város')}" value="{$user.varos}">
										<input name="utca" type="text" class="input-large" placeholder="{t('utca')}" value="{$user.utca}">
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">Adatok módosítása</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="szallitasiadatok">
						<div class="acc-copyszamlaadat">
						<a class="js-copyszamlaadat">Számlázási adatok másolása </a><i class="icon-question-sign chk-inlinetooltipbtn hidden-phone js-tooltipbtn" title="Átmásolja az adatokat onnan, felülírja ezeket itt"></i>
						</div>
						<form id="FiokSzallitasiAdatok" class="form-horizontal" action="/fiok/ment/szallitasiadatok" method="post">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="SzallitasiNevEdit">{t('Név')}:</label>
									<div class="controls">
										<input id="SzallitasiNevEdit" name="szallnev" type="text" class="input-xlarge" placeholder="{t('szállítási név')}" value="{$user.szallnev}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="SzallitasiCimEdit">{t('Szállítási cím')}:</label>
									<div class="controls">
										<input id="SzallitasiCimEdit" name="szallirszam" type="text" class="input-mini" placeholder="{t('ir.szám')}" value="{$user.szallirszam}">
										<input name="szallvaros" type="text" class="input-medium" placeholder="{t('város')}" value="{$user.szallvaros}">
										<input name="szallutca" type="text" class="input-large" placeholder="{t('utca')}" value="{$user.szallutca}">
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">Adatok módosítása</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="megrend">
						{if (count($megrendeleslist)>0)}
						<table class="acc-megrendelestable">
							<thead>
								<td>Rendelésszám</td>
								<td>Dátum</td>
								<td>Állapot</td>
								<td>Érték</td>
								<td>Fuvarlevélszám</td>
								<td></td>
							</thead>
							<tbody>
								{foreach $megrendeleslist as $megr}
								<tr{if ($megr@index mod 2==1)} class="acc-megrendelestablekiemelt"{/if}>
									<td>{$megr.id}</td>
									<td>{$megr.kelt}</td>
									<td></td>
									<td>{number_format($megr.ertek,0,'',' ')}</td>
									<td></td>
									<td><a href="#" class="js-accmegrendelesopen">V</a></td>
								</tr>
								<tr class="notvisible">
									<td>tételek</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
						{else}
							Ön sajnos még nem vásárolt tőlünk, szomorúak vagyunk.
						{/if}
					</div>
					<div class="tab-pane" id="visszajel">
						Visszajelzések
					</div>
					<div class="tab-pane" id="csomag">
						Csomagkövetés
					</div>
					<div class="tab-pane" id="termekertesito">
						{foreach $ertesitok as $ertesito}
						<div class="row js-termekertesito">
							<div class="span1"><a href="/termek/{$ertesito.termek.slug}"><img src="{$ertesito.termek.kiskepurl}" alt="{$ertesito.termek.caption}" title="{$ertesito.termek.caption}"></a></div>
							<div class="span4">
								<a href="/termek/{$ertesito.termek.slug}">{$ertesito.termek.caption}</a>
								<div>Feliratkozás dátuma: {$ertesito.createdstr}</div>
							</div>
							<div class="span1"><a href="#" class="js-termekertesitodel" data-id="{$ertesito.id}">Leiratkozás</a></div>
						</div>
						{foreachelse}
							<h3>Nincs termékértesítője</h3>
						{/foreach}
					</div>
					<div class="tab-pane" id="jelszo">
						Jelszó
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}
