{if ($morzsa|default:'')}
    <ol class="breadcrumb morzsa">
        <li><a href="/">{t('Főoldal')}</a></li>
        {foreach $morzsa as $_morzsa}
            {if ($_morzsa.link)}
                <li><a href="{$_morzsa.link}">{$_morzsa.caption}</a></li>
            {else}
                <li class="active">{$_morzsa.caption}</li>
            {/if}
        {/foreach}
    </ol>
{/if}
