{extends "base.tpl"}

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
                                    <h5 class="itemPrice"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
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
                <h4>{t('Ajánlott termékeink')}</h4>
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
                                    <h5 class="itemPrice"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
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