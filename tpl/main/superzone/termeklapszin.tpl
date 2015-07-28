{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{$termek.cikkszam} {$termek.caption}</h3>
        </div>
    </div>
    <div class="row">
        {foreach $termek.valtozatok as $_valt}
        <div class="col-md-4">
            <div class="szindoboz">
                <a href="{$_valt.link}">
                    <img src="{$_valt.kepurlmedium}" class="szinkep">
                    <div class="szinszoveg">{$_valt.caption}</div>
                </a>
            </div>
        </div>
        {/foreach}
    </div>
{/block}