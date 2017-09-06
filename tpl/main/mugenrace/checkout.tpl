{extends "checkoutbase.tpl"}

{block "stonebody"}
<header class="checkout">
<div class="headermid whitebg">
	<div class="container">
		<div class="row">
			<div class="span12">
				<a href="/"><img src="{$imagepath}{$mugenracelogo}" alt="Mugenrace webshop" title="Mugenrace webshop"></a>
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
					<div class="chk-datagroupheader js-chkdatagroupheader" data-container=".js-chklogin">{$sorszam++}. {t('Bejelentkezés')}</div>
					<div class="js-chklogin js-chkdatacontainer row chk-columncontainer">
						<div class="span5">
							<div class="chk-loginrightborder pull-left">
								<h5>{t('Új vásárló')}</h5>
								<div class="span">
									<label class="radio">
										<input name="regkell" id="regkell" type="radio" value="1"{if ($regkell==1)}checked="checked"{/if}>
										{t('Vásárlás vendégként (regisztráció nélkül)')}
									</label>
									<label class="radio">
										<input name="regkell" id="regkell" type="radio" value="2"{if ($regkell==2)}checked="checked"{/if}>
										{t('Vásárlás regisztrációval')}
									</label>
								</div>
								<div class="row chk-actionrow span"><a href="#block2" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallitasiadatokgh">{t('Tovább')}</a></div>
							</div>
						</div>
						<div class="span5">
							<h5>{t('Regisztrált vásárló')}</h5>
							{if ($showerror)}
								<h4>{t('A bejelentkezés nem sikerült')}...</h4>
							{/if}
							<div class="controls chk-controloffset">
                                <label class="span3 nomargin">{t('Email')}</label>
								<input name="email" type="text" form="LoginForm" class="span3 nomargin" value="{$user.email|default}">
							</div>
							<div class="controls chk-controloffset">
                                <label class="span3 nomargin">{t('Jelszó')}</label>
								<input name="jelszo" type="password" form="LoginForm" class="span3 nomargin" value="">
							</div>
							<div class="row chk-actionrow span">
								<input name="c" type="hidden" form="LoginForm" value="c">
								<input type="submit" form="LoginForm" class="btn okbtn pull-right js-chkloginbtn" value="{t('Belépés')}">
							</div>
						</div>
					</div>
				</div>
			</div>
			{/if}
			<div class="row">
				<div class="span10">
					<div id="block2" class="chk-datagroupheader js-chkdatagroupheader js-chkszallitasiadatokgh" data-container=".js-chkszallitasiadatok">{$sorszam++}. {t('Szállítási és számlázási adatok')}<a>{t('Módosít')}</a></div>
					<div class="js-chkszallitasiadatok js-chkdatacontainer">
						<h5>{t('Kapcsolati adatok')}</h5>
						<div class="controls controls-row chk-controloffset">
                            <div class="span4 nomargin">
                                <label class="span4 nomargin">{t('Vezetéknév')} *</label>
    							<input name="vezeteknev" type="text" class="span4 nomargin js-chkrefresh" value="{$vezeteknev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span4">
                                <label class="span4 nomargin">{t('Keresztnév')} *</label>
    							<input name="keresztnev" type="text" class="span4 nomargin js-chkrefresh" value="{$keresztnev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
						</div>
						<div class="controls controls-row chk-controloffset">
                            <div class="span4 nomargin chk-relative">
                                <label class="span4 nomargin">{t('Telefon')} *</label>
								<input name="telefon" type="text" class="span4 nomargin js-chkrefresh" value="{$telefon|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span4">
                                <label class="span4 nomargin">{t('Email')} *</label>
        						<input name="kapcsemail" type="text" class="span4 nomargin js-chkrefresh" value="{$email|default}" {if ($user.loggedin)}readonly {/if} data-container=".js-chkszallitasiadatok">
                            </div>
						</div>
						{if (!$user.loggedin)}
						<div class="js-checkoutpasswordcontainer">
						<div class="controls controls-row chk-controloffset js-checkoutpasswordrow">
                            <div class="span4 nomargin">
                                <label class="span4 nomargin">{t('Jelszó')} 1 *</label>
    							<input name="jelszo1" type="password" class="span4 nomargin" value="" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span4 chk-relative">
                                <label class="span4 nomargin">{t('Jelszó')} 2 *</label>
								<input name="jelszo2" type="password" class="span4 nomargin" value="" data-container=".js-chkszallitasiadatok">
								<i class="icon-question-sign chk-tooltipbtn hidden-phone js-chktooltipbtn" title="{t('Adja meg kétszer jelszavát, így elkerülheti az elgépelést')}"></i>
                            </div>
						</div>
						</div>
						{/if}
						<h5>{t('Szállítási adatok')}</h5>
                        <div class="controls chk-controloffset">
                            <div class="span8 nomargin">
                                <label class="span8 nomargin">{t('Ország')} *</label>
                                <select name="orszag" class="js-chkrefresh" required="required">
                                    {foreach $szallorszaglist as $f}
                                        <option value="{$f.id}"{if ($f.selected)} selected="selected"{/if}>{$f.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="controls chk-controloffset">
                            <div class="span10 nomargin">
                                <label class="span8 nomargin">{t('Szállítási név')}</label>
                                <input name="szallnev" type="text" class="span8 js-chkrefresh" value="{$szallnev|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                        </div>
                        <div class="controls controls-row chk-controloffset">
                            <div class="span2 nomargin">
                                <label class="span2 nomargin">{t('Ir.szám')} *</label>
                                <input name="szallirszam" type="text" class="span2 nomargin js-chkrefresh" value="{$szallirszam|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="span6">
                                <label class="span7 nomargin">{t('Város')} *</label>
                                <input name="szallvaros" type="text" class="span6 nomargin js-chkrefresh" value="{$szallvaros|default}" data-container=".js-chkszallitasiadatok">
                            </div>
                        </div>
                        <div class="controls chk-controloffset">
                            <label class="span8 nomargin">{t('Utca')} *</label>
                            <input name="szallutca" type="text" class="span8 nomargin js-chkrefresh" value="{$szallutca|default}" data-container=".js-chkszallitasiadatok">
                        </div>

						<h5 class="clearboth">{t('Számlázási adatok')}</h5>
						<div class="controls chk-controloffset">
							<label class="checkbox">
								<input name="szamlaeqszall" type="checkbox"{if ($szamlaeqszall|default)} checked{/if}>
                                {t('Megegyezik a szállítási adatokkal')}
							</label>
						</div>
						<div class="js-chkszamlaadatok{if ($szamlaeqszall|default)} notvisible{/if}">
                            <div class="controls chk-controloffset">
                                <div class="span10 nomargin">
                                    <label class="span8 nomargin">{t('Számlázási név')}</label>
                                    <input name="szamlanev" type="text" class="span8 nomargin js-chkrefresh" value="{$szamlanev|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                            </div>
                            <div class="controls controls-row chk-controloffset">
                                <div class="span2 nomargin">
                                    <label class="span2 nomargin">{t('Ir.szám')} *</label>
                                    <input name="szamlairszam" type="text" class="span2 nomargin js-chkrefresh" value="{$szamlairszam|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                                <div class="span6">
                                    <label class="span7 nomargin">{t('Város')} *</label>
                                    <input name="szamlavaros" type="text" class="span6 nomargin js-chkrefresh" value="{$szamlavaros|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                                </div>
                            </div>
                            <div class="controls chk-controloffset">
                                <label class="span8 nomargin">{t('Utca')} *</label>
                                <input name="szamlautca" type="text" class="span8 nomargin js-chkrefresh" value="{$szamlautca|default}" {if ($szamlaeqszall|default)}disabled {/if}data-container=".js-chkszallitasiadatok">
                            </div>
                            <div class="controls controls-row chk-controloffset">
                                <div class="span3 nomargin chk-relative">
                                    <label class="span3 nomargin">{t('Adószám')}</label>
                                    <input name="adoszam" type="text" class="span3 nomargin js-chkrefresh" value="{$adoszam|default}">
                                </div>
                            </div>
						</div>
						<div class="row chk-actionrow"><a href="#block3" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallmoddgh">{t('Tovább')}</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div id="block3" class="chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh" data-container=".js-chkszallmod">{$sorszam++}. {t('Szállítás és fizetés')}<a>{t('Módosít')}</a></div>
					<div class="js-chkszallmod js-chkdatacontainer">
						<div class="row">
								<div class="span2"><label class="chk-controllabel bold">{t('Szállítási mód')}:</label></div>
								<div class="span3 controls js-chkszallmodlist">
									{foreach $szallitasimodlist as $szallitasimod}
                                        <label class="radio">
                                            <input type="radio" name="szallitasimod" class="js-chkrefresh{if ($szallitasimod.foxpost)} js-foxpostchk{/if}" value="{$szallitasimod.id}"{if ($szallitasimod.selected)} checked{/if} data-caption="{$szallitasimod.caption}">
                                            {$szallitasimod.caption}
                                        </label>
                                        {if ($szallitasimod.leiras)}
                                            <div class="chk-courierdesc folyoszoveg">{$szallitasimod.leiras}</div>
                                        {/if}
                                        {if ($szallitasimod.foxpost)}
                                            <div class="js-foxpostterminalcontainer chk-foxpostcontainer"></div>
                                        {/if}
									{/foreach}
								</div>
								<div class="span2"><label class="chk-controllabel bold">{t('Fizetési mód')}:</label></div>
								<div class="span3 controls js-chkfizmodlist">
								</div>
						</div>
                        <div class="row">
                            <div class="span2"><label for="KuponEdit">{t('Kuponkód')}:</label></div>
                            <div class="span3 controls"><input id="KuponEdit" class="span3" type="text" name="kupon"></div>
                            <div class="span2 js-kuponszoveg"></div>
                        </div>
						<div class="row">
							<div class="span2"><label for="WebshopMessageEdit" class="bold">{t('Üzenet a webáruháznak')}:</label></div>
							<div class="span7 controls"><textarea id="WebshopMessageEdit" class="span5 js-chkrefresh" name="webshopmessage" rows="2">{$webshopmessage}</textarea></div>
						</div>
						<div class="row">
							<div class="span2"><label for="CourierMessageEdit" class="bold">{t('Üzenet a futár részére')}:</label></div>
							<div class="span7 controls"><textarea id="CourierMessageEdit" class="span5 js-chkrefresh" name="couriermessage" rows="2">{$couriermessage}</textarea></div>
						</div>
						<div class="row chk-actionrow"><a href="#block4" class="btn okbtn pull-right js-chkopenbtn" data-datagroupheader=".js-chkattekintesdgh">{t('Tovább')}</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div id="block4" class="chk-datagroupheader js-chkdatagroupheader js-chkattekintesdgh" data-container=".js-chkattekintes">{$sorszam++}. {t('Megrendelés áttekintése')}</div>
					<div class="js-chkattekintes js-chkdatacontainer">
						<div class="chk-columncontainer pull-left width100percent">
							<div class="col30percent">
								<div class="chk-colheader">{t('Számlázási adatok')}</div>
								<div class="js-chkszamlanev"></div>
								<div class="chk-coldatabottom js-chkadoszam"></div>
								<div><span class="js-chkszamlairszam"></span>&nbsp;<span class="js-chkszamlavaros"></span></div>
								<div class="js-chkszamlautca"></div>
								<div class="chk-colheader">{t('Kapcsolati adatok')}</div>
								<div><span class="js-chkvezeteknev"></span>&nbsp;<span class="js-chkkeresztnev"></span></div>
								<div class="js-chktelefon"></div>
								<div class="js-chkkapcsemail"></div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">{t('Szállítási adatok')}</div>
								<div class="chk-coldatabottom js-chkszallnev"></div>
                                <div><span class="js-chkorszag"></span></div>
								<div><span class="js-chkszallirszam"></span>&nbsp;<span class="js-chkszallvaros"></span></div>
								<div class="chk-coldatabottom js-chkszallutca"></div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">{t('Szállítás és fizetés')}</div>
								<div class="js-chkszallitasimod"></div>
                                <div class="js-chkfoxpostterminal"></div>
								<div class="chk-coldatabottom js-chkfizmod"></div>
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
						<div class="pull-right">
							<div class="chk-savecontainer">
								<div>
									<label class="checkbox">
										<input name="aszfready" type="checkbox">
                                        {if ($locale === 'hu')}
										Tudomásul veszem és elfogadom az <a href="{$showaszflink}" target="empty" class="js-chkaszf">ÁSZF</a>-et<br>és a rendeléssel járó fizetési kötelezettséget
                                        {elseif ($locale === 'en')}
                                        I have read and agree to the terms of the agreement.
                                        {/if}
									</label>
								</div>
								<div><input type="submit" class="btn cartbtn chk-sendorderbtn js-chksendorderbtn" value="{t('Megrendelés elküldése')}"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</fieldset>
			</form>
		</div>
	</div>
</div>
{/block}