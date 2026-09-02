{* A törzshöz kapcsolt dokumentumok linkjei a listákon; $doklinkek = Dokumentumtar::toLinkArray() tömbök *}
{if ($doklinkek)}
    <div class="doklinkek">
        {foreach $doklinkek as $_dok}
            <div>
                {if ($_dok.url)}
                    <a href="{$_dok.url|escape}" target="_blank" rel="noopener" title="{$_dok.url|escape}">{$_dok.nev|escape}</a>
                {/if}
                {if ($_dok.path)}
                    <a href="{$_dok.path|escape}" target="_blank" rel="noopener"
                       title="{$_dok.path|escape}">{if ($_dok.url)}({at('fájl')}){else}{$_dok.nev|escape}{/if}</a>
                {/if}
            </div>
        {/foreach}
    </div>
{/if}
