{extends "basestone.tpl"}

{block "stonebody"}
    <a class="skip" href="#tartalom">{t('Ugrás a tartalomra')}</a>

    <header class="fejlec">
        <div class="sav">
            <div class="hasab fejlecsor">
                <a class="logo" href="/">
                    {if ($logo)}
                        <img src="{$imagepath}{$logo}" alt="{$globaltitle|escape}">
                    {else}
                        <span class="logonev">{$globaltitle|default:'Lampion 2000'|escape}</span>
                    {/if}
                </a>
                <form class="kereso" method="get" action="/kereses" role="search">
                    <input type="search" name="keresett" value="{$keresett|default:''|escape}"
                           placeholder="{t('Keresés a katalógusban')}" aria-label="{t('Keresés a katalógusban')}">
                    <button type="submit">{t('Keresés')}</button>
                </form>
                <button type="button" class="menugomb js-menugomb" aria-expanded="false" aria-controls="fomenu">
                    <span></span><span></span><span></span>
                    <em>{t('Menü')}</em>
                </button>
            </div>
        </div>

        <nav class="fomenu" id="fomenu">
            <div class="hasab">
                <ul class="menusor">
                    {foreach $menu1|default:[] as $_menupont}
                        <li{if ($_menupont.children|default)} class="vanalmenu"{/if}>
                            <a href="{$_menupont.link}">{$_menupont.caption}</a>
                            {if ($_menupont.children|default)}
                                <div class="almenu">
                                    <ul>
                                        {foreach $_menupont.children as $_almenu}
                                            <li><a href="{$_almenu.link}">{$_almenu.caption}</a></li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
            </div>
        </nav>
    </header>

    <main id="tartalom">
        {include "morzsa.tpl"}
        {block "kozep"}{/block}
    </main>

    <footer class="lablec">
        <div class="hasab lablecsor">
            <div>
                <div class="lablecnev">{$globaltitle|default:'Lampion 2000'|escape}</div>
                <p>{t('Ez az oldal termékkatalógus: a kínálatunk böngészhető, megrendelés nem adható le rajta.')}</p>
            </div>
            <div>
                <div class="lableccim">{t('Kategóriák')}</div>
                <ul>
                    {foreach $menu1|default:[] as $_menupont}
                        <li><a href="{$_menupont.link}">{$_menupont.caption}</a></li>
                    {/foreach}
                </ul>
            </div>
            <div>
                <div class="lableccim">{t('Kapcsolat')}</div>
                <ul>
                    <li><a href="/kapcsolat">{t('Írjon nekünk')}</a></li>
                    <li><a href="/hirek">{t('Hírek')}</a></li>
                </ul>
            </div>
        </div>
        <div class="hasab copyright">&copy; {'Y'|date} {$globaltitle|default:'Lampion 2000'|escape}</div>
    </footer>
{/block}
