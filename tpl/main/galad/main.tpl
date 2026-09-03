{extends "base.tpl"}

{block "body"}
    <div class="row">
        {foreach $menu1 as $_menupont}
            <div class="col-md-4">
                <div class="szindoboz">
                    <a href="{$_menupont.link}">
                        {if ($_menupont.kozepeskepurl)}<img src="{$imagepath}{$_menupont.kozepeskepurl}" class="szinkep">{/if}
                        <div class="szinszoveg">{$_menupont.caption}</div>
                    </a>
                </div>
            </div>
        {/foreach}
    </div>
{/block}
