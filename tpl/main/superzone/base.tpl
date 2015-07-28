{extends "basestone.tpl"}

{block "bodyclass"}class="body"{/block}
{block "stonebody"}
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="/themes/main/superzone/minilogo.jpg">
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                {foreach $menu1 as $_menupont}
                    <li data-termekfa="1">
                        <a href="#">{$_menupont.caption}</a>
                        <div class="submenu">
                            <ul>
                                {foreach $_menupont.children as $_mpelem}
                                <li><a href="{$_mpelem.link}">{$_mpelem.cikkszam}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </li>
                {/foreach}
                <li><a href="/fiok">Account</a></li>
                <li><a href="#">Cart</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container content-back">
    {block "body"}{/block}
</div>
{/block}
