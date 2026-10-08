{* A törzshöz kapcsolt dokumentumok linkjei a listákon; $doklinkek = Dokumentumtar::toLinkArray() tömbök *}
{if ($doklinkek)}
    <div class="doklinkek">
        {foreach $doklinkek as $_dok}
            <div>
                {if ($_dok.url)}
                    <a href="{$_dok.url}" target="_blank" rel="noopener" title="{$_dok.url}">{$_dok.nev}</a>
                {/if}
                {if ($_dok.path)}
                    <a href="{$_dok.path}" target="_blank" rel="noopener"
                       title="{$_dok.path}">{if ($_dok.url)}({at('fájl')}){else}{$_dok.nev}{/if}</a>
                {/if}
            </div>
        {/foreach}
    </div>
{/if}
