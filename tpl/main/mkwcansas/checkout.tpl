{extends "basestone.tpl"}

{block "stonebody"}
<header>
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
<div class="container">
	<div class="row">
		<div class="span10">
			<form id="FiokSzamlaAdatok" class="" action="" method="post"><fieldset>
			<div class="row">
				<div class="span5">
					<div class="chk-datagroupheader js-chkdatagroupheader" data-container=".js-chkszallitasiadatok, .js-chkszamlazasiadatok">Szállítási adatok</div>
					<div class="js-chkszallitasiadatok js-chkdatacontainer">
						<div class="controls">
							<input name="szallnev" type="text" class="span5" placeholder="{t('szállítási név')}" value="{$user.szallnev}">
						</div>
						<div class="controls controls-row">
							<input name="szallirszam" type="text" class="span1" placeholder="{t('ir.szám')}" value="{$user.szallirszam}">
							<input name="szallvaros" type="text" class="span4" placeholder="{t('város')}" value="{$user.szallvaros}">
						</div>
						<div class="controls">
							<input name="szallutca" type="text" class="span5" placeholder="{t('utca')}" value="{$user.szallutca}">
						</div>
						<div class="controls">
							<input name="szallkapcsnev" type="text" class="span5" placeholder="{t('kapcsolattartó neve')}" value="{$user.szallutca}">
						</div>
						<div class="controls">
							<input name="szallkapcsemail" type="text" class="span5" placeholder="{t('kapcsolattartó email címe')}" value="{$user.szallutca}">
						</div>
						<div class="controls">
							<input name="szallkapcstelefon" type="text" class="span5" placeholder="{t('kapcsolattartó telefonszáma')}" value="{$user.szallutca}">
						</div>
					</div>
				</div>
				<div class="span5">
					<div class="chk-datagroupheader js-chkdatagroupheader" data-container=".js-chkszallitasiadatok, .js-chkszamlazasiadatok">Számlázási adatok</div>
					<div class="js-chkszamlazasiadatok js-chkdatacontainer">
						<div class="controls">
							<label class="checkbox">
								<input name="szamlaeqszall" type="checkbox" checked>
								Megegyezik a szállítási adatokkal</span>
							</label>
						</div>
						<div class="controls">
							<input class="span5" name="szamlanev" type="text" class="span5" placeholder="{t('számlázási név')}" value="{$user.szamlanev}">
						</div>
						<div class="controls">
							<input class="span3" name="szamlaadoszam" type="text" placeholder="{t('adószám')}" value="{$user.szamlaadoszam}">
						</div>
						<div class="controls controls-row">
							<input name="szamlairszam" type="text" class="span1" placeholder="{t('ir.szám')}" value="{$user.szamlairszam}">
							<input name="szamlavaros" type="text" class="span4" placeholder="{t('város')}" value="{$user.szamlavaros}">
						</div>
						<div class="controls">
							<input name="szamlautca" type="text" class="span5" placeholder="{t('utca')}" value="{$user.szamlautca}">
						</div>
						<div class="row chk-actionrow"><a class="btn btn-primary pull-right js-chkopenbtn" data-datagroupheader=".js-chkszallmoddgh">Szállítás és fizetés</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div class="chk-datagroupheader js-chkdatagroupheader js-chkszallmoddgh" data-container=".js-chkszallmod">Szállítás és fizetés</div>
					<div class="js-chkszallmod js-chkdatacontainer">
						<div class="row">
								<div class="span2"><label class="chk-controllabel">Szállítási mód:</label></div>
								<div class="span3 controls js-chkszallmodlist">
									<label class="radio">
										<input type="radio" name="szallitasimod" value="1" checked>
										Futárszolgálat
									</label>
									<label class="radio">
										<input type="radio" name="szallitasimod" value="2">
										Személyes átvétel
									</label>
								</div>
								<div class="span2"><label class="chk-controllabel">Fizetési mód:</label></div>
								<div class="span3 controls js-chkfizmodlist">
									<label class="radio">
										<input type="radio" name="fizetesimod" value="1" checked>
										Készpénz
									</label>
									<label class="radio">
										<input type="radio" name="fizetesimod" value="2">
										Előre utalás
									</label>
								</div>
						</div>
						<div class="row">
							<div class="span3"><label for="WebshopMessageEdit">Üzenet a webáruház részére:</label></div>
							<div class="span7 controls"><textarea id="WebshopMessageEdit" class="span7" name="webshopmessage" type="text" rows="2"></textarea></div>
						</div>
						<div class="row">
							<div class="span3"><label for="CourierMessageEdit">Üzenet a futár részére:</label></div>
							<div class="span7 controls"><textarea id="CourierMessageEdit" class="span7" name="couriermessage" type="text" rows="2"></textarea></div>
						</div>
						<div class="row chk-actionrow"><a class="btn btn-primary pull-right js-chkopenbtn" data-datagroupheader=".js-chkattekintesdgh">Megrendelés áttekintése</a></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span10">
					<div class="chk-datagroupheader js-chkdatagroupheader js-chkattekintesdgh" data-container=".js-chkattekintes">Megrendelés áttekintése</div>
					<div class="js-chkattekintes js-chkdatacontainer">
						<div class="chk-columncontainer pull-left">
							<div class="col30percent">
								<div class="chk-colheader">Szállítási adatok</div>
								<div class="chk-coldatabottom">Lövey Bálint</div>
								<div>3300 Eger</div>
								<div class="chk-coldatabottom">Koháry u. 14. 3/10</div>
								<div>Kiss Béla</div>
								<div>bela@kisskocsma.hu</div>
								<div>+36 30 4445666</div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Számlázási adatok</div>
								<div>Mesemasina Kft.</div>
								<div class="chk-coldatabottom">12345678-1-23</div>
								<div>3300 Eger</div>
								<div>Koháry u. 14. 3/10</div>
							</div>
							<div class="col30percent chk-colleftborder chk-colmargin">
								<div class="chk-colheader">Szállítás és fizetés</div>
								<div>Futárszolgálat</div>
								<div class="chk-coldatabottom">Előre utalás</div>
								<div class="chk-coldatabottom">Szóljatok annak a köcsögnek, hogy keressen meg a kapucsengőn, mert arra lusta és már a sokadik csomagom megy vissza. Az pedig pénzbe kerül és komoly bosszúság.</div>
								<div>Köcsög futás, keress meg a kapucsengőn, mert arra lusta és már a sokadik csomagom megy vissza. Az pedig pénzbe kerül és komoly bosszúság.</div>
							</div>
						</div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th><div class="textaligncenter">{t('Termék')}</div></th>
									<th>{t('Megnevezés, cikkszám')}</th>
									<th><div class="textAlignRight">{t('Egységár')}</div></th>
									<th><div class="textaligncenter">{t('Mennyiség')}</div></th>
									<th><div class="textAlignRight">{t('Érték')}</div></th>
								</tr>
							</thead>
							<tbody>
								{$osszesen=0}
								{foreach $tetellista as $tetel}
									{$osszesen=$osszesen+$tetel.bruttohuf}
									<tr class="clickable" data-href="{$tetel.link}">
										<td><div class="textaligncenter"><a href="{$tetel.link}"><img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a></div></td>
										<td><div><a href="{$tetel.link}">{$tetel.caption}</a></div>
											<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}:{$valtozat.ertek}&nbsp;{/foreach}</div>
											{$tetel.cikkszam}</td>
										<td><div class="textAlignRight">{number_format($tetel.bruttoegysarhuf,0,',',' ')} Ft</div></td>
										<td>
											<div class="textAligncenter">
												<div>{number_format($tetel.mennyiseg,0,',','')}"</div>
											</div>
										</td>
										<td><div class="textAlignRight">{number_format($tetel.bruttohuf,0,',',' ')} Ft</div></td>
									</tr>
								{/foreach}
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4"><div class="textAlignRight">{t('Összesen')}:</div></th>
									<th><div class="textAlignRight">{number_format($osszesen,0,',',' ')} Ft</div></th>
								</tr>
							</tfoot>
						</table>
						<div class="chk-actionrow"><div><a class="btn cartbtn pull-right">Megrendelés elküldése</a></div></div>
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