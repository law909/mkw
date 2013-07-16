{extends "base.tpl"}

{block "kozep"}
<div class="container morzsa">
	<div class="row">
		<div class="span12 morzsaszoveg">
		{foreach $navigator as $_navi}
			{if ($_navi.url|default)}
				<a href="{$_navi.url}">
					{$_navi.caption}
				</a>
				/
			{else}
				{$_navi.caption}
			{/if}
		{/foreach}
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="span3">
			<form id="szuroform">
				<div class="szurokontener">
					<div class="szurofej closeupbutton" data-refcontrol="#ArSzuro">{t('Ár')} <i class="icon-chevron-up"></i></div>
					<div id="ArSzuro" class="szurodoboz">
						<input id="ArSlider" type="slider" name="ar" value="{$minarfilter};{$maxarfilter}" data-maxar="{$maxar}" data-step="{$arfilterstep}">
					</div>
				</div>
				{foreach $szurok as $_szuro}
				<div class="szurokontener">
					<div class="szurofej closeupbutton" data-refcontrol="#SzuroFej{$_szuro.id}">{$_szuro.caption} <i class="icon-chevron-up"></i></div>
					<div id="SzuroFej{$_szuro.id}" class="szurodoboz">
						{foreach $_szuro.cimkek as $_ertek}
							<div>
								<label class="checkbox" for="SzuroEdit{$_ertek.id}">
									<input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}" type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption} ({$_ertek.termekdb})
								</label>
							</div>
						{/foreach}
					</div>
				</div>
				{/foreach}
			</form>
		</div>
		<div class="span9">
			<div class="lapozo">
				<form class="lapozoform" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
					<table><tbody><tr>
					<td class="lapozotalalat">
						{if ($vt==1)}
							<a href="#" class="termeklistview" data-vt="2" title="{t('Galéria')}"><img src="/themes/main/mkwcansas/img/i_grid.png" alt="Galéria"></a>
						{else}
							<img src="/themes/main/mkwcansas/img/i_grid.png" alt="Galéria">
						{/if}
						{if ($vt==2)}
							<a href="#" class="termeklistview" data-vt="1" title="{t('Lista')}"><img src="/themes/main/mkwcansas/img/i_list.png" alt="Lista"></a>
						{else}
							<img src="/themes/main/mkwcansas/img/i_list.png" alt="Lista">
						{/if}
						<select name="elemperpage" class="elemperpageedit">
							{$elemszam=array(10,20,30,40,$lapozo.elemcount)}
							{$elemnev=array("10 darab","20 darab","30 darab","40 darab","Mind")}
							{foreach $elemszam as $c}
							<option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
							{/foreach}
						</select>
					</td>
					<td class="lapozooldalak">
						{if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
						{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
						{if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
					</td>
					<td class="lapozorendezes">
						<select name="order" class="orderedit">
							<option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
							<option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
							<option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
							<option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
							<option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
							<option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
						</select>
						<input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
						<input id="ListviewEdit" type="hidden" name="vt" value="{$vt}">
					</td>
					</tr></tbody></table>
				</form>
			</div>
			{if ($lapozo.elemcount>0)}
				{if ($vt==1)}
					{foreach $termekek as $_termek}
						<div class="termek">
							<div class="termekimage">
								<a href="/termek/{$_termek.slug}"><img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
							</div>
							<div class="span6 termektext">
								<a href="/termek/{$_termek.slug}"><span class="termekcaption">{$_termek.caption}</span></a>
								<p>{$_termek.rovidleiras}</p>
								<div class="termekjelzok">
									{foreach $_termek.cimkelistaban as $_jelzo}
										<img src="{$_jelzo.kiskepurl}" title="{$_jelzo.caption}" alt="{$_jelzo.caption}">
									{/foreach}
								</div>
							</div>
							{$_kosarbaclass="js-kosarba"}
							{if ($_termek.valtozatok|default)}
								<div class="termekprice">{$_termek.valtozatok.fixname}: {$_termek.valtozatok.fixvalue}</div>
								{if ($_termek.valtozatok.name)}
								<div class="termekprice">
									{$_termek.valtozatok.name}:
									<select class="valtozatEdit" data-id="{$_termek.id}-{$_termek.valtozatid}" data-termek="{$_termek.id}">
									{foreach $_termek.valtozatok.data as $_data}
										<option value="{$_data.id}">{$_data.value}</option>
									{/foreach}
									</select>
								</div>
								{/if}
								{$_kosarbaclass="js-kosarbavaltozat"}
							{/if}
							{if ($_termek.mindenvaltozat|default)}
								{foreach $_termek.mindenvaltozat as $_valtozat}
									<div class="termekprice">
									{$_valtozat.name}
									<select class="mindenValtozatEdit" data-id="{$_termek.id}-{$_termek.valtozatid}" data-termek="{$_termek.id}" data-tipusid="{$_valtozat.tipusid}">
										<option value="">{t('Válasszon')}</option>
										{foreach $_valtozat.value as $_v}
											<option value="{$_v}">{$_v}</option>
										{/foreach}
									</select>
									</div>
								{/foreach}
								{$_kosarbaclass="js-kosarbamindenvaltozat"}
							{/if}
							<div id="termekprice{$_termek.id}-{$_termek.valtozatid|default}" class="termekprice">{number_format($_termek.bruttohuf,0,',',' ')} Ft</div>
							{if ($_termek.nemkaphato)}
								<div class="row">
									<a href="#" rel="nofollow" class="js-termekertesitobtn btn btn-inverse pull-right" data-termek="{$_termek.id}">
										{t('Elfogyott')}
									</a>
								</div>
								<div class="row">
									<a href="#" rel="nofollow" class="js-termekertesitobtn pull-right" data-termek="{$_termek.id}">{t('Értesítsen, ha a termék újra elérhető')}</a>
								</div>
							{else}
								<a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="{$_kosarbaclass} btn cartbtn pull-right" data-termek="{$_termek.id}" data-id="{$_termek.id}-{$_termek.valtozatid|default}" data-vid="{$_termek.valtozatid|default}">
									{t('Kosárba')}
								</a>
							{/if}
						</div>
					{/foreach}
				{else}
					{foreach $termekek as $_termek}
						{$maradek=$_termek@index%3}
						<div class="gtermek{if ($maradek==2||$_termek@last)} gtermekszelso{/if}">
							<table>
							<tr class="gtermekadat">
								<td class="gtermekimage">
									<a href="/termek/{$_termek.slug}"><img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
								</td>
								<td colspan="3">
									<a href="/termek/{$_termek.slug}"><span class="termekcaption">{$_termek.caption}</span></a>
									<p>{$_termek.rovidleiras}</p>
								</td>
							</tr>
							<tr>
								<td>
								{foreach $_termek.cimkelistaban as $_jelzo}
									<img src="{$_jelzo.kiskepurl}" title="{$_jelzo.caption}" alt="{$_jelzo.caption}">
								{/foreach}
								</td>
								{if ($_termek.valtozatok|default)}
									<td>
										<div>
										{$_termek.valtozatok.fixname}: {$_termek.valtozatok.fixvalue}
										</div>
										{if ($_termek.valtozatok.name)}
										<div>
										{$_termek.valtozatok.name}:
										<select id="valtozatEdit{$_termek.id}-{$_termek.valtozatid}">
										{foreach $_termek.valtozatok.data as $_data}
											<option value="{$_data.id}">{$_data.value}</option>
										{/foreach}
										</select>
										</div>
										{/if}
									</td>
								{/if}
								{if ($_termek.mindenvaltozat|default)}
									<td>
									{foreach $_termek.mindenvaltozat as $_valtozat}
										<div>
										{$_valtozat.name}
										<select class="valtozatEdit" data-termek="{$_termek.id}">
											<option value="">{t('Válasszon')}</option>
											{foreach $_valtozat.value as $_v}
												<option value="{$_v}">{$_v}</option>
											{/foreach}
										</select>
										</div>
									{/foreach}
									</td>
								{/if}
								<td class="gtermekprice">{number_format($_termek.bruttohuf,0,',',' ')} Ft
								{if ($_termek.nemkaphato)}
									<div class="row">
										<a href="#" rel="nofollow" class="js-termekertesitobtn btn btn-inverse pull-right" data-termek="{$_termek.id}">
											{t('Elfogyott')}
										</a>
									</div>
									<div class="row">
										<a href="#" rel="nofollow" class="js-termekertesitobtn pull-right" data-termek="{$_termek.id}">{t('Értesítsen, ha a termék újra elérhető')}</a>
									</div>
								{else}
									<a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="js-kosarba btn cartbtn pull-right" data-termek="{$_termek.id}">
										{t('Kosárba')}
									</a>
								{/if}
								</td>
							</tr>
							</table>
						</div>
					{/foreach}
				{/if}
			{else}
				Nincs ilyen termék
			{/if}
			<div class="lapozo">
				<form class="lapozoform" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
					<table><tbody><tr>
					<td class="lapozooldalak">
						{if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
						{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
						{if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
					</td>
					</tr></tbody></table>
				</form>
			</div>
		</div>
	</div>
</div>
{include 'termekertesitomodal.tpl'};
{/block}