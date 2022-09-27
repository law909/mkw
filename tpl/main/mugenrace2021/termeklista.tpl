{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/termeklista.js?v=2"></script>
{/block}

{block "body"}
    <div class="nav-spacer"></div>
    <div class="termekfa-kep" id="termekfa-kep">
        {if ($sketchfabmodelid)}
            <iframe title="Mugen overal 3D model"
                    class="c-viewer__iframe"
                    src="https://sketchfab.com/models/{$sketchfabmodelid}/embed?autostart=1&amp;autospin=1&amp;internal=1&amp;tracking=0&amp;ui_infos=0&amp;ui_snapshots=1&amp;ui_controls=0&amp;ui_stop=0&amp;ui_theme=dark&amp;ui_watermark=0"
                    id="object-frame"
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    xr-spatial-tracking="true"
                    execution-while-out-of-viewport="true"
                    execution-while-not-rendered="true"
                    web-share="false">
            </iframe>
        {else}
            <img src="{$imagepath}{$kepurl}"/>
        {/if}
    </div>
    <div class="termek-screen">
        <div class="termek-filter">
            <div class="termek-filter-inner">
                <div class="filter-upper-container">
                    <div class="filter-closer">
                        CLOSE
                    </div>
                    <div class="filter-cleaner">
                        <a id="filter-cleaner-button" href="#">CLEAR ALL</a>
                    </div>
                </div>
                {include 'comp_termekfilter.tpl'}
                <div class="filter-apply">
                    <button class="filter-apply-button">APPLY & SHOW PRODUCTS</button>
                </div>
                <div class="filter-opener">
                    FILTERS
                </div>
            </div>
        </div>
        <div class="termek-grid">
            {if ($lapozo.elemcount>0)}
            {$termekcnt=count($termekek)}
            {for $i=0 to $termekcnt-1}
                {$_termek=$termekek[$i]}
                {if ($_termek)}
                    {include 'comp_termekbox.tpl'}
                {/if}
            {/for}
            {else}
                {t('Nincs ilyen termék')}
            {/if}
        </div>
    </div>
{/block}
