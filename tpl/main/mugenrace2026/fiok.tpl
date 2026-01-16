{extends "base.tpl"}

{block "kozep"}
<div class="container page-header static-page__header">
	<div class="row">
		<div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
			<span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
					{if ($navigator|default)}
							<a href="/" rel="v:url" property="v:title">
									{t('Home')}
							</a>
							<i class="icon arrow-right"></i>
							{foreach $navigator as $_navi}
									{if ($_navi.url|default)}
											<span typeof="v:Breadcrumb">
													<a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
															{$_navi.caption|capitalize}
													</a>
											</span>
											<i class="icon arrow-right"></i>
									{else}
											{$_navi.caption|capitalize}
									{/if}
							{/foreach}
					{/if}
			</span>
		</div>
	</div>
	<div class="row">
			<div class="col">
				<h1 class="page-header__title" typeof="v:Breadcrumb">
						<a href="/fiok" rel="v:url" property="v:title">
								{t('Fiókom')}
						</a>
				</h1>
			</div>
	</div>
</div>
<div class="container account-page">
	<div class="row">
		<div class="col offset1">
			{* <div class="form-header">
				<h2>{t('Módosítsa adatait')}</h2>
				<h4>{t('Vagy')} <a href="/" title="{t('Vásárolok')}">{t('vásároljon')}</a> {t('tovább webáruházunkból')}</h4>
			</div> *}
			<div id="adatmodositasTabbable" class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#adataim" data-toggle="tab">{t('Adataim')}</a></li>
					<li><a href="#szamlaadatok" data-toggle="tab">{t('Számlázási adatok')}</a></li>
					<li><a href="#szallitasiadatok" data-toggle="tab">{t('Szállítási adatok')}</a></li>
					<li><a href="#megrend" data-toggle="tab">{t('Megrendeléseim')}</a></li>
					<li><a href="#termekertesito" data-toggle="tab">{t('Termékértesítők')}</a></li>
					<li><a href="#jelszo" data-toggle="tab">{t('Jelszó módosítása')}</a></li>
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
									<label class="control-label" for="EmailEdit">{t('Email')}*:</label>
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
									<button type="submit" class="button primary okbtn">{t('Adatok módosítása')}</button>
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
									<div class="controls controls__address">
										<input id="SzamlazasiCimEdit" name="irszam" type="text" class="input-mini" placeholder="{t('ir.szám')}" value="{$user.irszam}">
										<input name="varos" type="text" class="input-medium" placeholder="{t('város')}" value="{$user.varos}">
										<input name="utca" type="text" class="input-large" placeholder="{t('utca')}" value="{$user.utca}">
                    <input name="hazszam" type="text" class="input-mini" placeholder="{t('házszám')}" value="{$user.hazszam}">
									</div>
								</div>
                                <div class="control-group">
                                    <label class="control-label" for="OrszagEdit">{t('Ország')}:</label>
                                    <div class="controls">
                                        <select id="OrszagEdit" name="orszag">
                                            <option value="0">{t('válasszon')}</option>
                                            {foreach $orszaglist as $orszag}
                                                <option value="{$orszag.id}"{if ($orszag.selected)} selected="selected"{/if}>{$orszag.caption}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
								<div class="form-actions">
									<button type="submit" class="button primary okbtn">{t('Adatok módosítása')}</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="szallitasiadatok">
						<div class="acc-copyszamlaadat">
						<a class="js-copyszamlaadat button bordered">{t('Számlázási adatok másolása')} </a>
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
									<div class="controls  controls__address">
										<input id="SzallitasiCimEdit" name="szallirszam" type="text" class="input-mini" placeholder="{t('ir.szám')}" value="{$user.szallirszam}">
										<input name="szallvaros" type="text" class="input-medium" placeholder="{t('város')}" value="{$user.szallvaros}">
										<input name="szallutca" type="text" class="input-large" placeholder="{t('utca')}" value="{$user.szallutca}">
                                        <input name="szallhazszam" type="text" class="input-mini" placeholder="{t('házszám')}" value="{$user.szallhazszam}">
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="button primary okbtn">{t('Adatok módosítása')}</button>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="megrend">
						{if (count($megrendeleslist)>0)}
						<table class="acc-megrendeles">
							<thead class="acc-megrendeles">
								<td>{t('Rendelésszám')}</td>
								<td>{t('Dátum')}</td>
								<td>{t('Állapot')}</td>
								<td>{t('Érték')}</td>
								<td>{t('Fuvarlevélszám')}</td>
								<td></td>
							</thead>
							<tbody class="acc-megrendeles">
								{foreach $megrendeleslist as $megr}
								<tr class="acc-megrendelesbordertop acc-megrendelestablerow js-accmegrendelesopen">
									<td>{$megr.id}</td>
									<td>{$megr.kelt}</td>
									<td>{$megr.allapotnev|default:t('ismeretlen')}</td>
									<td class="textalignright">{number_format($megr.brutto,0,'',' ')}</td>
									<td></td>
                                    <td><a href="#" class=""><img src="/themes/main/mkwcansas/img/i_down.png"></a></td>
								</tr>
								<tr class="notvisible acc-megrendelesborderbottom">
									<td colspan="6">
										<table>
											<tr>
												<td><span class="acc-megrendelescaption">{t('Számlázási cím')}:</span></td>
												<td>{$megr.szamlanev|default} {$megr.szamlairszam|default} {$megr.szamlavaros|default} {$megr.szamlautca} {$megr.szamlahazszam}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">{t('Adószám')}:</span></td>
												<td>{$megr.adoszam|default}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">{t('Szállítási cím')}:</span></td>
												<td>{$megr.szallnev|default} {$megr.szallirszam|default} {$megr.szallvaros|default} {$megr.szallutca} {$megr.szallhazszam}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">{t('Szállítási mód')}:</span></td>
												<td>{$megr.szallitasimodnev|default}</td>
											</tr>
											<tr>
												<td><span class="acc-megrendelescaption">{t('Fizetési mód')}:</span></td>
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
													<td><div class="textaligncenter"><a href="{$tetel.link}"><img src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a></div></td>
													<td><div><a href="{$tetel.link}">{$tetel.caption}</a></div>
														<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
														{$tetel.cikkszam}</td>
													<td><div class="textalignright">{number_format($tetel.bruttoegysar,0,',',' ')}</div></td>
													<td>
														<div class="textaligncenter">
															<div>{number_format($tetel.mennyiseg,0,',','')}</div>
														</div>
													</td>
													<td><div class="textalignright">{number_format($tetel.brutto,0,',',' ')}</div></td>
												</tr>
											{/foreach}
											</tbody>
										</table>
                                        <div class="textalignright bold"><b>{t('Összesen')}: {number_format($megr.brutto,0,',',' ')}</b></div>
                                        {if ($megr.megjegyzes|default)}
                                        <div class="acc-megrendelescaption">{t('Webáruház megjegyzése')}:</div>
                                        <div>{$megr.megjegyzes}</div>
                                        {/if}
                                        {if ($megr.webshopmessage|default)}
                                        <div class="acc-megrendelescaption">{t('Megjegyzés a webáruháznak')}:</div>
                                        <div>{$megr.webshopmessage}</div>
                                        {/if}
                                        {if ($megr.couriermessage|default)}
                                        <div class="acc-megrendelescaption">{t('Megjegyzés a futárnak')}:</div>
                                        <div>{$megr.couriermessage}</div>
                                        {/if}
									</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
						{else}
							{t('Ön sajnos még nem vásárolt tőlünk')}.
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
							<div class="span1"><a href="/product/{$ertesito.termek.slug}"><img src="{$imagepath}{$ertesito.termek.kiskepurl}" alt="{$ertesito.termek.caption}" title="{$ertesito.termek.caption}"></a></div>
							<div class="span4">
								<a href="/product/{$ertesito.termek.slug}">{$ertesito.termek.caption}</a>
								<div>{t('Feliratkozás dátuma')}: {$ertesito.createdstr}</div>
							</div>
							<div class="span1"><a href="#" class="js-termekertesitodel" data-id="{$ertesito.id}">{t('Leiratkozás')}</a></div>
						</div>
						{foreachelse}
							<h3>{t('Nincs termékértesítője')}</h3>
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
                                    <label class="control-label">{t('Új jelszó')}:</label>
                                    <div class="controls">
                                        <div class="chk-relative pull-left">
                                            <input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="{t('jelszó')} 1 *" data-errormsg1="{t('Adjon meg jelszót')}." data-errormsg2="{t('A két jelszó nem egyezik.')}.">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls chk-relative pull-left">
                                        <i class="span inputiconhack"></i>
                                        <input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="{t('jelszó')} 2 *">
                                    </div>
                                </div>
								<div class="form-actions">
									<button type="submit" class="button primary okbtn">{t('Adatok módosítása')}</button>
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
