{extends "base.tpl"}

{block "kozep"}
<div class="container page-header">
    <div class="row">
        <div class="col">
            {include 'morzsa.tpl'}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h1 class="page-header__title">{t('Márkák')}</h1>
        </div>
    </div>
</div>
<div class="container whitebg">
    <div class="row">
        {foreach $markalista as $_marka}
            <div class="span2 markacontainer">
                <span class="markahelper"></span>
                <a href="{$_marka.termeklisturl}">{if ($_marka.kiskepurl)}<img class="markaimg" src="{$imagepath}{$_marka.kiskepurl}" alt="{$_marka.caption}">{else}{$_marka.caption}{/if}</a>
            </div>
        {/foreach}
    </div>
</div>
{/block}
