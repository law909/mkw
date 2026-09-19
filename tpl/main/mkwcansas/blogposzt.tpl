{extends "base.tpl"}

{block "meta"}
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
                            <div class="blogmetadatum">
                                <span>{t('Megjelent')}: {$blogposzt.megjelenesdatumstr}</span>
                                {if ($blogposzt.lastmodstr && ($blogposzt.lastmodstr != $blogposzt.megjelenesdatumstr))}
                                    <span>{t('Frissítve')}: {$blogposzt.lastmodstr}</span>
                                {/if}
                            </div>
                            {if ($blogszerzo.nev)}
                                <div class="blogmetaszerzo">
                                    <div class="blogmetaszerzonev">{t('Szerző')}: {$blogszerzo.nev}</div>
                                    {if ($blogszerzo.leiras)}
                                        <div class="blogmetaszerzoleiras">{$blogszerzo.leiras}</div>
                                    {/if}
                                </div>
                            {/if}
                        </div>
                        {$blogposzt.szoveg}
                    </div>
                </div>
    </article>
</div>
{/block}
