{extends "basestone.tpl"}

{block "bodyclass"}class="body"{/block}
{block "stonebody"}
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Mugen Proshop</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                {foreach $menu1 as $_menupont}
                    <li><a href="#">{$_menupont.caption}</a></li>
                {/foreach}
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Cart</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
{block "body"}{/block}
</div>
{/block}
