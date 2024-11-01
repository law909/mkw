{foreach from=$popups item=popup}
    <style>
        .popup{$popup.id}_overlay {
            background-color: {$popup.overlaybackgroundcolor};
            opacity: {$popup.overlayopacity};
        }

        .popup{$popup.id}_content {
            width: {$popup.contentwidth|default:'60%'};
            height: {$popup.contentheight|default:'40%'};
            top: {$popup.contenttop|default:'30%'};
        {if ($popup.backgroundimageurl)} background-image: url('{$popup.backgroundimageurl}');
        {/if}
        }

        .popup{$popup.id}_close_button {
            color: {$popup.closebuttoncolor|default:'#000000'};
            background-color: {$popup.closebuttonbackgroundcolor|default:'#ffffff'};
        }
    </style>
    <div id="popup{$popup.id}" class="shopmodal" style="display:none;">
        <div class="shopmodal-overlay popup{$popup.id}_overlay"></div>
        <div class="shopmodal-content popup{$popup.id}_content">
            <span class="shopmodal-close-button popup{$popup.id}_close_button" data-popup-id="{$popup.id}">{$popup.closebuttontext}</span>
            <h1>{$popup.headertext}</h1>
            <p>{$popup.bodytext}</p>
        </div>
    </div>
{/foreach}

<link rel="stylesheet" type="text/css" href="/themes/main/mkwcansas/popup.css">

<script type="text/javascript">
    window.popupQueue = [];
    {foreach from=$popups item=popup}
    window.popupQueue.push({
        id: {$popup.id},
        displayTime: {$popup.displaytime},
        triggerAfterPrevious: {if !$popup.triggerafterprevious}false{else}{$popup.triggerafterprevious}{/if}
    });
    {/foreach}
</script>

<script src="/js/main/mkwcansas/popup.js"></script>
