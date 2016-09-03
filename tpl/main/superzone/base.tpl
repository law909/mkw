{extends "basestone.tpl"}

{block "bodyclass"}class="body"{/block}

{block "stonebody"}
    <nav class="navbar navbar-default top-navbar">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">
                    <img src="{$logo}">
                </a>
                <ul class="nav navbar-nav top-navbar-nav">
                    <li{if ($uzletkoto.loggedin)} title="You are logged in as agent {$uzletkoto.nev}"{/if}>
                        <div class="headerbtnbefoglalo">
                            <a href="{$showaccountlink}" class="js-menupont js-headerbtn btn btn-primary">Account</a>
                        </div>
                    </li>
                    <li>
                        <div class="headerbtnbefoglalo">
                            <a id="minikosar" href="{$kosargetlink}" class="js-menupont js-headerbtn btn btn-success">{include "minikosar.tpl"}</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    {foreach $menu1 as $_menupont}
                        <li data-termekfa="1">
                            <a href="#" class="js-menupont">{$_menupont.caption}</a>
                            <div class="submenu">
                                <ul>
                                    {foreach $_menupont.children as $_mpelem}
                                        <li><a href="{$_mpelem.link}">{$_mpelem.cikkszam}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </div>
    </nav>
    {if ($uzletkoto.loggedin)}
    <div class="container content-back content-back-uk">
        <div>
            <span>You are ordering for <span class="bold">{$user.nev}.</span></span>
            <label>Choose an other customer:</label>
            <select name="partner" class="js-uzletkotopartnerselect">
                {foreach $ukpartnerlist as $ukp}
                <option value="{$ukp.id}">{$ukp.nev}</option>
                {/foreach}
            </select>
            <button type="button" class="js-changepartner btn btn-primary">Select</button>
            <span>or <a href="{$showregisztraciolink}">create</a> a new one.</span>
            <a class="pull-right" href="{$dologoutlink}">Logout</a>
        </div>
        {if ($uzletkoto.fo)}
            <div>You are executive agent {$uzletkoto.nev}. You can watch all of your agent's customers's orders in "All orders" menu.</div>
        {/if}
    </div>
    {/if}
    <div class="container content-back">
        {block "body"}{/block}
    </div>
{/block}
