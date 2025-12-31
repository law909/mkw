{extends "base.tpl"}

{block "script"}
	{$osszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.bruttohuf}
	{/foreach}
    <script>
			fbq('track', 'ViewCart', {
					value: {number_format($osszesen,0,',','')},
					currency: '{$valutanemnev}'
			});
    </script>
{/block}

{block "kozep"}

<div class="container page-header static-page__header">
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
                                <a href="/termekfa/{$_navi.url}" rel="v:url" property="v:title">
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
            <h1 class="page-header__title" typeof="v:Breadcrumb">
                {t('Kosár')}
            </h1>
        </div>
    </div>
</div>

<div class="container cart-page whitebg">
	<div class="row">
		<div class="col flex-cc flex-col js-cart">
			{if (count($tetellista)>0)}
			<div class="megrendelemcontainer flex-cb">
				<a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
				<a href="{$showcheckoutlink}" rel="nofollow" class="button primary cartbtn pull-right">
					<i class="icon cart icon__click"></i>
					{t('Megrendelem')}
				</a>
			</div>
			<table class="cart-page__table table table-bordered">
				{include 'kosartetellist.tpl'}
			</table>
			<div class="megrendelemcontainer flex-cb">
				<a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
				<a href="{$showcheckoutlink}" rel="nofollow" class="button primary cartbtn pull-right">
					<i class="icon cart icon__click"></i>
					{t('Megrendelem')}
				</a>
			</div>
			{else}
				<h3>{t('Az Ön kosara üres')}.</h3>
                <a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
			{/if}
            {if (count($hozzavasarolttermekek)>0)}
            <div class="row">
                <div class="span10">
                <div class="blockHeader">
                    <h2 class="main">{t('Vásárlóink ajánlják Önnek')}</h2>
                </div>
                <div id="hozzavasarolttermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                    {$lntcnt=count($hozzavasarolttermekek)}
                    {$step=3}
                    {for $i=0 to $lntcnt-1 step $step}
                        <div>
                        {for $j=0 to $step-1}
                            {if ($i+$j<$lntcnt)}
                            {$_termek=$hozzavasarolttermekek[$i+$j]}
                            <div class="textaligncenter pull-left" style="width:{100/$step}%">
                                <div class="termekSliderTermekInner">
                                    <a href="/termek/{$_termek.slug}">
                                        <div class="termekSliderImageContainer">
                                            <img src="{$imagepath}{$_termek.minikepurl}" title="{$_termek.caption|lower|capitalize}" alt="{$_termek.caption|lower|capitalize}">
                                        </div>
                                        <div>{$_termek.caption|lower|capitalize}</div>
                                        <div>{$_termek.cikkszam}</div>
                                        <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} {$valutanemnev}</span></h5>
                                    </a>
                                </div>
                            </div>
                            {/if}
                        {/for}
                        </div>
                    {/for}
                </div>
                </div>
            </div>
            {/if}
		</div>
	</div>
</div>
{/block}
