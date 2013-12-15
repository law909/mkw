{extends "base.tpl"}

{block "kozep"}
<div class="container morzsa whitebg">
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
<div class="container whitebg">
	<div class="row">
		<div class="span3">
            <div class="szurofej szurokontener js-filterclear bold">
                Szűrőfeltételek törlése
            </div>
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
									<input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}" type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption}{if ($_ertek.termekdb|default)} ({$_ertek.termekdb}){/if}
								</label>
							</div>
						{/foreach}
					</div>
				</div>
				{/foreach}
			</form>
		</div>
		<div class="span9">
			<div>
				{$kategoria.leiras2}
			</div>
            <div>
        {$lntcnt=count($kiemelttermekek)}
        {$step=min(3, $lntcnt)}
        {for $i=0 to $lntcnt-1 step $step}
            <div>
            {for $j=0 to $step-1}
                {if ($i+$j<$lntcnt)}
                {$_termek=$kiemelttermekek[$i+$j]}
                <div class="textaligncenter pull-left" style="width:{100/$step}%">
                    <div class="o404TermekInner">
                        <a href="{$_termek.link}">
                            <div class="o404ImageContainer">
                                <img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                            </div>
                            <div>{$_termek.caption}</div>
                            <h5><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
                            <a href="{$_termek.link}" class="btn cartbtn">Részletek</a>
                        </a>
                    </div>
                </div>
                {/if}
            {/for}
            </div>
        {/for}

            </div>
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
						<div class="row" itemscope itemtype="http://schema.org/Product">
                            <div class="span9 termek">
                                <div class="row">
                                    <div class="span2 termekimage">
                                        <a href="/termek/{$_termek.slug}"><img itemprop="image" src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
                                    </div>
                                    <div class="span5 termektext">
                                        <a href="/termek/{$_termek.slug}" itemprop="url"><span class="termekcaption" itemprop="name">{$_termek.caption}</span></a>
                                        <p itemprop="description" class="textalignjustify">{$_termek.rovidleiras}</p>
                                        {if ($_termek.szallitasiido)}
                                        <div><span class="bold">Szállítási idő: </span>max. {$_termek.szallitasiido} munkanap</div>
                                        {/if}
                                        <div class="termekjelzok">
                                            {foreach $_termek.cimkelistaban as $_jelzo}
                                                <span>{$_jelzo.caption}&nbsp;</span>
                                            {/foreach}
                                        </div>
                                        <div class="termekjelzok">
                                            {if ($_termek.ujtermek)}<img src="{$ujtermekjelolourl}" title="Új termék" alt="Új termék">{/if}
                                            {if ($_termek.akcios)}<img src="{$akciosjelolourl}" title="Akciós termék" alt="Akciós termék">{/if}
                                            {if ($_termek.top10)}<img src="{$top10jelolourl}" title="Top 10 termék" alt="Top 10 termék">{/if}
                                            {if ($_termek.ingyenszallitas)}<img src="{$ingyenszallitasjelolourl}" title="Ingyenes szállítás" alt="Ingyenes szállítás">{/if}
                                        </div>
                                    </div>
                                    <div class="span2">
                                        {$_kosarbaclass="js-kosarba"}
                                        {if ($_termek.valtozatok|default)}
                                            <div class="termekvaltozat">{$_termek.valtozatok.fixname}: {$_termek.valtozatok.fixvalue}</div>
                                            {if ($_termek.valtozatok.name)}
                                            <div class="termekvaltozat">
                                                <div>
                                                {$_termek.valtozatok.name}:
                                                </div>
                                                <select class="js-valtozatedit valtozatselect" data-id="{$_termek.id}-{$_termek.valtozatid}" data-termek="{$_termek.id}">
                                                {foreach $_termek.valtozatok.data as $_data}
                                                    <option value="{$_data.id}"{if ($_data.selected)} selected{/if}>{$_data.value}</option>
                                                {/foreach}
                                                </select>
                                            </div>
                                            {/if}
                                            {$_kosarbaclass="js-kosarbavaltozat"}
                                        {/if}
                                        {if ($_termek.mindenvaltozat|default)}
                                            {foreach $_termek.mindenvaltozat as $_valtozat}
                                                <div class="termekvaltozat">
                                                    <div>
                                                    {$_valtozat.name}:
                                                    </div>
                                                <select class="js-mindenvaltozatedit valtozatselect" data-id="{$_termek.id}-{$_termek.valtozatid|default}" data-termek="{$_termek.id}" data-tipusid="{$_valtozat.tipusid}">
                                                    <option value="">{t('Válasszon')}</option>
                                                    {foreach $_valtozat.value as $_v}
                                                        <option value="{$_v}"{if ($_valtozat.selected[$_v])} selected{/if}>{$_v}</option>
                                                    {/foreach}
                                                </select>
                                                </div>
                                            {/foreach}
                                            {$_kosarbaclass="js-kosarbamindenvaltozat"}
                                        {/if}
                                        {if ($_termek.akcios)}
                                            <div class="akciosarszoveg textalignright">Eredeti ár: <span class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} Ft</span></div>
                                        {/if}
                                        <div id="termekprice{$_termek.id}-{$_termek.valtozatid|default}" class="termekprice" itemprop="price">{number_format($_termek.bruttohuf,0,',',' ')} Ft</div>
                                        {if ($_termek.nemkaphato)}
                                            <div class="textalignright">
                                                <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn" data-termek="{$_termek.id}">
                                                    {t('Elfogyott')}
                                                </a>
                                            </div>
                                        {else}
                                            <div class="textalignright">
                                            <a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="{$_kosarbaclass} btn cartbtn" data-termek="{$_termek.id}" data-id="{$_termek.id}-{$_termek.valtozatid|default}" data-vid="{$_termek.valtozatid|default}">
                                                {t('Kosárba')}
                                            </a>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
						</div>
					{/foreach}
				{else}
                    {$termekcnt=count($termekek)}
                    {$step=2}
					{for $i=0 to $termekcnt-1 step $step}
                        <div>
                        {for $j=0 to $step-1}
                        {$_termek=$termekek[$i+$j]}
                        {if ($_termek)}
                            <div class="spanmkw3 gtermek{if (($j==$step-1)||($i+$j>=$termekcnt))} gtermekszelso{/if} itemscope itemtype="http://schema.org/Product">
                                <div class="gtermekinner"><div class="gtermekinnest">
                                    <div class="pull-left gtermekupper">
                                        <div class="gtermekleft pull-left">
                                            <a href="/termek/{$_termek.slug}"><img itemprop="image" src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
                                        </div>
                                        <div class="gtermekright pull-left">
                                        <a itemprop="url" href="/termek/{$_termek.slug}"><span class="gtermekcaption" itemprop="name">{$_termek.caption}</span></a>
                                        <div itemprop="description" class="textalignjustify">{$_termek.rovidleiras}</div>
                                        </div>
                                    </div>
                                    <div class="pull-left gtermekcenter">
                                        <div class="gtermekleft pull-left">
                                            {foreach $_termek.cimkelistaban as $_jelzo}
                                                <span>{$_jelzo.caption}&nbsp;</span>
                                            {/foreach}
                                        </div>
                                        <div class="gtermekright pull-left">
                                            <div>
                                                {if ($_termek.ujtermek)}<img src="{$ujtermekjelolourl}" title="Új termék" alt="Új termék">{/if}
                                                {if ($_termek.akcios)}<img src="{$akciosjelolourl}" title="Akciós termék" alt="Akciós termék">{/if}
                                                {if ($_termek.top10)}<img src="{$top10jelolourl}" title="Top 10 termék" alt="Top 10 termék">{/if}
                                                {if ($_termek.ingyenszallitas)}<img src="{$ingyenszallitasjelolourl}" title="Ingyenes szállítás" alt="Ingyenes szállítás">{/if}
                                            </div>
                                            {if ($_termek.szallitasiido)}
                                            <div><span class="bold">Szállítási idő: </span>{$_termek.szallitasiido} munkanap</div>
                                            {/if}
                                            {if ($_termek.valtozatok|default)}
                                                <div class="pull-left gvaltozatcontainer termekvaltozat">
                                                    {$_termek.valtozatok.fixname}: {$_termek.valtozatok.fixvalue}
                                                </div>
                                                {if ($_termek.valtozatok.name)}
                                                <div class="pull-left gvaltozatcontainer">
                                                    <div class="pull-left gvaltozatnev termekvaltozat">{$_termek.valtozatok.name}:</div>
                                                    <div class="pull-left gvaltozatselect">
                                                        <select class="js-valtozatedit valtozatselect" data-id="{$_termek.id}-{$_termek.valtozatid}" data-termek="{$_termek.id}">
                                                        {foreach $_termek.valtozatok.data as $_data}
                                                            <option value="{$_data.id}"{if ($_data.selected)} selected{/if}>{$_data.value}</option>
                                                        {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                {/if}
                                            {/if}
                                            {if ($_termek.mindenvaltozat|default)}
                                                {foreach $_termek.mindenvaltozat as $_valtozat}
                                                    <div class="pull-left gvaltozatcontainer">
                                                        <div class="pull-left gvaltozatnev termekvaltozat">{$_valtozat.name}:</div>
                                                        <div class="pull-left gvaltozatselect">
                                                            <select class="js-mindenvaltozatedit valtozatselect" data-id="{$_termek.id}-{$_termek.valtozatid|default}" data-termek="{$_termek.id}" data-tipusid="{$_valtozat.tipusid}">
                                                                <option value="">{t('Válasszon')}</option>
                                                                {foreach $_valtozat.value as $_v}
                                                                    <option value="{$_v}">{$_v}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                {/foreach}
                                            {/if}
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="termekprice" itemprop="price">
                                            {if ($_termek.akcios)}
                                            <span class="akciosarszoveg">Eredeti ár: <span class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} Ft</span></span>
                                            {/if}
                                        {number_format($_termek.bruttohuf,0,',',' ')} Ft</div>
                                        <div class="pull-right">
                                        {if ($_termek.nemkaphato)}
                                            <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="{$_termek.id}">
                                                {t('Elfogyott')}
                                            </a>
                                        {else}
                                            <a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="js-kosarba btn cartbtn pull-right" data-termek="{$_termek.id}">
                                                {t('Kosárba')}
                                            </a>
                                        {/if}
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        {/if}
                        {/for}
                        </div>
					{/for}
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
