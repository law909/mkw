{extends "base.tpl"}

{block "precss"}
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2021/splide/splide-sea-green.min.css?v=1">
{/block}

{block "prescript"}
    <script src="/themes/main/mugenrace2021/splide/splide.min.js?v=1"></script>
{/block}

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
            <section id="splide_{$page.id}" class="rc-img-container splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        {foreach $page.kepek as $kep}
                            <li class="splide__slide">
                                <img
                                    src="{$imagepath}{$kep.kepurl}"
                                    class="rc-img"
                                    alt="{$page.szoveg1}"
                                >
                            </li>
                        {/foreach}
                    </ul>
                </div>
                <div class="rc-text-container">
                    <div class="rc-text1">{$page.szoveg1}</div>
                    <div class="rc-text-23">
                        <div class="rc-text2">{$page.szoveg2}</div>
                        <div class="rc-text3">{$page.szoveg3}</div>
                    </div>
                    <svg
                        class="rc-img-bottom-triangle"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 100 100"
                        preserveAspectRatio="none"
                    >
                        <polygon fill="white" points="0,100 100,100 100,0"/>
                    </svg>
                </div>
            </section>
        </div>
    {/foreach}
{/block}

{block "endscript"}
    <script src="/js/main/mugenrace2021/mnrstaticracing.js?v=2"></script>
{/block}
