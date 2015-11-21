{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
    <div class="row">
        {foreach $markalista as $_marka}
            <div class="span2">
                <a href="{$_marka.termeklisturl}">{if ($_marka.kiskepurl)}<img src="{$_marka.kiskepurl}">{else}{$_marka.caption}{/if}</a>
            </div>
        {/foreach}
    </div>
</div>
{/block}