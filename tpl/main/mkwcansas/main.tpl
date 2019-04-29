{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$globaltitle}">
    <meta property="og:url" content="http://www.mindentkapni.hu">
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
        <div class="span4">
            {if (count($topkategorialista)>0)}
            <div class="blockHeader">
            <h2 class="main">{t('Top')} {count($topkategorialista)} {t('kategória')}</h2>
            </div>
            {foreach $topkategorialista as $_topkategoria}
                <div class="hirListBlock">
                    <dl>
                        <dd class="title"><a href="/termekfa/{$_topkategoria.slug}">{$_topkategoria.caption}</a></dd>
                        <dd class="copy"><p>{$_topkategoria.rovidleiras}</p></dd>
                    </dl>
                </div>
            {/foreach}
            {/if}
            {if (count($hirek)>0)}
            <div class="blockHeader">
            <h2 class="main">{t('Legfrissebb híreink')}</h2>
            </div>
            {foreach $hirek as $_hir}
                <div class="hirListBlock">
                    <div class="borderBottomColorOneExtraLight">
                        <dl class="spg-additional">
                            <dd class="title"><a href="/hir/{$_hir.slug}">{$_hir.cim}</a></dd>
                            <dd class="copy"><p>{$_hir.lead}</p></dd>
                        </dl>
                    </div>
                </div>
            {/foreach}
            {/if}
            {if (count($kiemeltmarkalista)>0)}
            <div class="blockHeader">
            <h2 class="main">{t('Kiemelt márkáink')}</h2>
            </div>
            {foreach $kiemeltmarkalista as $_marka}
                <div class="hirListBlock">
                    <div class="borderBottomColorOneExtraLight">
                        <dl class="spg-additional">
                            <dd class="title">
                                <a href="{$_marka.termeklisturl}">
                                    <img src="{$_marka.kiskepurl}" title="{$_marka.caption}" alt="{$_marka.caption}">
                                </a>
                            </dd>
                        </dl>
                    </div>
                </div>
            {/foreach}
            {/if}
        <div class="fb-like-box" data-href="http://www.facebook.com/pages/Mindent-Kapni-Web%C3%A1ruh%C3%A1z/182178395162369" data-width="100%" data-height="400" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
        </div>
        <div class="span8">
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
                <h1 class="main">Háztartási webáruházunkban tényleg Mindent Kapni!</h1>
                <img src="\themes\main\mkwcansas\img\mindentkapni.hu-haztartasi-webaruhaz.jpg">
                <p>Az online vásárlás modern világunk egyik nagy találmánya. Anélkül vásárolhatunk ma már szinte
                bármit, hogy el kellene mennünk otthonról. Amennyiben military felszerelésekre, ruhákra, műszaki
                cikkekre, sportszerekre van szüksége, vagy játékokat keres gyermekének, érdemes felkeresnie
                    <b>háztartási webáruházunkat</b>!</p>
                <p><b>Háztartási webáruházunk</b> használatával egy helyről beszerezhet mindent, ami az otthonába,
                nyaralásához, vagy túrázáshoz kellhet. Termékeink között talál kempingfelszereléseket, ruházati
                cikkeket, konyhafelszereléseket éppúgy, mint labdajáték kellékeket. Egyszóval nálunk tényleg Mindent
                    Kapni!</p>
                <p><b>Háztartási webáruházunk</b> mindezt igyekszik kedvező áron biztosítani, hiszen az a célunk, hogy
                ügyfeleink elégedettek legyenek, és rendszeresen visszatérő vásárlókká váljanak. Folyamatos
                akciókkal, újabb termékekkel és pénzvisszafizetési garanciával várjuk Önt! Kattintson honlapunkra és
                    rendeljen; tőlünk csak a legjobbat kaphatja!</p>
                <p>Forduljon bizalommal <b>háztartási webáruházunkhoz</b>, hiszen érdemes körülnézni
                    termékkínálatunkban! Ha kérdése van, megadott elérhetőségeinken szívesen válaszolunk.</p>
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
                <h2 class="main">{t('Mások ezeket vásárolják most')}</h2>
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
