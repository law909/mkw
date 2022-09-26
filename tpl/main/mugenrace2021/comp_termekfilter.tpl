<div class="filter-box">
    <div class="filter-header">CATEGORY</div>
    <div class="filter-filters">
        {foreach $categoryfilter as $cat}
            <div><a href="/termekfa/{$cat.slug}">{$cat.caption}</a></div>
        {/foreach}
    </div>
</div>
{foreach $szurok as $_szuro}
    <div class="filter-box">
        <div class="filter-header">{$_szuro.caption}</div>
        <div class="filter-filters" id="SzuroFej{$_szuro.id}">
            {foreach $_szuro.cimkek as $_ertek}
                <div>
                    <label for="SzuroEdit{$_ertek.id}">
                        <input id="SzuroEdit{$_ertek.id}" name="szuro_{$_szuro.id}_{$_ertek.id}" type="checkbox"{if ($_ertek.selected)} checked="checked"{/if}>{$_ertek.caption}{if ($_ertek.termekdb|default)} ({$_ertek.termekdb}){/if}
                    </label>
                </div>
            {/foreach}
        </div>
    </div>
{/foreach}
<div class="filter-apply">
APPLY
</div>
<div class="filter-opener">
    FILTERS
</div>
