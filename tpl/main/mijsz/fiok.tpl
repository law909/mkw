{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>{t('Módosítsd adataidat')}</h2>
			</div>
			<div id="adatmodositasTabbable" class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#adataim" data-toggle="tab">{t('Adataim')}</a></li>
					<li><a href="#szamlaadatok" data-toggle="tab">{t('Számlázási adatok')}</a></li>
                    <li><a href="#oklevelek" data-toggle="tab">{t('Oklevelek')}</a></li>
                    <li><a href="#oralatogatasok" data-toggle="tab">{t('Óralátogatások')}</a></li>
                    <li><a href="#tanitas" data-toggle="tab">{t('Oktatott órák')}</a></li>
                    <li><a href="#pune" data-toggle="tab">{t('Pune látogatások')}</a></li>
                    <li><a href="#szamla" data-toggle="pill">{t('Számláim')}</a></li>
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
                                    <label class="control-label" for="MiotaJogazikEdit">{t('Mióta jógázol Iyengar metódus szerint (év)')}:</label>
                                    <div class="controls">
                                        <input id="MiotaJogazikEdit" name="mijszmiotajogazik" type="text" class="input-large" value="{$user.mijszmiotajogazik}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="MiotaTanitEdit">{t('Mióta tanítasz Iyengar metódusban (év)')}:</label>
                                    <div class="controls">
                                        <input id="MiotaTanitEdit" name="mijszmiotatanit" type="text" class="input-large" value="{$user.mijszmiotatanit}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="MemberBesidesHuEdit">{t('Más Iyengar Szövetségbeli tagságod')}:</label>
                                    <div class="controls">
                                        <input id="MemberBesidesHuEdit" name="mijszmembershipbesideshu" type="text" class="input-large" value="{$user.mijszmembershipbesideshu}" title="{t('Írd be vesszővel elválasztva a szövetségek nevét')}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="BusinessEdit">{t('Stúdiód neve')}:</label>
                                    <div class="controls">
                                        <input id="BusinessEdit" name="mijszbusiness" type="text" class="input-large" value="{$user.mijszbusiness}" title="{t('Írd be a stúdiód nevét, ha van')}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="WeboldalEdit">{t('Weboldal')}:</label>
                                    <div class="controls">
                                        <input id="WeboldalEdit" name="honlap" type="text" class="input-large" value="{$user.honlap}" title="{t('Írd be a weboldaladat vagy a stúdiód weboldalát')}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="MunkahelyneveEdit">{t('Munkahelyed')}:</label>
                                    <div class="controls">
                                        <input id="MunkahelyneveEdit" name="munkahelyneve" type="text" class="input-large" value="{$user.munkahelyneve}" title="{t('Írd be a munkahelyed nevét, ha van')}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="FoglalkozasEdit">{t('Foglalkozás')}:</label>
                                    <div class="controls">
                                        <input id="FoglalkozasEdit" name="foglalkozas" type="text" class="input-large" value="{$user.foglalkozas}" title="{t('Írd be a foglalkozásodat')}">
                                    </div>
                                </div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">{t('Mentés')}</button>
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
                                        <input name="hazszam" type="text" class="input-mini" placeholder="{t('házszám')}" value="{$user.hazszam}">
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">{t('Mentés')}</button>
								</div>
							</fieldset>
						</form>
					</div>
                    <div class="tab-pane" id="oklevelek">
                        <form id="FiokOklevelek" class="form-horizontal" action="/fiok/ment/oklevelek" method="post">
                            <fieldset>
                                {foreach $user.mijszoklevelek as $mijszoklevel}
                                    {include "okleveledit.tpl"}
                                {/foreach}
                                <div class="form-actions">
                                    <a class="js-mijszoklevelnewbutton btn graybtn" href="#">{t("Új oklevél")}</a>
                                    <button type="submit" class="btn okbtn">{t('Mentés')}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="tab-pane" id="oralatogatasok">
                        <form id="FiokOralatogatasok" class="form-horizontal" action="/fiok/ment/oralatogatasok" method="post">
                            <fieldset>
                                {foreach $user.mijszoralatogatas as $mijszoralatogatas}
                                    {include "oralatogatasedit.tpl"}
                                {/foreach}
                                <div class="form-actions">
                                    <a class="js-mijszoralatogatasnewbutton btn graybtn" href="#">{t("Új óralátogatás")}</a>
                                    <button type="submit" class="btn okbtn">{t('Mentés')}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="tab-pane" id="tanitas">
                        <form id="FiokTanitas" class="form-horizontal" action="/fiok/ment/tanitas" method="post">
                            <fieldset>
                                {foreach $user.mijsztanitas as $mijsztanitas}
                                    {include "tanitasedit.tpl"}
                                {/foreach}
                                <div class="form-actions">
                                    <a class="js-mijsztanitasnewbutton btn graybtn" href="#">{t("Új óra")}</a>
                                    <button type="submit" class="btn okbtn">{t('Mentés')}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="tab-pane" id="pune">
                        <form id="FiokPune" class="form-horizontal" action="/fiok/ment/pune" method="post">
                            <fieldset>
                                {foreach $user.mijszpune as $mijszpune}
                                    {include "puneedit.tpl"}
                                {/foreach}
                                <div class="form-actions">
                                    <a class="js-mijszpunenewbutton btn graybtn" href="#">{t("Új látogatás")}</a>
                                    <button type="submit" class="btn okbtn">{t('Mentés')}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="tab-pane" id="szamla">
                        {if (count($szamlalist)>0)}
                            <table class="acc-megrendeles">
                                <thead class="acc-megrendeles">
                                <td>{t('Számlaszám')}</td>
                                <td>{t('Kelt')}</td>
                                <td class="textalignright">{t('Érték')}</td>
                                <td></td>
                                <td></td>
                                </thead>
                                <tbody class="acc-megrendeles">
                                {foreach $szamlalist as $szamla}
                                    <tr class="acc-megrendelesbordertop acc-megrendelestablerow js-accmegrendelesopen">
                                        <td>{$szamla.id}</td>
                                        <td>{$szamla.kelt}</td>
                                        <td class="textalignright acc-price">{number_format($szamla.brutto, 2, '.', ' ')} {$szamla.valutanemnev}</td>
                                        <td><a href="/szamlaprint?id={$szamla.id}" target="_blank">{t('Nyomtat')}</a></td>
                                        <td><a href="#" class="js-accmegrendelesopen"><img src="/themes/main/mkwcansas/img/i_down.png"></a></td>
                                    </tr>
                                    <tr class="notvisible acc-megrendelesborderbottom">
                                        <td colspan="5">
                                            <table>
                                                <tr>
                                                    <td><span class="acc-megrendelescaption">{t('Számlázási cím')}:</span></td>
                                                    <td>{$szamla.szamlanev|default} {$szamla.szamlairszam|default} {$szamla.szamlavaros|default} {$szamla.szamlautca} {$szamla.szamlahazszam}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="acc-megrendelescaption">{t('Adószám')}:</span></td>
                                                    <td>{$szamla.adoszam|default}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="acc-megrendelescaption">{t('Fizetési mód')}:</span></td>
                                                    <td>{$szamla.fizmodnev|default}</td>
                                                </tr>
                                            </table>
                                            <table class="acc-megrendelestetellist">
                                                <thead class="acc-megrendelestetellist">
                                                <td>{t('Item')}</td>
                                                <td><div class="textalignright">{t('Egységár')}</div></td>
                                                <td><div class="textaligncenter">{t('Mennyiség')}</div></td>
                                                <td><div class="textalignright">{t('Érték')}</div></td>
                                                </thead>
                                                <tbody>
                                                {foreach $szamla.tetellista as $tetel}
                                                    <tr class="clickable" data-href="{$tetel.link}">
                                                        <td><div>{$tetel.caption}</div>
                                                            <div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
                                                            {$tetel.cikkszam}</td>
                                                        <td><div class="textalignright">{number_format($tetel.bruttoegysar, 2, ',', ' ')} {$tetel.valutanemnev}</div></td>
                                                        <td>
                                                            <div class="textaligncenter">
                                                                <div>{number_format($tetel.mennyiseg,0,',','')}</div>
                                                            </div>
                                                        </td>
                                                        <td><div class="textalignright">{number_format($tetel.brutto, 2, ',', ' ')} {$tetel.valutanemnev}</div></td>
                                                    </tr>
                                                {/foreach}
                                                </tbody>
                                            </table>
                                            <div class="textalignright bold"><b>{t('Összesen')}: {number_format($szamla.fizetendo, 2, ',', ' ')} {$tetel.valutanemnev}</b></div>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        {else}
                            {t('Még nincs számlája')}.
                        {/if}
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
                                            <input id="Jelszo1Edit" name="jelszo1" type="password" class="span" required placeholder="{t('jelszó')} *" data-errormsg1="{t('Adjon meg jelszót')}." data-errormsg2="{t('A két jelszó nem egyezik.')}.">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls chk-relative pull-left">
                                        <i class="span inputiconhack"></i>
                                        <input id="Jelszo2Edit" name="jelszo2" type="password" class="span" required placeholder="{t('jelszó megismétlése')} *">
                                    </div>
                                </div>
								<div class="form-actions">
									<button type="submit" class="btn okbtn">{t('Mentés')}</button>
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
