{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>Értékelje megvásárolt termékeit</h2>
			</div>
			<div>
				<table>
					<tr>
						<td>Rendelésszám:</td>
						<td>{$megr.id}</td>
					</tr>
					<tr>
						<td>Dátum:</td>
						<td>{$megr.kelt}</td>
					</tr>
					<tr>
						<td></td>
						<td>{$megr.allapotnev|default:"ismeretlen"}</td>
					</tr>
					<td>Érték:</td>
					<td>{number_format($megr.brutto,0,'',' ')} Ft</td>
				</table>
				<form method="post" action="/termekertekeles/save" class="js-tert-form">
					<input type="hidden" name="pid" value="{$megr.partnerid}">
				{foreach $megr.tetellista as $tetel}
					{if ($tetel.termekid != $szktgtermek)}
					<div class="tert-termek">
						<input type="hidden" class="js-termekids" name="termekids[]" value="{$tetel.termekid}">
						<div class="tert-termekinfo">
							<img src="{$tetel.kiskepurl}" alt="{$tetel.termeknev}" title="{$tetel.termeknev}">{$tetel.termeknev}
							<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
							{$tetel.cikkszam}
						</div>
							<label class="tert-label" for="ertekelesedit{$tetel.termekid}">Értékelés</label>
							<div class="rating js-errorable">
								<input type="radio" name="rating_{$tetel.termekid}" value="5" id="5"><label for="5">☆</label>
								<input type="radio" name="rating_{$tetel.termekid}" value="4" id="4"><label for="4">☆</label>
								<input type="radio" name="rating_{$tetel.termekid}" value="3" id="3"><label for="3">☆</label>
								<input type="radio" name="rating_{$tetel.termekid}" value="2" id="2"><label for="2">☆</label>
								<input type="radio" name="rating_{$tetel.termekid}" value="1" id="1"><label for="1">☆</label>
							</div>
							<textarea id="ertekelesedit{$tetel.termekid}" name="ertekeles_{$tetel.termekid}" rows="5" class="js-errorable"></textarea>
							<label class="tert-label" for="elonyedit{$tetel.termekid}">Előnyök</label>
							<textarea id="elonyedit{$tetel.termekid}" name="elony_{$tetel.termekid}" rows="5" class="js-errorable"></textarea>
							<label class="tert-label" for="hatranyedit{$tetel.termekid}">Hátrányok</label>
							<textarea id="hatranyedit{$tetel.termekid}" name="hatrany_{$tetel.termekid}" rows="5" class="js-errorable"></textarea>
					</div>
					{/if}
				{/foreach}
				<div class="tert-saverow">
					<button type="submit" class="btn okbtn">Mentés</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
{/block}
