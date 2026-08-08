<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{$pagetitle|default:'Médiatár'}</title>
    <link rel="stylesheet" type="text/css" media="screen" href="/themes/ui/{$uitheme}/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/default/mediatar.css"/>
    <script type="text/javascript" src="/js/admin/default/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-ui-1.14.2.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-ui-i18n-hu.js"></script>
</head>
<body class="mediatar">

<div id="mediatar"
     data-type="{$mtype|escape}"
     data-path="{$mpath|escape}"
     data-sel="{$msel|escape}"
     data-cb="{$mcb|default:0}"
     data-funcnum="{$mfuncnum|default:0}"
     data-maxsize="{$mmaxsize|default:0}"
     data-writable="{if $mwritable}1{else}0{/if}">

    {if $mimgpostwarnings}
        <div class="mt-warn">
            {foreach $mimgpostwarnings as $w}
                <div>{$w|escape}</div>
            {/foreach}
        </div>
    {/if}

    <div class="mt-head">
        <div class="mt-crumbs" id="mtCrumbs"></div>
        <div class="mt-headtools">
            <input type="text" id="mtSearch" class="mt-search" placeholder="{at('Szűrés a mappában')}"/>
            {if $mwritable}
                <button type="button" id="mtNewFolder">{at('Új mappa')}</button>
                <button type="button" id="mtUploadBtn">{at('Feltöltés')}…</button>
                <input type="file" id="mtFile" multiple style="display:none"/>
            {/if}
        </div>
    </div>

    <div class="mt-grid" id="mtGrid" tabindex="0">
        <div class="mt-empty">{at('Betöltés')}…</div>
    </div>

    <div class="mt-drop" id="mtDrop">
        <span>{at('Húzd ide a feltöltendő fájlokat')}</span>
    </div>

    <div class="mt-queue" id="mtQueue"></div>

    <div class="mt-foot">
        <div class="mt-info" id="mtInfo">
            {at('Engedélyezett')}: {$mextensions|escape} &middot; {at('max')}. {$mmaxsizetext|escape}
        </div>
        <div class="mt-foottools">
            {if $mwritable}
                <button type="button" id="mtRename" disabled="disabled">{at('Átnevezés')}</button>
                <button type="button" id="mtDelete" disabled="disabled">{at('Törlés')}</button>
            {/if}
            <button type="button" id="mtSelect" class="mt-primary" disabled="disabled">{at('Kiválaszt')}</button>
            <button type="button" id="mtCancel">{at('Mégse')}</button>
        </div>
    </div>

</div>

<script type="text/javascript" src="/js/admin/default/mediatarbrowse.js"></script>
</body>
</html>
