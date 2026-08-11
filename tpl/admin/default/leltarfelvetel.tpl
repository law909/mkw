{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/leltarfelvetel.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Leltár felvétel')}</h3>
            {if (!$leltarhiba)}
                <div>{$leltarnev} {$nyitasstr}</div>
                <div>{at('Raktár')}: {$raktarnev}</div>
            {/if}
        </div>
        {if ($leltarhiba)}
            <div class="leltarfelvetel-hiba">{$leltarhiba}</div>
        {else}
            <div class="js-leltarfelvetel leltarfelvetel" data-leltarid="{$leltarid}">
                <table class="leltarfelvetel-tetelek ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <thead>
                    <tr>
                        <th>{at('Cikkszám')}</th>
                        <th>{at('Termék')}</th>
                        <th>{at('Gépi készlet')}</th>
                        <th>{at('Tény mennyiség')}</th>
                        <th>{at('Eltérés')}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="js-leltartetelek">
                    {foreach $tetelek as $_sor}
                        {$_sor nofilter}
                    {/foreach}
                    </tbody>
                </table>

                <div class="leltarfelvetel-vonalkodsor">
                    <label for="LeltarVonalkodEdit">{at('Vonalkód / keresés')}:</label>
                    <input id="LeltarVonalkodEdit" class="js-leltarkereso" type="text" autocomplete="off">
                    <span class="js-leltarkereshiba leltarfelvetel-hiba"></span>
                </div>
                <div class="js-leltarvaltozatvalaszto leltarfelvetel-valtozatvalaszto"></div>
                <p class="mattkarb-hint">
                    {at('Minden beolvasás azonnal mentődik, és eggyel növeli a tény mennyiséget. Ugyanaz a termék többször beolvasva összeadódik.')}
                </p>
            </div>
        {/if}
    </div>
{/block}
