{extends "base.tpl"}
{block "css"}
<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/jquery.slider.min.css">
{/block}
{block "script"}
<script src="/js/main/mkwnew/jquery.blockUI.js"></script>
<script src="/js/main/mkwnew/matt-accordion.js"></script>
<script src="/js/main/mkwnew/jquery.slider.min.js"></script>
{/block}
{block "kozep"}
<div class="row hataroltSor">
	<div class="span12">
	{foreach $navigator as $_navi}
		{if ($_navi.url!='')}
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
<div class="row">
	<div class="span3">
		<form id="szuroform" class="jobbrapadding">
			<div class="keret hataroltSor">
				<div class="szurofej closeupbutton" data-refcontrol="#ArSzuro">{t('Ár')} <i class="icon-chevron-up"></i></div>
				<div id="ArSzuro" class="szurodoboz korbepadding">
					<input id="ArSlider" type="slider" name="ar" value="{$minarfilter};{$maxarfilter}" data-maxar="{$maxar}" data-step="{$arfilterstep}">
				</div>
			</div>
			{foreach $szurok as $_szuro}
			<div class="keret hataroltSor">
				<div class="szurofej closeupbutton" data-refcontrol="#SzuroFej{$_szuro.id}">{$_szuro.caption} <i class="icon-chevron-up"></i></div>
				<div id="SzuroFej{$_szuro.id}" class="szurodoboz korbepadding">
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
		{$kitermekdb=count($kiemelttermekek)}
		{if ($kitermekdb>0)}
			<div class="row blockHeader">
					<h4>{t('KIEMELT TERMÉKEINK')}</h4>
			</div>
			{$cikl=0}
			{foreach $kiemelttermekek as $_termek}
				{$maradek=$cikl%3}
				{if ($maradek==0)}
				<div class="row kiemeltTermekListBlock">
				{/if}
					<div class="span3 textAlignCenter">
						<div class="miniItemPicture"><a href="/termek/{$_termek.slug}"><img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a></div>
						<div class="miniItemCaption"><a href="/termek/{$_termek.slug}"><h4>{$_termek.caption}</h4></a></div>
						<div class="miniItemJelzok">
							{foreach $_termek.cimkelistaban as $_jelzo}
								<img src="{$_jelzo.kiskepurl}" title="{$_jelzo.caption}" alt="{$_jelzo.caption}">
							{/foreach}
						</div>
						<div class="row">
							<div class="span3">
								<h3 class="itemPrice"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h3>
							</div>
						</div>
						<!-- div class="row">
							<div class="span3">
								<a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="kosarba btn btn-large btn-success">
									<i class="icon-shopping-cart icon-white"></i>
									{t('Megveszem')}
								</a>
							</div>
						</div -->
					</div>
				{$cikl=$cikl+1}
				{if ($maradek==2||$cikl==$kitermekdb)}
				</div>
				{/if}
			{/foreach}
		{/if}
		<div class="row keret hataroltSor korbepadding">
			<div class="span9">
				<form class="lapozoForm" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
					<span>{t('Termék oldalanként')}</span>
					<select name="elemperpage" class="elemperpageEdit">
						{$elemszam=array(10,20,30,40,$lapozo.elemcount)}
						{$elemnev=array("10 darab/{$lapozo.elemcount}","20 darab/{$lapozo.elemcount}","30 darab/{$lapozo.elemcount}","40 darab/{$lapozo.elemcount}","Mind")}
						{foreach $elemszam as $c}
						<option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
						{/foreach}
					</select>
					<span>{t('Rendezés')}</span>
					<select name="order" class="orderEdit">
						<option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
						<option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
						<option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
						<option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
						<option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
						<option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
					</select>
					<input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
				</form>
			</div>
			<div class="textAlignCenter">
				{if ($lapozo.pageno>1)}<a href="#" class="pageEdit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
				{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualisLap">{$i}</span>{else}<a href="#" class="pageEdit" data-pageno="{$i}">{$i}</a>{/if}{/for}
				{if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageEdit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
			</div>
		</div>
		{if ($lapozo.elemcount>0)}
			{foreach $termekek as $_termek}
				<div class="row itemListBlock">
					<div class="span2">
						<div>
						<a href="/termek/{$_termek.slug}"><img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
						</div>
						<div class="itemJelzok">
							{foreach $_termek.cimkelistaban as $_jelzo}
								<img src="{$_jelzo.kiskepurl}" title="{$_jelzo.caption}" alt="{$_jelzo.caption}">
							{/foreach}
						</div>
					</div>
					<div class="span5">
						<a href="/termek/{$_termek.slug}"><h3>{$_termek.caption}</h3></a>
						<p>{$_termek.rovidleiras}</p>
						<ul class="simalista">
							{foreach $_termek.valtozatok['tipus'] as $_ertek}
								{if ($_ertek)}
									<li>
									{$_ertek}
									{$_adat=$_termek.valtozatok['adat']}
									<select class="js-valtozatedit" data-oszlop="{$_ertek@index+1}" data-id="{$_ertek@key}" data-termek="{$_termek.id}">
										<option value="">{t('Válasszon')}</option>
										{foreach $_adat[$_ertek@key] as $_v}
											<option value="{$_v}">{$_v}</option>
										{/foreach}
									</select>
									</li>
								{/if}
							{/foreach}
						</ul>
					</div>
					<div class="span2">
						<div class="row">
							<h3 class="itemPrice"><span class="pull-right">{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h3>
						</div>
						<div class="row">
							<a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="kosarba btn btn-large btn-success pull-right" data-termek="{$_termek.id}">
								<i class="icon-shopping-cart icon-white"></i>
								{t('Megveszem')}
							</a>
						</div>
					</div>
				</div>
			{/foreach}
		{else}
			Nincs ilyen termék
		{/if}
		<div class="row korbepadding">
			<div class="textAlignCenter">
				{if ($lapozo.pageno>1)}<a href="#" class="pageEdit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
				{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualisLap">{$i}</span>{else}<a href="#" class="pageEdit" data-pageno="{$i}">{$i}</a>{/if}{/for}
				{if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageEdit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
			</div>
		</div>
	</div>
</div>
{/block}