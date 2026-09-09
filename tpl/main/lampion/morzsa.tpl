{if ($morzsa|default)}
    <nav class="morzsa" aria-label="{t('Hol járok')}">
        <div class="hasab">
            <a href="/">{t('Főoldal')}</a>
            {foreach $morzsa as $_elem}
                <span class="nyil">›</span>
                {if ($_elem.link)}
                    <a href="{$_elem.link}">{$_elem.caption}</a>
                {else}
                    <span class="itt">{$_elem.caption}</span>
                {/if}
            {/foreach}
        </div>
    </nav>
{/if}
