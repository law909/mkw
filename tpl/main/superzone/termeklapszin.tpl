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
            <a href="#">
            <img src="{$_valt.kepurlmedium}">
            <div>{$_valt.caption}</div>
            </a>
        </div>
        {/foreach}
    </div>
{/block}