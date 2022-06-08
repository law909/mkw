{extends "base.tpl"}

{block "body"}
<div class="page">
    <img src="{$mnrstatic.kepurl}" class="bgimg">
    <div class="szlogen1">{$mnrstatic.szlogen1}</div>
    <div class="szlogen2">{$mnrstatic.szlogen2}</div>
</div>
{foreach $mnrstatic.pages as $page}
    <div class="page">
        <img src="{$page.kepurl}" class="pageimg">
        <div class="pageszlogencontainer">
            <div class="pageszlogen1">{$page.szlogen1}</div>
            <div class="pageszlogen2">{$page.szlogen2}</div>
        </div>
        <div class="pagetartalomcontainer">
            <span class="pagetartalom">{$page.tartalom}</span>
        </div>
    </div>
{/foreach}
{/block}