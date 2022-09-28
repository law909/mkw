{extends "base.tpl"}

{block "body"}
    <div class="mainpage">
        <img src="{$imagepath}{$mnrstatic.kepurl}" class="bgimg">
        <div class="szlogen-container">
            <div class="szlogen1">{$mnrstatic.szlogen1}</div>
            <div class="szlogen2">{$mnrstatic.szlogen2}</div>
        </div>
    </div>
    {foreach $mnrstatic.pages as $page}
        <div class="page-container">
            <div class="page-padder"></div>
            <div class="page">
                <div class="page-img-container">
                    <svg class="page-img-top-triangle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <polygon fill="white" points="0,0 0,100 100,0"/>
                    </svg>
                    <img class="page-img" src="{$imagepath}{$page.kepurl}">
                    <div class="page-szlogen-container">
                        <div class="page-szlogen1">{$page.szlogen1}</div>
                        <div class="page-szlogen2">{$page.szlogen2}</div>
                    </div>
                    <svg class="page-img-bottom-triangle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <polygon fill="white" points="0,100 100,100 100,0"/>
                    </svg>
                </div>
                <div class="page-tartalom">{$page.tartalom}</div>
            </div>
        </div>
    {/foreach}
{/block}

{block "endscript"}
    <script src="/js/main/mugenrace2021/mnrstatic.js?v=2"></script>
{/block}
