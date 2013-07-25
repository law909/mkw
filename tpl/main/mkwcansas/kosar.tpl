{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span14 offset1">
			<div class="textAlignRight">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-large okbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
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
									<form class="kosarform" action="{$tetel.editlink}">
										<div><input id="mennyedit_{$tetel.id}" class="span1" type="number" step="any" name="mennyiseg" value="{$tetel.mennyiseg}"></div>
										<div><button class="kosareditbtn btn btn-mini" type="submit" data-id="{$tetel.id}">{t('Módosít')}</button></div>
										<input type="hidden" name="id" value="{$tetel.id}">
										<a class="kosardelbtn" href="/kosar/del?id={$tetel.id}"><i class="icon-remove-sign"></i>{t('Töröl')}</a>
									</form>
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
			<div class="textAlignRight">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-large okbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
		</div>
	</div>
</div>
{/block}