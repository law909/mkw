{extends "../base.tpl"}

{block "inhead"}
{/block}

{block "kozep"}
    <div class="component-container">
    {include "comp_noallapot.tpl"}
    </div>
    <div class="component-container">
    {include "comp_apierrorlog.tpl"}
    </div>
{/block}