{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$globaltitle}">
    <meta property="og:url" content="http://www.mugenrace.com">
    <meta property="og:image" content="{$logo}">
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{$seodescription}">
{/block}

{block "body"}
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
{/block}

{block "kozep"}
    <div class="container-full whitebg">
        <div id="MainContent">
            {foreach $blokklista as $_blokk}
                {if ($_blokk.tipus==1)}
                    {include 'blokkok/blokk.tpl' blokk=$_blokk}
                {elseif ($_blokk.tipus==2)}
                    {include 'blokkok/duplablokk.tpl' blokk=$_blokk}
                {elseif ($_blokk.tipus==3)}
                    {include 'blokkok/csapatok.tpl' blokk=$_blokk}
                {elseif ($_blokk.tipus==4)}
                    {include 'blokkok/versenyzok.tpl' blokk=$_blokk}
                {elseif ($_blokk.tipus==5 || $_blokk.tipus==6 || $_blokk.tipus==7)}
                    {include 'blokkok/termekcarousel.tpl' blokk=$_blokk}
                {elseif ($_blokk.tipus==8)}
                    {include 'blokkok/hirek.tpl' blokk=$_blokk}
                {/if}
            {/foreach}
        </div>
    </div>
{/block}
