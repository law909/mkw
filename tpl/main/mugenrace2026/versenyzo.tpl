{extends "base.tpl"}

{block "kozep"}
    <div class="sponsored-riders-datasheet">
        <article itemtype="http://schema.org/Article" itemscope="">
            <div class="row">
                <div class="col ">
                    {if ($versenyzo.kepurl12000)}
                        <div class="sponsored-riders-datasheet__image-wrapper">
                            <img src="{$imagepath}{$versenyzo.kepurl12000}" alt="{$versenyzo.kepleiras1}" class="sponsored-riders-datasheet__image">
                        </div>
                    {/if}
                    <div class="sponsored-riders-datasheet__meta">
                        {if ($versenyzo.csapatnev)}
                            <div class="sponsored-riders-datasheet__category">
                                {$versenyzo.csapatnev}
                            </div>
                        {/if}
                        <h2 class="sponsored-riders-datasheet__title">{$versenyzo.nev}</h2>
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
                        <img src="{$imagepath}{$versenyzo.kepurl22000}" alt="{$versenyzo.kepleiras2}" class="sponsored-riders-datasheet__image">
                    {/if}
                    {if ($versenyzo.leiras)}
                        <div class="sponsored-riders-datasheet__content">
                            {$versenyzo.leiras}
                        </div>
                    {/if}
                    {if ($versenyzo.kepurl32000)}
                        <img src="{$imagepath}{$versenyzo.kepurl32000}" alt="{$versenyzo.kepleiras3}" class="sponsored-riders-datasheet__image">
                    {/if}
                </div>
            </div>
        </article>
    </div>
{/block}