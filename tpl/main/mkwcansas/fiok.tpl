{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>Módosítsa adatait</h2>
				<h4>Vagy <a href="/" title="Vásárolok">vásároljon</a> tovább webáruházunkból</h4>
			</div>
			<div id="adatmodositasTabbable" class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#adataim" data-toggle="tab">Adataim</a></li>
					<li><a href="#szamlaadatok" data-toggle="tab">Számlázási adatok</a></li>
					<li><a href="#szallitasiadatok" data-toggle="tab">Szállítási adatok</a></li>
					<li><a href="#megrend" data-toggle="tab">Megrendeléseim</a></li>
					<!--li><a href="#visszajel" data-toggle="tab">Visszajelzések</a></li>
					<li><a href="#csomag" data-toggle="tab">Csomagkövetés</a></li-->
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
									<label class="control-label" for="TelkorzetEdit">{t('Telefon')}:</label>
                                    {if ($user.telefon && (!$user.telkorzet || !$user.telszam))}
                                    <div class="controls">{t('Az ön által megadott telefonszám')}: {$user.telefon}</div>
                                    {/if}
									<div class="controls">
										<select id="TelkorzetEdit" name="telkorzet" required="required" data-errormsg="{t('Hibás telefonszám')}">
                                            <option value="">{t('válasszon')}</option>
                                            {foreach $telkorzetlist as $tk}
                                                <option value="{$tk.id}" data-hossz="{$tk.hossz}"{if ($tk.selected)} selected="selected"{/if}>{$tk.id}</option>
                                            {/foreach}
                                        </select>
                                        <input id="TelszamEdit" type="text" name="telszam" class="input-large" value="{$user.telszam}" required="required">
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
						<table class="acc-megrendeles">
							<thead class="acc-megrendeles">
								<td>Rendelésszám</td>
								<td>Dátum</td>
								<td>Állapot</td>
								<td>Érték</td>
								<td>Fuvarlevélszám</td>
								<td></td>
								<td></td>
							</thead>
							<tbody class="acc-megrendeles">
								{foreach $megrendeleslist as $megr}
								<tr class="acc-megrendelesbordertop acc-megrendelestablerow js-accmegrendelesopen">
									<td>{$megr.id}</td>
									<td>{$megr.kelt}</td>
									<td>{$megr.allapotnev|default:"ismeretlen"}</td>
									<td class="textalignright">{number_format($megr.brutto,0,'',' ')} Ft</td>
                                    <td>
                                        {if ($megr.csomagkovetolink)}
                                            <a href="{$megr.csomagkovetolink}" target="_blank">{$megr.fuvarlevelszam}</a>
                                        {else}
                                            {$megr.fuvarlevelszam}
                                        {/if}
                                    </td>
									<td>
										{if ($megr.vanmitertekelni)}
										<a href="{$megr.termekertekelesurl}" class="js-termekertekeles btn okbtn">{t('Értékelés')}</a>
										{/if}
									</td>
                                    <td><a href="#" class=""><img src="/themes/main/mkwcansas/img/i_down.png"></a></td>
								</tr>
								<tr class="notvisible acc-megrendelesborderbottom">
									<td colspan="6">
										<table>
											<tr>
												<td><span class="acc-megrendelescaption">Számlázási cím:</span></td>
												<td>{$megr.szamlanev|default} {$megr.szamlairszam|default} {$megr.szamlavaros|default} {$megr.szamlautca}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">Adószám:</span></td>
												<td>{$megr.adoszam|default}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">Szállítási cím:</span></td>
												<td>{$megr.szallnev|default} {$megr.szallirszam|default} {$megr.szallvaros|default} {$megr.szallutca}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">Szállítási mód:</span></td>
												<td>{$megr.szallitasimodnev|default}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">Fizetési mód:</span></td>
												<td>{$megr.fizmodnev|default}</td>
											</tr>
										</table>
										<table class="acc-megrendelestetellist">
											<thead class="acc-megrendelestetellist">
													<td><div class="textaligncenter">{t('Termék')}</div></td>
													<td>{t('Megnevezés, cikkszám')}</td>
													<td><div class="textalignright">{t('Egységár')}</div></td>
													<td><div class="textaligncenter">{t('Mennyiség')}</div></td>
													<td><div class="textalignright">{t('Érték')}</div></td>
											</thead>
											<tbody>
											{foreach $megr.tetellista as $tetel}
												<tr class="clickable" data-href="{$tetel.link}">
													<td><div class="textaligncenter"><a href="{$tetel.link}"><img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a></div></td>
													<td><div><a href="{$tetel.link}">{$tetel.caption}</a></div>
														<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
														{$tetel.cikkszam}</td>
													<td><div class="textalignright">{number_format($tetel.bruttoegysar,0,',',' ')} Ft</div></td>
													<td>
														<div class="textaligncenter">
															<div>{number_format($tetel.mennyiseg,0,',','')}</div>
														</div>
													</td>
													<td><div class="textalignright">{number_format($tetel.brutto,0,',',' ')} Ft</div></td>
												</tr>
											{/foreach}
											</tbody>
										</table>
                                        <div class="textalignright bold"><b>Összesen: {number_format($megr.brutto,0,',',' ')} Ft</b></div>
                                        {if ($megr.megjegyzes|default)}
                                        <div class="acc-megrendelescaption">Webáruház megjegyzése:</div>
                                        <div>{$megr.megjegyzes}</div>
                                        {/if}
                                        {if ($megr.webshopmessage|default)}
                                        <div class="acc-megrendelescaption">Megjegyzés a webáruháznak:</div>
                                        <div>{$megr.webshopmessage}</div>
                                        {/if}
                                        {if ($megr.couriermessage|default)}
                                        <div class="acc-megrendelescaption">Megjegyzés a futárnak:</div>
                                        <div>{$megr.couriermessage}</div>
                                        {/if}
									</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
						{else}
							Ön sajnos még nem vásárolt tőlünk, szomorúak vagyunk.
						{/if}
					</div>
					<!--div class="tab-pane" id="visszajel">
						Visszajelzések
					</div>
					<div class="tab-pane" id="csomag">
						Csomagkövetés
					</div-->
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
                                                        <h4>Mi az a termékértesítő?</h4>
                                                        Ha egy termékünk elfogyott a készletünkről, de Ön mégis szeretné megrendelni, akkor lehetősége van értesítést kapni arról, amikor újra elérhetővé válik.
                                                        Ehhez csak fel kell iratkoznia az adott termékre. A legközelebbi árubeérkezésről rendszerünk automatikusan kiküldi a levelet Önnek, így Ön elsőként fog erről értesülni.
                                                        <h4>Hogy tudok feliratkozni?</h4>
                                                        Nagyon egyszerűen. Csak kattintson a termék képe mellett lévő szürke "Elfogyott" gombra, és adja meg emailcímét. Minden mást rendszerünk intézi.

						{/foreach}
					</div>
					<div class="tab-pane" id="jelszo">
						<form id="JelszoChangeForm" class="form-horizontal" action="/fiok/ment/jelszo" method="post">
							<fieldset>
								<div class="control-group">
									<label class="control-label" for="RegijelszoEdit">{t('Régi jelszó')}:</label>
									<div class="controls">
										<input id="RegijelszoEdit" name="regijelszo" type="password" class="input-xlarge" placeholder="{t('régi jelszó')}">
										<input name="checkregijelszo" type="hidden" value="1">
									</div>
								</div>
                                <div class="control-group">
                                    <label class="control-label">Új jelszó:</label>
                                    <div class="controls">
                                        <div class="chk-relative pull-left">
                                            <input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="jelszó 1 *" data-errormsg1="{t('Adjon meg jelszót.')}" data-errormsg2="{t('A két jelszó nem egyezik.')}">
                                            <i class="icon-question-sign chk-tooltipbtn hidden-phone js-tooltipbtn" title="" data-original-title="Jelszó azért szükséges, hogy csak Ön férhessen hozzá személyes adataihoz. Használhat kis- és nagybetűket, valamint számokat is a jelszóban (erősen ajánlott)."></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls chk-relative pull-left">
                                        <i class="span inputiconhack"></i>
                                        <input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="jelszó 2 *">
                                        <i class="icon-question-sign chk-tooltipbtn hidden-phone js-tooltipbtn" title="" data-original-title="Azért kérjük kétszer a jelszót, hogy biztosan elkerülhessük az elgépelést."></i>
                                    </div>
                                </div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">Adatok módosítása</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}
