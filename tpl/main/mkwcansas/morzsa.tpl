{* Látható morzsalánc + BreadcrumbList JSON-LD. A $morzsalanc-ot a controller állítja elő a
   Services\SeoService::buildBreadcrumb()-bel: az első elem a Főoldal, az utolsó (ahol állunk) nem link. *}
{if ($morzsalanc|default)}
<div class="container morzsa whitebg">
    <div class="row">
        <nav class="span12 morzsaszoveg" aria-label="{t('Morzsalánc')}">
            {foreach $morzsalanc as $_morzsa}
                {if (!$_morzsa@first)}<span class="morzsaelvalaszto">›</span>{/if}
                {if ($_morzsa.url)}<a href="{$_morzsa.url|escape}">{$_morzsa.caption}</a>{else}<span class="morzsaaktualis">{$_morzsa.caption}</span>{/if}
            {/foreach}
        </nav>
    </div>
</div>
{$morzsajsonld}
{/if}
