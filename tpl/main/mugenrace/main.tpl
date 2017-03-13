{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$globaltitle}">
    <meta property="og:url" content="http://www.mugenrace.com">
    <meta property="og:image" content="{$logo}">
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{$seodescription}">
{/block}

{block "body"}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
{/block}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
        <div class="span12">
            {if (count($korhintalista)>0)}
            <div id="korhinta" class="royalSlider contentSlider rsDefaultInv">
                {foreach $korhintalista as $_korhinta}
                <div>
                    <a href="{$_korhinta.url}"><img class="rsImg" src="{$_korhinta.kepurl}" alt="{$_korhinta.kepleiras}"></a>
                    <a class="rsCaption" href="{$_korhinta.url}">
                        <h2 class="main">{$_korhinta.nev}</h2>
                        <p>{$_korhinta.szoveg}</p>
                    </a>
                </div>
                {/foreach}
            </div>
            {/if}
            <div>
                <h1 class="main">{t('Üdvözöljük webáruházunkban!')}</h1>
            </div>
            {if (count($akciostermekek)>0)}
                <div class="blockHeader">
                    <h2 class="main">{t('Akciós termékeink')}</h2>
                </div>
                <div id="akciostermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                    {$lntcnt=count($akciostermekek)}
                    {$step=3}
                    {for $i=0 to $lntcnt-1 step $step}
                        <div>
                            {for $j=0 to $step-1}
                                {if ($i+$j<$lntcnt)}
                                    {$_termek=$akciostermekek[$i+$j]}
                                    <div class="textaligncenter pull-left" style="width:{100/$step}%">
                                        <div class="termekSliderTermekInner">
                                            <a href="/termek/{$_termek.slug}">
                                                <div class="termekSliderImageContainer">
                                                    <img src="{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                                    <div class="akciosplecsni"><span class="akciosplecsniszoveg">-{number_format(100 - ($_termek.bruttohuf / $_termek.eredetibruttohuf * 100),0,',',' ')} %</span></div>
                                                </div>
                                                <div>{$_termek.caption}</div>
                                                <span class="akciosarszoveg akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} Ft</span>
                                                <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
                                            </a>
                                        </div>
                                    </div>
                                {/if}
                            {/for}
                        </div>
                    {/for}
                </div>
            {/if}
            {if (count($legujabbtermekek)>0)}
            <div class="blockHeader">
                <h2 class="main">{t('Legújabb termékeink')}</h2>
            </div>
            <div id="legnepszerubbtermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                {$lntcnt=count($legujabbtermekek)}
                {$step=3}
                {for $i=0 to $lntcnt-1 step $step}
                    <div>
                    {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                        {$_termek=$legujabbtermekek[$i+$j]}
                        <div class="textaligncenter pull-left" style="width:{100/$step}%">
                            <div class="termekSliderTermekInner">
                                <a href="/termek/{$_termek.slug}">
                                    <div class="termekSliderImageContainer">
                                        <img src="{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                    </div>
                                    <div>{$_termek.caption}</div>
                                    <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
                                </a>
                            </div>
                        </div>
                        {/if}
                    {/for}
                    </div>
                {/for}
            </div>
            {/if}
            {if (count($ajanlotttermekek)>0)}
            <div class="blockHeader">
                <h2 class="main">{t('Ajánlott termékeink')}</h2>
            </div>
            <div id="ajanlotttermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                {$lntcnt=count($ajanlotttermekek)}
                {$step=3}
                {for $i=0 to $lntcnt-1 step $step}
                    <div>
                    {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                        {$_termek=$ajanlotttermekek[$i+$j]}
                        <div class="textaligncenter pull-left" style="width:{100/$step}%">
                            <div class="termekSliderTermekInner">
                                <a href="/termek/{$_termek.slug}">
                                    <div class="termekSliderImageContainer">
                                        <img src="{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                    </div>
                                    <div>{$_termek.caption}</div>
                                    <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
                                </a>
                            </div>
                        </div>
                        {/if}
                    {/for}
                    </div>
                {/for}
            </div>
            {/if}
        </div>
	</div>
</div>
{/block}
