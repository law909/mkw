{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
    <div class="row">
        <div class="span12">
            {foreach $markalista as $_marka}
                <div>
                <a href="{$_marka.termeklisturl}">{if ($_marka.kiskepurl)}<img src="{$_marka.kiskepurl}">{else}{$_marka.caption}{/if}</a>
                </div>
            {/foreach}
        </div>
    </div>
</div>
{/block}