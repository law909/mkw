{extends "base.tpl"}

{block "meta"}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{$blogposzt.cim|escape}">
    <meta property="og:url" content="{$canonical|escape}">
    <meta property="og:description" content="{$blogposzt.kivonat|strip_tags|strip|trim|escape}">
    {if ($blogposzt.kepurllarge)}<meta property="og:image" content="{$canonicalbase}{$blogposzt.kepurllarge|escape}">{/if}
    <meta property="article:published_time" content="{$blogposzt.megjelenesdatumiso}">
    {if ($blogposzt.lastmodiso)}<meta property="article:modified_time" content="{$blogposzt.lastmodiso}">{/if}
{/block}

{block "kozep"}
{include 'morzsa.tpl'}
{$blogjsonld|default}
<div class="container whitebg">
    <article class="blogposzt">
		<div class="row">
                    <div class="span10 offset1">
                        <h1>{$blogposzt.cim}</h1>
                        <div class="blogmeta">
                            <span>{t('Megjelent')}: {$blogposzt.megjelenesdatumstr}</span>
                            {if ($blogposzt.lastmodstr && ($blogposzt.lastmodstr != $blogposzt.megjelenesdatumstr))}
                                <span>{t('Frissítve')}: {$blogposzt.lastmodstr}</span>
                            {/if}
                            {if ($blogszerzo.nev)}
                                <span>{t('Szerző')}: {$blogszerzo.nev}{if ($blogszerzo.leiras)}, {$blogszerzo.leiras}{/if}</span>
                            {/if}
                        </div>
                        {$blogposzt.szoveg}
                    </div>
                </div>
    </article>
</div>
{/block}
