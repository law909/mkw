{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/valtozatcikkszamatiras.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Változat cikkszám átírás')}</h3>
        </div>
        <div id="DefaTab" class="mattkarb-page" data-visible="visible">
            <p>
                {at('Érintett változatok száma')}: <span class="js-erintett">{$erintett}</span>
            </p>
            <div class="matt-hseparator"></div>
            <a href="/admin/termekvaltozat/cikkszamatiras" class="js-atirasbutton">{at('Átírás')}</a>
            <span class="js-atirasuzenet"></span>
            <p class="mattkarb-hint">
                {at('Azoknak a változatoknak írja át a cikkszámát TERMÉKCIKKSZÁM-színkód-méretkód alakra, amelyek termékfa ágán be van kapcsolva a szín+méretes cikkszám, és amelyeknek a cikkszámában még nincs benne a szín és a méret. A saját (egyedire állított) cikkszámhoz nem nyúl.')}
            </p>
        </div>
    </div>
{/block}
