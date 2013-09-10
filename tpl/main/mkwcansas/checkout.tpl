{extends "basestone.tpl"}

{block "stonebody"}
<header class="checkout">
<div class="headermid">
	<div class="container">
		<div class="row">
			<div class="span12">
				<a href="/"><img src="/themes/main/mkwcansas/img/mkw-logo.png" alt="Mindent Kapni Webáruház logo" title="Mindent Kapni Webáruház"></a>
			</div>
		</div>
	</div>
</div>
</header>
<div class="container js-checkout">
	<div class="row">
		<div class="span10">
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
										<input name="regkell" id="regkell" type="radio" value="1">
										Vásárlás vendégként (regisztráció nélkül)
									</label>
									<label class="radio">
										<input name="regkell" id="regkell" type="radio" value="2">
										Vásárlás regisztrációval
									</label>
									<div class="chk-courierdesc folyoszoveg">Regisztrációval olyan előnyökhöz juthat, mint például a hűségpontok gyűjtése és beváltása, rendelések nyomon követése, termékértékelések írása, és sokminden más.</div>
								</div>
								<div class="row chk-actionrow span"><a class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallitasiadatokgh">Tovább</a></div>
							</div>
						</div>
						<div class="span5">
							<h5>Regisztrált vásárló</h5>
							{if ($showerror)}
								<h4>A bejelentkezés nem sikerült.</h4>
							{/if}
							<div class="controls chk-controloffset">
								<input name="email" type="email" form="LoginForm" class="span3" placeholder="{t('email')} *" value="{$user.email|default}" required>
							</div>
							<div class="controls chk-controloffset">
								<input name="jelszo" type="password" form="LoginForm" class="span3" placeholder="{t('jelszó')} *" value="">
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
					<div class="chk-datagroupheader js-chkdatagroupheader js-chkszallitasiadatokgh" data-container=".js-chkszallitasiadatok">{$sorszam++}. Szállítási és számlázási adatok<a>Módosít</a></div>
					<div class="js-chkszallitasiadatok js-chkdatacontainer">
						<small>A <span class="piros">*</span>-gal jelölt adatok kitöltése kötelező.</small>
						<h5>Kapcsolati adatok</h5>
						<div class="controls controls-row chk-controloffset">
							<input name="vezeteknev" type="text" class="span4 js-chkrefresh" placeholder="{t('vezetéknév')} *" value="{$user.vezeteknev|default}" required data-container=".js-chkszallitasiadatok">
							<input name="keresztnev" type="text" class="span4 js-chkrefresh" placeholder="{t('keresztnév')} *" value="{$user.keresztnev|default}" required data-container=".js-chkszallitasiadatok">
						</div>
						<div class="controls controls-row chk-controloffset">
							<div class="chk-relative pull-left chk-tooltippedcontainer">
								<input name="telefon" type="text" class="span4 js-chkrefresh" placeholder="{t('telefon')} *" value="{$user.telefon|default}" required data-errormsg="Kérem adja meg a telefonszámát" data-container=".js-chkszallitasiadatok">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="A telefonszámra azért van szükségünk, mert ezen keresztül egyeztetünk Önnel a kiszállításról, illetve a futár is így fogja tudni Önnel felvenni a kapcsolatot."></i>
							</div>
							<i class="span inputiconhack"></i>
							<input name="kapcsemail" type="email" class="span4 js-chkrefresh" placeholder="{t('email')} *" value="{$user.email|default}" required {if ($user.loggedin)}disabled {/if}data-errormsg="Kérem adja meg az emailcímét" data-container=".js-chkszallitasiadatok">
						</div>
						{if (!$user.loggedin)}
						<div class="js-checkoutpasswordcontainer">
						<div class="controls controls-row chk-controloffset js-checkoutpasswordrow">
							<input name="jelszo1" type="password" class="span4" placeholder="{t('jelszó')} 1 *" value="" required data-errormsg1="Adjon meg jelszót" data-errormsg2="A két jelszó nem egyezik" data-container=".js-chkszallitasiadatok">
							<div class="chk-relative pull-left chk-tooltippedcontainer">
								<i class="span inputiconhack"></i>
								<input name="jelszo2" type="password" class="span4" placeholder="{t('jelszó')} 2 *" value="" required data-container=".js-chkszallitasiadatok">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="Adja meg kétszer jelszavát, így elkerülheti az elgépelést"></i>
							</div>
						</div>
						</div>
						{/if}
						<h5>Számlázási adatok</h5>
						<div class="controls chk-controloffset js-szamlasave">
							<label class="checkbox">
								<input name="szamlasave" type="checkbox">
								Ezután mindig ezt fogom használni <i class="icon-question-sign chk-inlinetooltipbtn hidden-phone js-chktooltipbtn" title="Elmentjük az adatbázisba"></i>
							</label>
						</div>
						<div class="controls chk-controloffset">
							<input name="szamlanev" type="text" class="span8 js-chkrefresh" placeholder="{t('számlázási név')}" value="{$user.szamlanev|default}" data-container=".js-chkszallitasiadatok">
						</div>
						<div class="controls controls-row chk-controloffset">
							<input name="szamlairszam" type="text" class="span2 js-chkrefresh" placeholder="{t('ir.szám')} *" value="{$user.szamlairszam|default}" required data-errormsg="Kérem adja meg az irányítószámát" data-container=".js-chkszallitasiadatok">
							<i class="span inputiconhack"></i>
							<input name="szamlavaros" type="text" class="span6 js-chkrefresh" placeholder="{t('város')} *" value="{$user.szamlavaros|default}" required data-errormsg="Kérem adja meg a várost" data-container=".js-chkszallitasiadatok">
						</div>
						<div class="controls chk-controloffset">
							<input name="szamlautca" type="text" class="span8 js-chkrefresh" placeholder="{t('utca')} *" value="{$user.szamlautca|default}" required data-errormsg="Kérem adja meg az utcát" data-container=".js-chkszallitasiadatok">
						</div>
						<div class="controls chk-controloffset">
							<div class="chk-relative pull-left chk-tooltippedcontainer">
								<input name="adoszam" type="text" class="span3 js-chkrefresh" placeholder="{t('adószám')}" value="{$user.adoszam|default}">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="Nem kötelező kitölteni az adószámot. Akkor adja meg, ha cég nevére vásárol, és szeretné, ha a számlán szerepelne ez az adat is."></i>
							</div>
						</div>
						<h5 class="clearboth">Szállítási adatok</h5>
						<div class="controls chk-controloffset">
							<label class="checkbox">
								<input name="szamlaeqszall" type="checkbox"{if ($user.szalladategyezik|default)} checked{/if}>
								Megegyezik a számlázási adatokkal
							</label>
						</div>
						<div class="js-chkszamlaadatok{if ($user.szalladategyezik|default)} notvisible{/if}">
							<div class="controls chk-controloffset js-szallsave">
								<label class="checkbox">
									<input name="szallsave" type="checkbox">
									Ezután mindig ezt fogom használni <i class="icon-question-sign chk-inlinetooltipbtn hidden-phone js-chktooltipbtn" title="Elmentjük az adatbázisba"></i>
								</label>
							</div>
							<div class="controls chk-controloffset">
								<input name="szallnev" type="text" class="span8 js-chkrefresh" placeholder="{t('szállítási név')}" value="{$user.szallnev|default}" data-orgdata="{$user.szallnev|default}" data-container=".js-chkszallitasiadatok">
							</div>
							<div class="controls controls-row chk-controloffset">
								<input name="szallirszam" type="text" class="span2 js-chkrefresh" placeholder="{t('ir.szám')} *" value="{$user.szallirszam|default}" data-orgdata="{$user.szallirszam|default}" required {if ($user.szalladategyezik|default)}disabled {/if}data-errormsg="Kérem adja meg az irányítószámát" data-container=".js-chkszallitasiadatok">
								<i class="span inputiconhack"></i>
								<input name="szallvaros" type="text" class="span6 js-chkrefresh" placeholder="{t('város')} *" value="{$user.szallvaros|default}" data-orgdata="{$user.szallvaros|default}" required {if ($user.szalladategyezik|default)}disabled {/if}data-errormsg="Kérem adja meg a várost" data-container=".js-chkszallitasiadatok">
							</div>
							<div class="controls chk-controloffset">
								<input name="szallutca" type="text" class="span8 js-chkrefresh" placeholder="{t('utca')} *" value="{$user.szallutca|default}" data-orgdata="{$user.szallutca|default}" required {if ($user.szalladategyezik|default)}disabled {/if}data-errormsg="Kérem adja meg az utcát" data-container=".js-chkszallitasiadatok">
							</div>
						</div>
						<div class="row chk-actionrow"><a class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallmoddgh">Tovább</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div class="chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh" data-container=".js-chkszallmod">{$sorszam++}. Szállítás és fizetés<a>Módosít</a></div>
					<div class="js-chkszallmod js-chkdatacontainer">
						<div class="row">
								<div class="span2"><label class="chk-controllabel bold">Szállítási mód:</label></div>
								<div class="span3 controls js-chkszallmodlist">
									{foreach $szallitasimodlist as $szallitasimod}
									<label class="radio">
										<input type="radio" name="szallitasimod" class="js-chkrefresh" value="{$szallitasimod.id}"{if ($szallitasimod.selected)} checked{/if} data-caption="{$szallitasimod.caption}">
										{$szallitasimod.caption}
									</label>
									{if ($szallitasimod.leiras)}
									<div class="chk-courierdesc folyoszoveg">{$szallitasimod.leiras}</div>
									{/if}
									{/foreach}
								</div>
								<div class="span2"><label class="chk-controllabel bold">Fizetési mód:</label></div>
								<div class="span3 controls js-chkfizmodlist">
								</div>
						</div>
						<div class="row">
							<div class="span2"><label for="WebshopMessageEdit" class="bold">Üzenet a webáruháznak:</label></div>
							<div class="span7 controls"><textarea id="WebshopMessageEdit" class="span5 js-chkrefresh" name="webshopmessage" type="text" rows="2" placeholder="pl. megrendeléssel, számlázással kapcsolatos kérések"></textarea></div>
						</div>
						<div class="row">
							<div class="span2"><label for="CourierMessageEdit" class="bold">Üzenet a futár részére:</label></div>
							<div class="span7 controls"><textarea id="CourierMessageEdit" class="span5 js-chkrefresh" name="couriermessage" type="text" rows="2" placeholder="pl. kézbesítéssel kapcsolatos kérések"></textarea></div>
						</div>
						<div class="row chk-actionrow"><a class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkattekintesdgh">Tovább</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div class="chk-datagroupheader js-chkdatagroupheader js-chkattekintesdgh" data-container=".js-chkattekintes">{$sorszam++}. Megrendelés áttekintése</div>
					<div class="js-chkattekintes js-chkdatacontainer">
						<div class="chk-columncontainer pull-left width100percent">
							<div class="col30percent">
								<div class="chk-colheader">Számlázási adatok</div>
								<div class="js-chkszamlanev">{$user.szamlanev|default}</div>
								<div class="chk-coldatabottom js-chkadoszam">{$user.adoszam|default}</div>
								<div><span class="js-chkszamlairszam">{$user.szamlairszam|default}</span>&nbsp;<span class="js-chkszamlavaros">{$user.szamlavaros|default}</span></div>
								<div class="js-chkszamlautca">{$user.szamlautca|default}</div>
								<div class="chk-colheader">Kapcsolati adatok</div>
								<div><span class="js-chkvezeteknev">{$user.vezeteknev|default}</span>&nbsp;<span class="js-chkkeresztnev">{$user.keresztnev|default}</span></div>
								<div class="js-chktelefon">{$user.telefon|default}</div>
								<div class="js-chkkapcsemail">{$user.email|default}</div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Szállítási adatok</div>
								<div class="chk-coldatabottom js-chkszallnev">{$user.szallnev|default}</div>
								<div><span class="js-chkszallirszam">{$user.szallirszam|default}</span>&nbsp;<span class="js-chkszallvaros">{$user.szallvaros|default}</span></div>
								<div class="chk-coldatabottom js-chkszallutca">{$user.szallutca|default}</div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Szállítás és fizetés</div>
								<div class="js-chkszallitasimod"></div>
								<div class="chk-coldatabottom js-chkfizmod"></div>
								<div class="chk-coldatabottom folyoszoveg js-chkwebshopmessage"></div>
								<div class="folyoszoveg js-chkcouriermessage"></div>
							</div>
						</div>
						<table class="table table-bordered js-chktetellist">
							{include 'checkouttetellist.tpl'}
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
						<div class="pull-right">
							<div class="chk-savecontainer">
								<div>
									<label class="checkbox">
										<input name="aszfready" type="checkbox">
										Elolvastam és elfogadom az <a href="{$showaszflink}" target="empty" class="js-chkaszf">ÁSZF</a>-et
									</label>
								</div>
								<div><input type="button" class="btn cartbtn chk-sendorderbtn js-chksendorderbtn" value="Megrendelés elküldése"></div>
								<input type="submit" class="js-checkoutsubmit notvisible">
							</div>
						</div>
					</div>
				</div>
			</div>
			</fieldset>
			</form>
		</div>
		<div class="span2">
			<div>
				<h5>Hívjon és segítünk!</h5>
				<div>+36 20 342 1511</div>
			</div>
		</div>
	</div>
</div>
{/block}