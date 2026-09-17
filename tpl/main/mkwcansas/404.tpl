{extends "base.tpl"}

{block "meta"}
    <meta name="robots" content="noindex,follow">
{/block}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
	<h1 class="textaligncenter">Hoppá! Nem találjuk, amit keres.</h1>
	<p class="textaligncenter">Lehet, hogy a termék kikerült a kínálatból, vagy elgépelte a címet. Keressen rá, vagy induljon el valamelyik kategóriánkból!</p>
	</div>
	<div class="row">
		<div class="span12 textaligncenter">
			<form id="o404searchform" name="o404searchbox" method="get" action="/kereses" autocomplete="off">
				<input class="siteSearch" type="text" title="{t('Keressen a termékeink között!')}" placeholder="{t('Keressen a termékeink között!')}" maxlength="300" name="keresett">
				<button type="submit" class="btn okbtn">{t('Keresés')}</button>
			</form>
		</div>
	</div>
	{if (count($menu1))}
	<div class="row">
		<div class="span12">
			<h4 class="textaligncenter">Népszerű kategóriáink</h4>
			<ul class="o404katlista">
				{foreach $menu1 as $_menupont}
					<li><a href="/termekfa/{$_menupont.slug}">{$_menupont.caption}</a></li>
				{/foreach}
			</ul>
		</div>
	</div>
	{/if}
	<div class="row">
	<h4 class="textaligncenter">A következő termékeket ajánljuk Önnek:</h4>
	</div>
	<div class="row">
		<div class="span12">
        {$lntcnt=count($ajanlotttermekek)}
        {$step=3}
        {for $i=0 to $lntcnt-1 step $step}
            <div>
            {for $j=0 to $step-1}
                {if ($i+$j<$lntcnt)}
                {$_termek=$ajanlotttermekek[$i+$j]}
                <div class="textaligncenter pull-left" style="width:{100/$step}%">
                    <div class="o404TermekInner">
                        <a href="{$_termek.link}">
                            <div class="o404ImageContainer">
                                <img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}" loading="lazy">
                            </div>
                            <div>{$_termek.caption}</div>
                            <h5>
                                {if ($_termek.akcios)}
                                <span><span class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} Ft</span> helyett {number_format($_termek.bruttohuf,0,',',' ')} Ft</span>
                                {else}
                                <span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span>
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
	</div>
</div>
{/block}
