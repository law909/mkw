{extends "base.tpl"}

{block "kozep"}
<div class="container page-header">
	<div class="row">
		<div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
            <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
                {if ($navigator|default)}
                    <a href="/" rel="v:url" property="v:title">
                        {t('Home')}
                    </a>
                    <i class="icon arrow-right"></i>
                    {foreach $navigator as $_navi}
                        {if ($_navi.url|default)}
                            <span typeof="v:Breadcrumb">
                                <a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
                                    {$_navi.caption|lower|capitalize}
                                </a>
                            </span>
                            <i class="icon arrow-right"></i>
                        {else}
                            {$_navi.caption|lower|capitalize}
                        {/if}
                    {/foreach}
                {/if}
            </span>
		</div>
	</div>
    <div class="row">
        <div class="col">
            {foreach $navigator as $_navi}
                {if ($_navi@last)}
                    <h1 class="page-header__title" typeof="v:Breadcrumb">
                        <a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
                            {$_navi.caption|lower|capitalize}
                        </a>
                    </h1>
                {/if}
            {/foreach}
        </div>
        <div class="col flex-cr">
            <button class="bordered product-filter__toggle">
                <span>{t('Szűrők')}</span>
                <i class="icon filter"></i>
            </button>
        </div>
    </div>

</div>
<div class="container whitebg">

    <div class="product-filter">
        <div class="product-filter__header flex-lc">
            <span class="product-filter__title bold">{t('Szűrőfeltételek')}</span>
            <span class="product-filter__close js-filterclose"><i class="icon close icon__click"></i></span>
        </div>

        <select name="elemperpage" class="elemperpageedit">
            {$elemszam = array(10, 20, 30, 40, $lapozo.elemcount)}
            {$elemnev = array("10 "|cat:t('darab'), "20 "|cat:t('darab'), "30 "|cat:t('darab'), "40 "|cat:t('darab'), t("Mind"))}
            {foreach $elemszam as $c}
            <option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
            {/foreach}
        </select>

        <select name="order" class="orderedit">
            <option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
            <option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
            <option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
            <option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
            <option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
            <option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
        </select>
        <input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
        <input id="ListviewEdit" type="hidden" name="vt" value="{$vt}">
        <input id="CsakakciosEdit" type="hidden" name="csakakcios" value="{$csakakcios}">

        <div class="szurofej szurokontener js-filterclear bold">
            {t('Szűrőfeltételek törlése')}
        </div>

        {* <div class="szurokontener">
            <div class="szurofej closeupbutton" data-refcontrol="#ArSzuro">{t('Ár')} <i class="icon-chevron-up"></i></div>
            <div id="ArSzuro" class="szurodoboz">
                <input id="ArSlider" type="slider" name="ar" value="{$minarfilter};{$maxarfilter}" data-maxar="{$maxar}" data-step="{$arfilterstep}">
            </div>
        </div> *}

        <form id="szuroform">
            {foreach $szurok as $_szuro}
            <div class="szurokontener">
                <div class="szurofej closeupbutton" data-refcontrol="#SzuroFej{$_szuro.id}">{$_szuro.caption|lower|capitalize} <i class="icon-chevron-up"></i></div>
                <div id="SzuroFej{$_szuro.id}" class="szurodoboz">
                    {foreach $_szuro.cimkek as $_ertek}
                        <div>
                            <label class="checkbox" for="SzuroEdit{$_ertek.id}">
                                <input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}" type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption|lower|capitalize}{if ($_ertek.termekdb|default)} ({$_ertek.termekdb}){/if}
                            </label>
                        </div>
                    {/foreach}
                </div>
            </div>
            {/foreach}
        </form>
    </div>


	<div class="row">
		{* <div class="span3">
            <div class="szurofej szurokontener js-filterclear bold">
                {t('Szűrőfeltételek törlése')}
            </div>
			<form id="szuroform">
            szűrők
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
		</div> *}
		<div class="col">
			<div class="category-description">
				{$kategoria.leiras2}
			</div>
        {$lntcnt=count($kiemelttermekek)}
        {if ($lntcnt>0)}
            <div class="lapozo">
                <span class="bold">Kiemelt termékeink</span>
            </div>
            <div>
            {$step=min(3, $lntcnt)}
            {if ($step==0)}
                {$step=1}
            {/if}
            {for $i=0 to $lntcnt-1 step $step}
                <div>
                {for $j=0 to $step-1}
                    {if ($i+$j<$lntcnt)}
                    {$_termek=$kiemelttermekek[$i+$j]}
                    <div class="textaligncenter pull-left" style="width:{100/$step}%">
                        <div class="o404TermekInner">
                            <a href="{$_termek.link}">
                                <div class="o404ImageContainer">
                                    <img src="{$imagepath}{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                </div>
                                <div>{$_termek.caption}</div>
                                <h5 class="termeklista">
                                    {if ($_termek.akcios)}
                                    <span><span class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} {$_termek.valutanemnev}</span> helyett {number_format($_termek.bruttohuf,0,',',' ')} {$_termek.valutanemnev}</span>
                                    {else}
                                    <span>{number_format($_termek.bruttohuf,0,',',' ')} {$_termek.valutanemnev}</span>
                                    {/if}
                                </h5>
                                <a href="{$_termek.link}" class="btn okbtn">Részletek</a>
                            </a>
                        </div>
                    </div>
                    {/if}
                {/for}
                </div>
            {/for}
            </div>
        {/if}
			<div class="lapozo">
				<form class="lapozoform" action="{$url}" method="post" data-url="{$url}" data-pageno="{$lapozo.pageno}">
					<table><tbody><tr>
					{* <td class="lapozotalalat">
						<select name="elemperpage" class="elemperpageedit">
							{$elemszam = array(10, 20, 30, 40, $lapozo.elemcount)}
							{$elemnev = array("10 "|cat:t('darab'), "20 "|cat:t('darab'), "30 "|cat:t('darab'), "40 "|cat:t('darab'), t("Mind"))}
							{foreach $elemszam as $c}
							<option value="{$c}"{if ($c==$lapozo.elemperpage)} selected="selected"{/if}>{$elemnev[$c@index]}</option>
							{/foreach}
						</select>
					</td> *}
					{* <td class="lapozooldalak">
						{if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
						{for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
						{if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
					</td> *}
					{* <td class="lapozorendezes">
						<select name="order" class="orderedit">
							<option value="nevasc"{if ($order=='nevasc')} selected="selected"{/if}>{t('Név szerint növekvő')}</option>
							<option value="nevdesc"{if ($order=='nevdesc')} selected="selected"{/if}>{t('Név szerint csökkenő')}</option>
							<option value="arasc"{if ($order=='arasc')} selected="selected"{/if}>{t('Legolcsóbb elől')}</option>
							<option value="ardesc"{if ($order=='ardesc')} selected="selected"{/if}>{t('Legdrágább elől')}</option>
							<option value="idasc"{if ($order=='idasc')} selected="selected"{/if}>{t('Legrégebbi elől')}</option>
							<option value="iddesc"{if ($order=='iddesc')} selected="selected"{/if}>{t('Legújabb elől')}</option>
						</select>
						<input class="KeresettEdit" type="hidden" name="keresett" value="{$keresett}">
						<input id="ListviewEdit" type="hidden" name="vt" value="{$vt}">
                        <input id="CsakakciosEdit" type="hidden" name="csakakcios" value="{$csakakcios}">
					</td> *}
					</tr></tbody></table>
				</form>
			</div>
			{if ($lapozo.elemcount>0)}
                {$termekcnt=count($termekek)}
                {$step=4}
                <div class="product-list">
                {for $i=0 to $termekcnt-1 step $step}
                    {* <div> *}
                    {for $j=0 to $step-1}
                    {if (isset($termekek[$i+$j]))}
                        {$_termek=$termekek[$i+$j]}
                    {else}
                        {$_termek=null}
                    {/if}
                    {if ($_termek)}
                        <div class=" product-list-item spanmkw3 gtermek{if (($j==$step-1)||($i+$j>=$termekcnt))} gtermekszelso{/if} itemscope itemtype="http://schema.org/Product">
                            <div class="gtermekinner"><div class="gtermekinnest product-list-item__inner">
                                <div class="textaligncenter product-list-item__image-container">
                                    <div class="flags">
                                        {if (isset($_termek.ujtermek) && $_termek.ujtermek)}
                                            <div class="flag new-product">{t('Új')}</div>
                                        {/if}

                                        {if (isset($_termek.akcios) && $_termek.akcios)}
                                            <div class="flag sale-product">{t('Akciós')}</div>
                                        {/if}
                                        
                                        {if (isset($_termek.kiemelt) && $_termek.kiemelt)}
                                            <div class="flag featured">{t('Kiemelt')}</div>
                                        {/if}
                                        {* {if (isset($_termek.top10) && $_termek.top10)}
                                            <div class="flag sale-product">{t('Top 10')}</div>
                                        {/if} *}
                                    </div>
                                    <a href="/product/{$_termek.slug}"><img class="product-list-item__image itemprop="image" src="{$imagepath}{$_termek.kepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>

                                    {* {$kcnt=count($_termek.kepek)}
                                    {if ($kcnt>0)}
                                    <div class="js-termekimageslider termekimageslider termekimagecontainer textaligncenter royalSlider contentSlider rsDefaultInv">
                                        {$step=4}
                                        {for $i=0 to $kcnt-1 step $step}
                                            <div>
                                            {for $j=0 to $step-1}
                                                {if ($i+$j<$kcnt)}
                                                    {$_kep=$_termek.kepek[$i+$j]}
                                                    <a href="{$imagepath}{$_kep.kepurl}" class="js-lightbox" title="{$_kep.leiras}">
                                                        <img class="termeksmallimage" src="{$imagepath}{$_kep.minikepurl}" alt="{$_kep.leiras}" title="{$_kep.leiras}">
                                                    </a>
                                                {/if}
                                            {/for}
                                            </div>
                                        {/for}
                                    </div>
                                    {/if} *}
                                </div>
                                <div class="textaligncenter product-list-item__content product-list-item__title">
                                    <a itemprop="url" href="/product/{$_termek.slug}"><span class="gtermekcaption" itemprop="name">{$_termek.caption|lower|capitalize}</span></a>
                                </div>
                                <div class="textaligncenter product-list-item__content product-list-item__code">
                                    <a href="/product/{$_termek.slug}">{$_termek.cikkszam}</a>
                                </div>
                                <div class="textaligncenter product-list-item__content">
                                    {if ($_termek.szallitasiido && (!$_termek.nemkaphato))}
                                    <div class="textaligncenter"><span class="bold">Szállítási idő: </span>{$_termek.szallitasiido} munkanap</div>
                                    {/if}
                                    {if ($_termek.szinek|default)}
                                        <div class="js-valtozatbox product-list-item__variations-container">
                                            {* {$_termek.szinek|@count} {t('szín')} *}
                                            <div class="pull-left gvaltozatcontainer product-list-item__variations">
                                                <div class="pull-left gvaltozatnev termekvaltozat">{t('Szín')}:</div>
                                                <div class="pull-left gvaltozatselect">
                                                
                                                    <div class="option-selector color-selector" data-termek="{$_termek.id}">
                                                        {foreach $_termek.szinek as $_v}
                                                            <div class="select-option {$_v|lower|replace:'/':'-'}" data-value="{$_v}" title="{$_v}"></div>
                                                        {/foreach}
                                                    </div>
                                                
                                                    <select class="js-szinvaltozatedit custom-select valtozatselect" data-termek="{$_termek.id}">
                                                        <option value="">{t('Válasszon')}</option>
                                                        {foreach $_termek.szinek as $_v}
                                                            <option value="{$_v}">{$_v}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                                <div class="flex-tb ">
                                    <div class="termekprice pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        {if ($_termek.akcios)}
                                            <span class="akciosarszoveg"><span class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} {$_termek.valutanemnev}</span></span>
                                        {/if}
                                        {if ($_termek.nemkaphato)}
                                            <link itemprop="availability" href="http://schema.org/OutOfStock" content="{t('Nem kapható')}">
                                        {else}
                                            <link itemprop="availability" href="http://schema.org/InStock" content="{t('Kapható')}">
                                        {/if}
                                        <span class="product-list-item__price" itemprop="price">{number_format($_termek.bruttohuf,0,',',' ')}
                                         {$_termek.valutanemnev}
                                        </span>
                                    </div>
                                    <div class="pull-right">
                                    {if ($_termek.nemkaphato)}
                                        <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="{$_termek.id}">
                                            {t('Elfogyott')}
                                        </a>
                                    {else}
                                        {if ($_termek.bruttohuf > 0)}
                                        <a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="js-kosarbaszinvaltozat button bordered small cartbtn pull-right" data-termek="{$_termek.id}">
                                            {t('Kosárba')}
                                        </a>
                                        {/if}
                                    {/if}
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    {/if}
                    {/for}
                    {* </div> *}
                {/for}
                </div>
			{else}
				{t('Nincs ilyen termék')}
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
{include 'termekertesitomodal.tpl'}
{/block}
