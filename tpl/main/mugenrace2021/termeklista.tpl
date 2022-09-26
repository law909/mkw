{extends "base.tpl"}

{block "body"}
    <div>
        <div class="filter"></div>
        <div class="termekgrid">
            {if ($lapozo.elemcount>0)}
            {$termekcnt=count($termekek)}
            {for $i=0 to $termekcnt-1}
                {$_termek=$termekek[$i]}
                {if ($_termek)}
                    <div class="termekbox" itemscope itemtype="http://schema.org/Product">
                        <div class="termekfokep">
                            <a href="/termek/{$_termek.slug}"><img itemprop="image" src="{$imagepath}{$_termek.nagykepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a>
                        </div>
                        {if ($_termek.kepek|default)}
                            <div class="termekvaltozatslider">
                                {foreach $_termek.kepek as $_v}
                                <img src="{$_v}">{$_v} alt=""/>
                                {/foreach}
                            </div>
                        {/if}
                        <div class="termeknev">
                            <a href="/termek/{$_termek.slug}" itemprop="url"><span itemprop="name">{$_termek.caption}</span></a>
                        </div>
                        <div class="termekcikkszam">
                            <a href="/termek/{$_termek.slug}">{$_termek.cikkszam}</a>
                        </div>
                        <div class="termekrovidleiras">
                            <span>{$_termek.rovidleiras}</span>
                        </div>
                        <div class="">
                            <div class="termekertekeles"></div>
                            <div class="termekar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <span itemprop="price">{number_format($_termek.brutto,0,',',' ')} {$_termek.valutanemnev}</span>
                            </div>
                        </div>
                    </div>
                {/if}
            {/for}
            {else}
                {t('Nincs ilyen termék')}
            {/if}
        </div>
    </div>
{/block}