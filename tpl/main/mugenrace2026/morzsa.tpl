{* Látható morzsalánc + BreadcrumbList JSON-LD. A $morzsalanc-ot a controller állítja elő a
   Services\SeoService::buildBreadcrumb()-bel: az első elem a Főoldal, az utolsó (ahol állunk) nem link.
   A markup a data-vocabulary.org RDFa-t váltja ki, amit a Google 2020 óta nem értelmez. *}
{if ($morzsalanc|default)}
    <nav class="page-header__breadcrumb flex-lc" aria-label="{t('Morzsalánc')}">
        {foreach $morzsalanc as $_morzsa}
            {if (!$_morzsa@first)}<i class="icon arrow-right"></i>{/if}
            {if ($_morzsa.url)}<a href="{$_morzsa.url|escape}">{$_morzsa.caption}</a>{else}
                <span class="morzsaaktualis">{$_morzsa.caption}</span>{/if}
        {/foreach}
    </nav>
    {$morzsajsonld}
{/if}
