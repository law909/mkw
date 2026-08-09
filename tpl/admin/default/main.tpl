{extends "../base.tpl"}

{block "inhead"}
{/block}

{block "kozep"}
    <div class="component-container">
    <div class="js-noallapotbody">{t('Betöltés')}…</div>
    </div>
    <div class="component-container">
    {include "comp_apierrorlog.tpl"}
    </div>
{/block}