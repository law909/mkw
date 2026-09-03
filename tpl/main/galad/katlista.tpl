{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$kategorianev|default:''}</h3>
        </div>
    </div>
    <div class="row">
        {foreach $children as $_kat}
            <div class="col-md-4">
                <div class="szindoboz">
                    <a href="{$_kat.link}">
                        {if ($_kat.kozepeskepurl)}<img src="{$imagepath}{$_kat.kozepeskepurl}" class="szinkep">{/if}
                        <div class="szinszoveg">{$_kat.caption}</div>
                    </a>
                </div>
            </div>
        {/foreach}
    </div>
{/block}
