{extends "base.tpl"}

{block "kozep"}
    {$versenyzojsonld|default}
    <div class="container page-header">
        <div class="row">
            <div class="col">
                {include 'morzsa.tpl'}
            </div>
        </div>
    </div>
    <div class="sponsored-riders-datasheet">
        <article class="sponsored-riders-datasheet__article">
            <div class="row">
                <div class="col ">
                    {if ($versenyzo.kepurl12000)}
                        <div class="sponsored-riders-datasheet__image-wrapper">
                            <img src="{$imagepath}{$versenyzo.kepurl12000}" alt="{if ($versenyzo.kepleiras1)}{$versenyzo.kepleiras1|escape}{else}{$versenyzo.nev|escape}{/if}"
                                 class="sponsored-riders-datasheet__image" fetchpriority="high">
                        </div>
                    {/if}
                    <div class="sponsored-riders-datasheet__meta">
                        {if ($versenyzo.csapatnev)}
                            <div class="sponsored-riders-datasheet__category">
                                {$versenyzo.csapatnev}
                            </div>
                        {/if}
                        <h1 class="sponsored-riders-datasheet__title">{$versenyzo.nev}</h1>
                        {if ($versenyzo.versenysorozat)}
                            <div class="sponsored-riders-datasheet__category">
                                {$versenyzo.versenysorozat}
                            </div>
                        {/if}
                        {if ($versenyzo.rovidleiras)}
                            <div class="sponsored-riders-datasheet__lead">
                                {$versenyzo.rovidleiras}
                            </div>
                        {/if}
                    </div>
                    {if ($versenyzo.kepurl22000)}
                        <img src="{$imagepath}{$versenyzo.kepurl22000}" alt="{if ($versenyzo.kepleiras2)}{$versenyzo.kepleiras2|escape}{else}{$versenyzo.nev|escape}{/if}" class="sponsored-riders-datasheet__image">
                    {/if}
                    {if ($versenyzo.leiras)}
                        <div class="sponsored-riders-datasheet__content">
                            {$versenyzo.leiras}
                        </div>
                    {/if}
                    {* kereskedelmi kilépés a versenyzőoldalról: enélkül a lap zsákutca *}
                    {if ($menu1[0].children[0].slug|default)}
                        <div class="sponsored-riders-datasheet__cta textaligncenter">
                            <a href="/categories/{$menu1[0].children[0].slug}" class="button bordered">{t('Nézd meg a Mugen Race felszereléseket')}</a>
                        </div>
                    {/if}
                    {if ($versenyzo.kepurl32000)}
                        <img src="{$imagepath}{$versenyzo.kepurl32000}" alt="{if ($versenyzo.kepleiras3)}{$versenyzo.kepleiras3|escape}{else}{$versenyzo.nev|escape}{/if}" class="sponsored-riders-datasheet__image">
                    {/if}
                </div>
            </div>
        </article>
    </div>
{/block}