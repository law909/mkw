{extends "base.tpl"}

{block "kozep"}
    <div class="hasab">
        <div class="listafej"><h1>{t('Hírek')}</h1></div>
        <div class="hirracs">
            {foreach $children as $_hir}
                <article class="hirdoboz">
                    <div class="hirdatum">{$_hir.datum}</div>
                    <h3><a href="/hir/{$_hir.slug}">{$_hir.cim}</a></h3>
                    <p>{$_hir.lead|strip_tags|truncate:200}</p>
                </article>
            {foreachelse}
                <p class="ures">{t('Jelenleg nincs megjeleníthető hír.')}</p>
            {/foreach}
        </div>
    </div>
{/block}
