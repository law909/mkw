<article class="doboz">
    <a class="dobozkep" href="{$termek.link}">
        {if ($termek.kozepeskepurl)}
            <img src="{$termek.kozepeskepurl}" alt="{$termek.caption|escape}" loading="lazy">
        {else}
            <span class="nincskep" aria-hidden="true">🕯</span>
        {/if}
        {if ($termek.uj|default)}<span class="jelzo uj">{t('Újdonság')}</span>{/if}
    </a>
    <div class="dobozszoveg">
        <h3><a href="{$termek.link}">{$termek.caption}</a></h3>
        {if ($termek.cikkszam)}<div class="cikkszam">{$termek.cikkszam}</div>{/if}
        {if ($termek.rovidleiras)}<p class="rovid">{$termek.rovidleiras|strip_tags|truncate:110}</p>{/if}
        {if ($termek.bruttohuf|default) > 0}
            <div class="ar">{$termek.bruttohuf|bizformat:0} {$valutanemnev|default:'Ft'}</div>
        {/if}
    </div>
</article>
