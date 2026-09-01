{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/szallmegrpenzugylista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Szállítói megrendelések pénzügyi állása')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="datum"}<span>{at('Az időszak a szállítói megrendelés teljesítésére vonatkozik. A hozzá kapcsolt bizonylatok (bevét, szállítói előleg) és a rájuk könyvelt pénzügyi teljesítés az időszaktól függetlenül számít.')}</span>
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/szallmegrpenzugylista/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
