{* „Gyakran használt" menüszakasz: a dolgozó legtöbbet megnyitott menüpontjai (Services\MenuHasznalatService) *}
<div class="menu-titlebar mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all js-gyakranhasznalttoggle"
     title="{t('Nyitás/zárás')}">
    <span class="ui-icon menu-titlebar-icon {if ($gyakrannyitva)}ui-icon-circle-triangle-n{else}ui-icon-circle-triangle-s{/if}"></span>
    <span class="ui-jqgrid-title">{t('Gyakran használt')}</span>
</div>
<div class="menu-csoport js-gyakranhasznalt"{if (!$gyakrannyitva)} style="display:none;"{/if}>
    {foreach $gyakranhasznalt as $_gyakori}
        <div><a class="menupont ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only {$_gyakori.class}"
                href="{$_gyakori.url}"><span class="ui-button-text">{t($_gyakori.nev)}</span></a></div>
    {foreachelse}
        <div class="menu-gyakranhasznalt-ures">{t('A többször megnyitott képernyők itt jelennek meg.')}</div>
    {/foreach}
</div>
