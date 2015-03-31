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
			<form id="CheckoutPayForm" class="" action="/checkout/pay/ment" method="post"><fieldset>
			<div class="row">
				<div class="span10">
					<div id="block1" class="chk-datagroupheader js-chkdatagroupheader js-chkfizetesiadatokgh" data-container=".js-chkfizetesiadatok">{$fizmodnev} adatok</div>
					<div class="js-chkfizetesiadatok js-chkdatacontainer">
						<h5>Adja meg fizetési adatait, és nyomja meg a Fizetés gombot.</h5>
						<div class="controls controls-row chk-controloffset">
                            <div class="span4 nomargin">
                                <label class="span4 nomargin">Mobil telefonszám</label>
    							<input name="mobilszam" type="text" class="span4 nomargin js-chkrefresh" value="{$mobilszam|default}" data-container=".js-chkfizetesiadatok">
                            </div>
                            <div class="span4">
                                <label class="span4 nomargin">Mobil fizetési azonosító</label>
    							<input name="fizazon" type="text" class="span4 nomargin js-chkrefresh" value="{$keresztnev|default}" data-container=".js-chkfizetesiadatok">
                            </div>
						</div>
						<div class="pull-right">
							<div class="chk-savecontainer">
                                <input name="megrendelesszam" type="hidden" value="{$megrendelesszam}">
								<div><input type="submit" class="btn cartbtn chk-sendorderbtn" value="Fizetés"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</fieldset>
			</form>
			<form id="CheckoutFizmodForm" class="" action="/checkout/newfizmod/ment" method="post"><fieldset>
            <div class="row">
                <div class="span10">
					<div id="block2" class="chk-datagroupheader js-chkdatagroupheader js-chkfizmodadatokgh" data-container=".js-chkfizmodadatok">Fizetési módok</div>
                    <div class="js-chkfizmodadatok js-chkdatacontainer">
                        <h5>Amennyiben mégis más módon szeretne fizetni, válassza ki a fizetési módot és nyomja meg a Mentés gombot.</h5>
						<div class="controls controls-row chk-controloffset">
                        {$fizmodlist}
                        </div>
                        <input name="megrendelesszam" type="hidden" value="{$megrendelesszam}">
                    </div>
                    <div class="pull-right">
                        <div class="chk-savecontainer">
                            <div><input type="submit" class="btn cartbtn chk-sendorderbtn" value="Mentés"></div>
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
