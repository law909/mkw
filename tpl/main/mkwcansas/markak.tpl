{extends "base.tpl"}

{block "kozep"}
{include 'morzsa.tpl'}
<div class="container whitebg">
    <div class="row">
        {foreach $markalista as $_marka}
            <div class="span2 markacontainer">
                <span class="markahelper"></span>
                <a href="{$_marka.termeklisturl}">{if ($_marka.kiskepurl)}<img class="markaimg" src="{$_marka.kiskepurl}" alt="{$_marka.caption|escape}">{else}{$_marka.caption}{/if}</a>
            </div>
        {/foreach}
    </div>
</div>
{/block}