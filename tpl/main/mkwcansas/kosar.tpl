{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<div class="textalignright megrendelemcontainer">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th><div class="textaligncenter">{t('Termék')}</div></th>
						<th>{t('Megnevezés, cikkszám')}</th>
						<th><div class="textalignright">{t('Egységár')}</div></th>
						<th><div class="textaligncenter">{t('Mennyiség')}</div></th>
						<th><div class="textalignright">{t('Érték')}</div></th>
						<th></th>
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
							<td><div class="textalignright">{number_format($tetel.bruttoegysarhuf,0,',',' ')} Ft</div></td>
							<td>
								<div class="textaligncenter">
									<form class="kosarform" action="{$tetel.editlink}">
										<div><input id="mennyedit_{$tetel.id}" class="span1" type="number" step="any" name="mennyiseg" value="{$tetel.mennyiseg}"></div>
										<input type="hidden" name="id" value="{$tetel.id}">
									</form>
								</div>
							</td>
							<td><div class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} Ft</div></td>
							<td class="textaligncenter"><a class="btn js-kosardelbtn" href="/kosar/del?id={$tetel.id}"><i class="icon-remove-sign"></i>{t('Töröl')}</a></td>
						</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4"><div class="textalignright">{t('Összesen')}:</div></th>
						<th><div class="textalignright">{number_format($osszesen,0,',',' ')} Ft</div></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
			<div class="textalignright megrendelemcontainer">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
		</div>
	</div>
</div>
{/block}