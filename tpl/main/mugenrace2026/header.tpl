{include "headerfirstrow.tpl"}

<div class="header container-full__with-padding">
    <div class="row">
        <div class="col flex-cl header__left">
            <a href="/"><img class="header__logo" src="/themes/main/mugenrace2026/img/mugen-logo-white.svg" alt="Mugenrace Webshop"
                             title="Mugenrace Webshop"></a>
        </div>
        <div class="col flex-cc header__center">
            <nav class="main-menu flex-cc">
                <ul id="" class="flex-cc">
                    {foreach $menu1[0]['children'] as $_menupont}
                        <li class="main-menu-item {if ($_menupont@last)} last{/if}" {if ($_menupont@first)} class="first"{/if}><a href="#"
                                                                                                                                  data-cnt="{count($_menupont.children)}">{$_menupont.nev_locale}</a>
                            {if (count($_menupont.children)>0)}
                                <i class="icon arrow-down white main-menu__arrow icon__click"></i>
                                <div class="sub">
                                    <div class="sub__wrapper">
                                        {foreach $_menupont.children as $_focsoport}
                                            <ul>
                                                <li class="categorytitle"><a href="/categories/{$_focsoport.slug}">{$_focsoport.nev_locale}</a></li>
                                                {foreach $_focsoport.children as $_alcsoport}
                                                    <li>
                                                        <a href="/categories/{$_alcsoport.slug}">{$_alcsoport.nev_locale}</a>
                                                        {if (count($_alcsoport.children)>0)}
                                                        <ul>
                                                            {/if}
                                                            {foreach $_alcsoport.children as $_alcsoport2}
                                                                <li>
                                                                    <a href="/categories/{$_alcsoport2.slug}">{$_alcsoport2.nev_locale}</a>
                                                                    {if (count($_alcsoport2.children)>0)}
                                                                    <ul>
                                                                        {/if}
                                                                        {foreach $_alcsoport2.children as $_alcsoport3}
                                                                            <li>
                                                                                <a href="/categories/{$_alcsoport3.slug}">{$_alcsoport3.nev_locale}</a>
                                                                                {if (count($_alcsoport3.children)>0)}
                                                                                <ul>
                                                                                    {/if}
                                                                                    {foreach $_alcsoport3.children as $_alcsoport4}
                                                                                        <li>
                                                                                            <a href="/categories/{$_alcsoport4.slug}">{$_alcsoport4.nev_locale}</a>

                                                                                        </li>
                                                                                    {/foreach}
                                                                                    {if (count($_alcsoport3.children)>0)}
                                                                                </ul>
                                                                                {/if}
                                                                            </li>
                                                                        {/foreach}
                                                                        {if (count($_alcsoport2.children)>0)}
                                                                    </ul>
                                                                    {/if}
                                                                </li>
                                                            {/foreach}
                                                            {if (count($_alcsoport.children)>0)}
                                                        </ul>
                                                        {/if}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/foreach}
                                    </div>
                                </div>
                            {/if}
                        </li>
                    {/foreach}

                    <li><a href="/news" title="{t('Legfrissebb híreink')}">{t('Legfrissebb híreink')}</a></li>
                    <li><a href="/statlap/about-us" title="{t('Rólunk')}">{t('Rólunk')}</a></li>
                    <li><a href="/riders" title="{t('Szponzorált versenyzők')}">{t('Szponzorált versenyzők')}</a></li>
                    <li><a href="/teams" title="{t('Csapatok')}">{t('Csapatok')}</a></li>
                </ul>
                <i class="icon close white main-menu__close icon__click"></i>
            </nav>
        </div>
        <div class="col flex-cr header__right">

            <nav class="right-menu flex-cc">
                <ul id="" class="flex-cc">
                    <li><i class="icon search white icon__click"></i></li>
                    <li>
                        {if $hidecart != 1}
                            <div id="minikosar">
                                {include "minikosar.tpl"}
                            </div>
                        {/if}
                    </li>
                    <li>
                        <i class="icon menu white menu-toggle icon__click"></i>
                    </li>
                </ul>
            </nav>

            <form id="searchform" class="header__searchform flex-cc" name="searchbox" method="get" action="/search" autocomplete="off">
                <div class="searchinputbox flex-cc">
                    <input id="searchinput" class="siteSearch span2" type="text" title="{t('Keressen a termékeink között!')}"
                           placeholder="{t('Keressen a termékeink között!')}" accesskey="k" value="" maxlength="300" name="keresett">
                </div>
                <i class="icon close white header__searchform-close icon__click"></i>
            </form>
        </div>
    </div>
</div>