{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
    <div class="row">
        {foreach $markalista as $_marka}
            <div class="span2 markacontainer">
                <span class="markahelper"></span>
                <a href="{$_marka.termeklisturl}">{if ($_marka.kiskepurl)}<img class="markaimg" src="{$imagepath}{$_marka.kiskepurl}">{else}{$_marka.caption}{/if}</a>
            </div>
        {/foreach}
    </div>
</div>
{/block}