{extends "base.tpl"}

{block "body"}
    <div class="mainpage">
        <img src="{$imagepath}{$mnrlanding.kepurl}" class="bgimg">
        <div class="szlogen-container">
            <div class="szlogen1">{$mnrlanding.nev}</div>
            <div class="szlogen2">{$mnrlanding.szlogen}</div>
        </div>
    </div>
{/block}

{block "endscript"}
    <script src="/js/main/mugenrace2021/mnrstatic.js?v=1"></script>
{/block}
