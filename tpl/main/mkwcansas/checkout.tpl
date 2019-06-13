{extends "checkoutbase.tpl"}

{block "stonebody"}
<header class="checkout">
<div class="headermid whitebg">
	<div class="container">
		<div class="row">
			<div class="span12">
				<a href="/"><img src="/themes/main/mkwcansas/img/mkw-logo.png" alt="Mindent Kapni Webáruház logo" title="Mindent Kapni Webáruház"></a>
			</div>
		</div>
	</div>
</div>
</header>
<div class="container whitebg js-checkout">
	<div class="row">
		<div class="span10">
            {if ($checkouterrors)}
            <div class="row">
            <div class="span10 checkouterrorblock">
                <div class="checkouterrorblockinner">
                    {foreach $checkouterrors as $_ce}
                    <div class="checkouterror">{$_ce}</div>
                    {/foreach}
                </div>
            </div>
            </div>
            {/if}
			<form id="LoginForm" method="post" action="/login/ment"></form>
			<form id="CheckoutForm" class="" action="/checkout/ment" method="post"><fieldset>
			{$sorszam=1}
			{if (!$user.loggedin)}
			<div class="row">
				<div class="span10">
					<div class="chk-datagroupheader js-chkdatagroupheader" data-container=".js-chklogin">{$sorszam++}. Bejelentkezés</div>
					<div class="js-chklogin js-chkdatacontainer row chk-columncontainer">
						<div class="span5">
							<div class="chk-loginrightborder pull-left">
								<h5>Új vásárló</h5>
								<div class="span">
									<label class="radio">
										<input name="regkell" id="regkell" type="radio" value="1"{if ($regkell==1)}checked="checked"{/if}>
										Vásárlás vendégként (regisztráció nélkül)
									</label>
									<label class="radio">
										<input name="regkell" id="regkell" type="radio" value="2"{if ($regkell==2)}checked="checked"{/if}>
										Vásárlás regisztrációval
									</label>
									<div class="chk-courierdesc folyoszoveg">Regisztrációval olyan előnyökhöz juthat, mint például a hűségpontok gyűjtése és beváltása, rendelések nyomon követése, termékértékelések írása, és sokminden más.</div>
								</div>
								<div class="row chk-actionrow span"><a href="#block2" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallitasiadatokgh">Tovább</a></div>
							</div>
						</div>
						<div class="span5">
							<h5>Regisztrált vásárló</h5>
							{if ($showerror)}
								<h4>A bejelentkezés nem sikerült...</h4>
							{/if}
							<div class="controls chk-controloffset">
                                <label class="span3 nomargin">Email</label>
								<input name="email" type="text" form="LoginForm" class="span3 nomargin" value="{$user.email|default}">
							</div>
							<div class="controls chk-controloffset">
                                <label class="span3 nomargin">Jelszó</label>
								<input name="jelszo" type="password" form="LoginForm" class="span3 nomargin" value="">
							</div>
							<div class="row chk-actionrow span">
								<input name="c" type="hidden" form="LoginForm" value="c">
								<input type="submit" form="LoginForm" class="btn okbtn pull-right js-chkloginbtn" value="Belépés">
							</div>
						</div>
					</div>
				</div>
			</div>
			{/if}
			<div class="row">
				<div class="span10">
					<div id="block2" class="chk-datagroupheader js-chkdatagroupheader js-chkszallitasiadatokgh" data-container=".js-chkszallitasiadatok">{$sorszam++}. Szállítási és számlázási adatok<a>Módosít</a></div>
					<div class="js-chkszallitasiadatok js-chkdatacontainer">
						<small>A <span class="piros">*</span>-gal jelölt adatok kitöltése kötelező.</small>
						<h5>Kapcsolati adatok</h5>
						<div class="controls controls-row chk-controloffset">
                            <div class="span4 nomargin">
                                <label class="span4 nomargin">Vezetéknév *</label>
    							<input name="vezeteknev" type="text" class="span4 nomargin js-chkrefresh" value="{$vezeteknev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span4">
                                <label class="span4 nomargin">Keresztnév *</label>
    							<input name="keresztnev" type="text" class="span4 nomargin js-chkrefresh" value="{$keresztnev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
						</div>
						<div class="controls controls-row chk-controloffset">
                            <div class="span4 nomargin chk-relative">
                                <label class="span4 nomargin">Telefon *</label>
                                {if ($telefon && (!$telkorzet || !$telszam))}
                                    <div>{t('Az ön által megadott telefonszám')}: {$telefon}</div>
                                {/if}
                                <div class="controls">
                                    <select id="TelkorzetEdit" class="telszam js-chkrefresh" name="telkorzet" data-errormsg="{t('Hibás telefonszám')}" data-container=".js-chkszallitasiadatok">
                                        <option value="">{t('válasszon')}</option>
                                        {foreach $telkorzetlist as $tk}
                                            <option value="{$tk.id}" data-hossz="{$tk.hossz}"{if ($tk.selected)} selected="selected"{/if}>{$tk.id}</option>
                                        {/foreach}
                                    </select>
                                    <input id="TelszamEdit" class="telszam js-chkrefresh" type="text" name="telszam" value="{$telszam}" data-container=".js-chkszallitasiadatok">
                                </div>
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="Csak mobil számot adjon meg, mert a futár arra tud SMS-t küldeni. A telefonszámra azért van szükségünk, mert ezen keresztül egyeztetünk Önnel a kiszállításról, illetve a futár is így fogja tudni Önnel felvenni a kapcsolatot."></i>
                            </div>
                            <div class="span4">
                                <label class="span4 nomargin">Email *</label>
        						<input name="kapcsemail" type="text" class="span4 nomargin js-chkrefresh" value="{$email|default}" {if ($user.loggedin)}readonly {/if} data-container=".js-chkszallitasiadatok">
                            </div>
						</div>
						{if (!$user.loggedin)}
						<div class="js-checkoutpasswordcontainer">
						<div class="controls controls-row chk-controloffset js-checkoutpasswordrow">
                            <div class="span4 nomargin">
                                <label class="span4 nomargin">Jelszó 1 *</label>
    							<input name="jelszo1" type="password" class="span4 nomargin" value="" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span4 chk-relative">
                                <label class="span4 nomargin">Jelszó 2 *</label>
								<input name="jelszo2" type="password" class="span4 nomargin" value="" data-container=".js-chkszallitasiadatok">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="Adja meg kétszer jelszavát, így elkerülheti az elgépelést"></i>
                            </div>
						</div>
						</div>
						{/if}
						<h5>Szállítási adatok</h5>
                        <div class="controls chk-controloffset">
                            <div class="span10 nomargin">
                                <label class="span8 nomargin">Szállítási név</label>
                                <input name="szallnev" type="text" class="span8 js-chkrefresh" value="{$szallnev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                        </div>
                        <div class="controls controls-row chk-controloffset">
                            <div class="span2 nomargin">
                                <label class="span2 nomargin">Ir.szám *</label>
                                <input name="szallirszam" type="text" class="span2 nomargin js-chkrefresh" value="{$szallirszam|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span6">
                                <label class="span7 nomargin">Város *</label>
                                <input name="szallvaros" type="text" class="span6 nomargin js-chkrefresh" value="{$szallvaros|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                        </div>
                        <div class="controls chk-controloffset">
                            <label class="span8 nomargin">Utca *</label>
                            <input name="szallutca" type="text" class="span8 nomargin js-chkrefresh" value="{$szallutca|default}" data-container=".js-chkszallitasiadatok">
                        </div>

						<h5 class="clearboth">Számlázási adatok</h5>
						<div class="controls chk-controloffset">
							<label class="checkbox">
								<input name="szamlaeqszall" type="checkbox"{if ($szamlaeqszall|default)} checked{/if}>
								Megegyezik a szállítási adatokkal
							</label>
						</div>
						<div class="js-chkszamlaadatok{if ($szamlaeqszall|default)} notvisible{/if}">
                            <div class="controls chk-controloffset">
                                <div class="span10 nomargin">
                                    <label class="span8 nomargin">Számlázási név</label>
                                    <input name="szamlanev" type="text" class="span8 nomargin js-chkrefresh" value="{$szamlanev|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                            </div>
                            <div class="controls controls-row chk-controloffset">
                                <div class="span2 nomargin">
                                    <label class="span2 nomargin">Ir.szám *</label>
                                    <input name="szamlairszam" type="text" class="span2 nomargin js-chkrefresh" value="{$szamlairszam|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                                <div class="span6">
                                    <label class="span7 nomargin">Város *</label>
                                    <input name="szamlavaros" type="text" class="span6 nomargin js-chkrefresh" value="{$szamlavaros|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                            </div>
                            <div class="controls chk-controloffset">
                                <label class="span8 nomargin">Utca *</label>
                                <input name="szamlautca" type="text" class="span8 nomargin js-chkrefresh" value="{$szamlautca|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="controls controls-row chk-controloffset">
                                <div class="span3 nomargin chk-relative">
                                    <label class="span3 nomargin">Adószám</label>
                                    <input name="adoszam" type="text" class="span3 nomargin js-chkrefresh" value="{$adoszam|default}">
                                    <i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="Nem kötelező kitölteni az adószámot. Akkor adja meg, ha cég nevére vásárol, és szeretné, ha a számlán szerepelne ez az adat is."></i>
                                </div>
                            </div>
						</div>
						<div class="row chk-actionrow"><a href="#block3" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallmoddgh">Tovább</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div id="block3" class="chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh" data-container=".js-chkszallmod">{$sorszam++}. Szállítás<a>Módosít</a></div>
					<div class="js-chkszallmod js-chkdatacontainer">
						<div class="row">
								<div class="span2"><label class="chk-controllabel bold">Szállítási mód:</label></div>
								<div class="span8 controls js-chkszallmodlist">
									{foreach $szallitasimodlist as $szallitasimod}
                                        <label class="radio">
                                            <input type="radio" name="szallitasimod" class="js-chkrefresh{if ($szallitasimod.foxpost)} js-foxpostchk{/if}{if ($szallitasimod.tof)} js-tofchk{/if}{if ($szallitasimod.gls)} js-glschk{/if}" value="{$szallitasimod.id}"{if ($szallitasimod.selected)} checked{/if} data-caption="{$szallitasimod.caption}">
                                            {$szallitasimod.caption}
                                        </label>
                                        {if ($szallitasimod.leiras)}
                                            <div class="chk-courierdesc folyoszoveg">{$szallitasimod.leiras}</div>
                                        {/if}
                                        {if ($szallitasimod.foxpost)}
                                            <div class="js-foxpostterminalcontainer chk-foxpostcontainer"></div>
                                        {/if}
                                        {if ($szallitasimod.tof)}
                                            <div class="js-tofmapcontainer"></div>
                                        {/if}
                                        {if ($szallitasimod.gls)}
                                            <div class="js-glsterminalcontainer chk-glscontainer"></div>
                                        {/if}
									{/foreach}
                                    <input type="hidden" class="js-tofnev">
                                    <input type="hidden" class="js-tofid" name="tofid">
								</div>
						</div>
						<div class="row chk-actionrow"><a href="#block4" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkfizmoddgh">Tovább</a></div>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="span10">
                    <div id="block4" class="chk-datagroupheader js-chkdatagroupheader js-chkfizmoddgh" data-container=".js-chkfizmod">{$sorszam++}. Fizetés<a>Módosít</a></div>
                    <div class="js-chkfizmod js-chkdatacontainer">
                        <div class="row">
                            <div class="span2"><label class="chk-controllabel bold">Fizetési mód:</label></div>
                            <div class="span8 controls js-chkfizmodlist">
                            </div>
                        </div>
                        <div class="row">
                            <div class="span2"><label for="KuponEdit">Kuponkód:</label></div>
                            <div class="span4 controls"><input id="KuponEdit" class="span4" type="text" name="kupon"></div>
                            <div class="span2 js-kuponszoveg"></div>
                        </div>
                        <div class="row">
                            <div class="span2"><label for="WebshopMessageEdit" class="bold">Üzenet a webáruháznak:</label></div>
                            <div class="span7 controls"><textarea id="WebshopMessageEdit" class="span5 js-chkrefresh" name="webshopmessage" rows="2" placeholder="pl. megrendeléssel, számlázással kapcsolatos kérések">{$webshopmessage}</textarea></div>
                        </div>
                        <div class="row">
                            <div class="span2"><label for="CourierMessageEdit" class="bold">Üzenet a futár részére:</label></div>
                            <div class="span7 controls"><textarea id="CourierMessageEdit" class="span5 js-chkrefresh" name="couriermessage" rows="2" placeholder="pl. kézbesítéssel kapcsolatos kérések">{$couriermessage}</textarea></div>
                        </div>
                        <div class="row chk-actionrow"><a href="#block5" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkattekintesdgh">Tovább</a></div>
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="span10">
					<div id="block5" class="chk-datagroupheader js-chkdatagroupheader js-chkattekintesdgh" data-container=".js-chkattekintes">{$sorszam++}. Megrendelés áttekintése</div>
					<div class="js-chkattekintes js-chkdatacontainer">
						<div class="chk-columncontainer pull-left width100percent">
							<div class="col30percent">
								<div class="chk-colheader">Számlázási adatok</div>
								<div class="js-chkszamlanev"></div>
								<div class="chk-coldatabottom js-chkadoszam"></div>
								<div><span class="js-chkszamlairszam"></span>&nbsp;<span class="js-chkszamlavaros"></span></div>
								<div class="js-chkszamlautca"></div>
								<div class="chk-colheader">Kapcsolati adatok</div>
								<div><span class="js-chkvezeteknev"></span>&nbsp;<span class="js-chkkeresztnev"></span></div>
								<div class="js-chktelefon"></div>
								<div class="js-chkkapcsemail"></div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Szállítási adatok</div>
								<div class="chk-coldatabottom js-chkszallnev"></div>
								<div><span class="js-chkszallirszam"></span>&nbsp;<span class="js-chkszallvaros"></span></div>
								<div class="chk-coldatabottom js-chkszallutca"></div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Szállítás és fizetés</div>
								<div class="js-chkszallitasimod"></div>
                                <div class="js-chkcsomagterminal"></div>
								<div class="chk-coldatabottom js-chkfizetesimod"></div>
								<div class="chk-coldatabottom folyoszoveg js-chkwebshopmessage"></div>
								<div class="folyoszoveg js-chkcouriermessage"></div>
							</div>
						</div>
						<table class="table table-bordered js-chktetellist">
						</table>

						<div>
							<label class="checkbox">
								<input name="akciohirlevel" type="checkbox">
								{t('Igen, értesítsenek az akciókról')}
							</label>
							<label class="checkbox">
								<input name="ujdonsaghirlevel" type="checkbox">
								{t('Igen, értesítsenek az újdonságokról')}
							</label>
						</div>
                        <div>
                            Amennyiben pár percen belül nem kapná meg a megrendelést visszaigazoló emailünket, kérem hívjon minket a 20/342-1511-es telefonszámon, vagy kérjen visszahívást <a href='mailto:info@mindentkapni.hu'>erre a linkre</a> kattintva, és mi ellenőrizzük megrendelését.
                        </div>
						<div class="pull-right">
							<div class="chk-savecontainer">
								<div>
									<label class="checkbox">
										<input name="aszfready" type="checkbox">
										Tudomásul veszem és elfogadom az <a href="{$showaszflink}" target="empty" class="js-chkaszf">ÁSZF</a>-et<br>és a rendeléssel járó fizetési kötelezettséget
									</label>
								</div>
								<div><input type="submit" class="btn cartbtn chk-sendorderbtn js-chksendorderbtn" value="Megrendelés elküldése"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</fieldset>
			</form>
		</div>
		<div class="span2 chk-helpcontainer">
			<div class="keret">
				<div class="szurofej">{t('Kérdése van?')}</div>
				<div class="szurodoboz korbepadding">
					<ul>
						<li><a href="/statlap/p/gy-i-k-leggyakoribb-kerdesek" target="empty" class="js-chkhelp">{t('Gy.I.K. - Gyakori kérdések')}</a></li>
						<li><a href="/statlap/p/szallitasi-feltetelek-es-tudnivalok" target="empty" class="js-chkhelp">{t('Szállítási tudnivalók')}</a></li>
						<li><a href="/statlap/p/fizetesi-feltetelek" target="empty" class="js-chkhelp">{t('Fizetési tudnivalók')}</a></li>
						<li><a href="/statlap/p/husegpontok" target="empty" class="js-chkhelp">{t('Hűségpontok')}</a></li>
					</ul>
				</div>
			</div>
			<div class="keret">
				<div class="szurofej">{t('Biztonságos vásárlás')}</div>
				<div class="szurodoboz korbepadding">
					<ul>
						<li><a href="/statlap/p/penzvisszafizetesi-garancia" target="empty" class="js-chkhelp">{t('Pénzvisszafizetési garancia')}</a></li>
						<li><a href="/statlap/p/vasarloink-visszajelzesei" target="empty" class="js-chkhelp">{t('Vásárlóink visszajelzései')}</a></li>
						<li><a href="/statlap/p/vasarloi-adatok-kezelese" target="empty" class="js-chkhelp">{t('Személyes adatok védelme')}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
{/block}