{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
	<h2 class="textaligncenter">{t('Nem találjuk, amit keres')}.</h2>

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
                                <img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                            </div>
                            <div>{$_termek.caption}</div>
                            <h5>
                                <span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span>
                            </h5>
                            <a href="{$_termek.link}" class="btn okbtn">{t('Részletek')}</a>
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