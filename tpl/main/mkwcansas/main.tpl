{extends "base.tpl"}

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
<div class="container">
	<div class="row">
        <div class="span4">
            {if (count($topkategorialista)>0)}
            <div class="blockHeader">
            <h4>{t('Top')} {count($topkategorialista)} {t('kategória')}</h4>
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
            <h4>{t('Legfrissebb híreink')}</h4>
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
            <h4>{t('Kiemelt márkáink')}</h4>
            </div>
            {foreach $kiemeltmarkalista as $_marka}
                <div class="hirListBlock">
                    <div class="borderBottomColorOneExtraLight">
                        <dl class="spg-additional">
                            <dd class="title">
                                <a href="/szuro?pageno=1&filter={$_marka.termekfilter},">
                                    <img src="{$_marka.kiskepurl}" title="{$_marka.caption}" alt="{$_marka.caption}">
                                </a>
                            </dd>
                        </dl>
                    </div>
                </div>
            {/foreach}
            {/if}
            <div>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" style="cursor:pointer"></a>
<a class="addthis_button_facebook" style="cursor:pointer"></a>
<g:plusone size="small"></g:plusone>
<a class="addthis_button_iwiw" style="cursor:pointer"></a>
<a class="addthis_button_twitter" style="cursor:pointer"></a>
<a class="addthis_button_email" style="cursor:pointer"></a>
<a class="addthis_button_pinterest_pinit" style="cursor:pointer"></a>
</div>
<script type="text/javascript">var addthis_config = { "data_track_clickback":true };</script>
<script type="text/javascript">var addthis_config = { "data_track_addressbar":true };</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=quixoft"></script>
<!-- AddThis Button END -->
            </div>
        <div class="fb-like-box" data-href="http://www.facebook.com/pages/Mindent-Kapni-Web%C3%A1ruh%C3%A1z/182178395162369" data-width="100%" data-height="400" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
        </div>
        <div class="span8">
            {if (count($korhintalista)>0)}
            <div id="korhinta" class="royalSlider contentSlider rsDefaultInv">
                {foreach $korhintalista as $_korhinta}
                <div>
                    <a href="{$_korhinta.url}"><img class="rsImg" src="{$_korhinta.kepurl}" alt="{$_korhinta.kepleiras}"></a>
                    <a class="rsCaption" href="{$_korhinta.url}">
                        <h4>{$_korhinta.nev}</h4>
                        <p>{$_korhinta.szoveg}</p>
                    </a>
                </div>
                {/foreach}
            </div>
            {/if}
            {if (count($legnepszerubbtermekek)>0)}
            <div class="blockHeader">
                <h4>{t('Legnépszerűbb termékeink')}</h4>
            </div>
            <div id="legnepszerubbtermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                {$lntcnt=count($legnepszerubbtermekek)}
                {$step=3}
                {for $i=0 to $lntcnt-1 step $step}
                    <div>
                    {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                        {$_termek=$legnepszerubbtermekek[$i+$j]}
                        <div class="textaligncenter pull-left" style="width:{100/$step}%">
                            <div class="termekSliderTermekInner">
                                <a href="/termek/{$_termek.slug}">
                                    <div class="termekSliderImageContainer">
                                        <img src="{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                    </div>
                                    <div>{$_termek.caption}</div>
                                    <h5><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
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
                <h4>{t('Mások ezeket vásárolják most')}</h4>
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
                                    <h5><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
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