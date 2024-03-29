{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/termeklista.js?v=4"></script>
{/block}

{block "body"}
    <div class="nav-spacer"></div>
    <div class="termek-screen">
        <div class="termek-filter">
            <div class="termek-filter-inner">
                <div class="filter-upper-container">
                    <div class="filter-closer">
                        {t('BEZÁR')}
                    </div>
                    {if ($szurok)}
                        <div class="filter-cleaner">
                            <a id="filter-cleaner-button" href="#">{t('TÖRLÉS')}</a>
                        </div>
                    {/if}
                </div>
                {include 'comp_termekfilter.tpl'}
                {if ($szurok)}
                    <div class="filter-apply">
                        <button class="filter-apply-button">{t('SZŰRÉS')}</button>
                    </div>
                {/if}
                <div class="filter-opener">
                    {t('SZŰRŐK')}
                </div>
            </div>
        </div>
        <div class="termek-grid">
            {if ($sketchfabmodelid)}
                <div class="termek-box">
                    <div class="termek-innerbox">
                        {if (!$developer)}
                            <iframe
                                title="Mugen overal 3D model"
                                class="c-viewer__iframe"
                                src="https://sketchfab.com/models/{$sketchfabmodelid}/embed?autostart=1&amp;autospin=1&amp;internal=1&amp;tracking=0&amp;ui_infos=0&amp;ui_snapshots=1&amp;ui_controls=0&amp;ui_stop=0&amp;ui_theme=dark&amp;ui_watermark=0"
                                id="object-frame"
                                allow="autoplay; fullscreen; xr-spatial-tracking"
                                xr-spatial-tracking="true"
                                execution-while-out-of-viewport="true"
                                execution-while-not-rendered="true"
                                web-share="false"
                            >
                            </iframe>
                        {/if}
                    </div>
                </div>
            {/if}

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
