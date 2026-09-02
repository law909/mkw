{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/bizonylatellenorzes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Tételek ellenőrzése')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page js-ellenorzes" data-visible="visible" data-bizonylat="{$egyed.id}">
                <div>
                    <label>{at('Bizonylat')}:</label>
                    {if ($egyed.listaurl)}
                        <a href="{$egyed.listaurl}" target="_blank"
                           title="{at('Ugrás a bizonylathoz')}">{$egyed.tipusnev} {$egyed.id}</a>
                    {else}
                        {$egyed.tipusnev} {$egyed.id}
                    {/if}
                    &nbsp;{$egyed.partnernev|escape}&nbsp;{at('kelt')}: {$egyed.keltstr}
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EllenorzesKeresoEdit">{at('Vonalkód / cikkszám / keresés')}:</label>
                    <input id="EllenorzesKeresoEdit" class="js-ellkereso mattable-important" type="text" size="50"
                           autocomplete="off" autofocus>
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EllenorzesMennyisegEdit">{at('Mennyiség')}:</label>
                    <input id="EllenorzesMennyisegEdit" class="js-ellmennyiseg" type="number" step="any" value="1" size="6">
                    <span class="js-ellhiba redtext"></span>
                </div>
                <div class="js-ellvaltozat"></div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-ellujra">{at('Újrakezdés')}</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny">
                    {include 'bizonylatellenorzestetel.tpl'}
                </div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
