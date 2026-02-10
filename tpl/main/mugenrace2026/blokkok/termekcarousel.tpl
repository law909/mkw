{if (!isset($termeklista))}
    {if ($blokk.tipus=='5')}
        {$termeklista = $ajanlotttermekek}
    {elseif ($blokk.tipus=='6')}
        {$termeklista = $legnepszerubbtermekek}
    {elseif ($blokk.tipus=='7')}
        {$termeklista = $legujabbtermekek}
    {/if}
{/if}

{if (!isset($hatterszin))}
    {$hatterszin = 'dark'}
{/if}

{if ( $termeklista && count($termeklista)>0 )}
    <section {if (isset($blokk))}id="{$blokk.id}"{/if}
             class="featured-collection-slider featured-collection-slider__{$hatterszin} carousel-section  {if (isset($blokk))}{$blokk.cssclass} {/if}" {if (isset($blokk.cssstyle) && $blokk.cssstyle)} style="{$blokk.cssstyle}" {/if}>
        <div class="container section-header small row flex-cb">
            <div class="col flex-lc flex-col ta-l">
                <h2>{if (isset($fejlecszoveg))} {$fejlecszoveg} {else} {$blokk.nev} {/if}</h2>
                <p></p>
            </div>
            <div class="col flex-cr">
                <div class="carousel-controls">
                    <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                    <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                </div>
            </div>
        </div>

        <div class="carousel-container">
            <div class="carousel-wrapper product-list">
                {$lntcnt=count($termeklista)}
                {$step=3}
                {for $i=0 to $lntcnt-1 step $step}
                    {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                            {$_termek=$termeklista[$i+$j]}
                            {include 'blokkok/termek.tpl' termek=$_termek  detailsbutton=false}
                        {/if}
                    {/for}
                {/for}

            </div>
            {* <div class="carousel-dots" id="carouselDots"></div> *}
        </div>
    </section>
{/if}
