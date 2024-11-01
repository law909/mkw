<style>
    .shopmodal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        z-index: 1000;
    }

    .shopmodal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .shopmodal-content {
        position: relative;
        margin: auto;
        padding: 20px;
        background-color: #fff;
        background-size: cover;
        z-index: 1001;
    }

    .shopmodal-close-button {
        position: absolute;
        top: 10px;
        right: 20px;
        cursor: pointer;
        padding: 1em;
    }

    .popup_overlay {
        background-color: {$popup.overlaybackgroundcolor};
        opacity: {$popup.overlayopacity};
    }

    .popup_content {
        width: {$popup.contentwidth|default:'60%'};
        height: {$popup.contentheight|default:'40%'};
        top: {$popup.contenttop|default:'30%'};
    {if ($popup.backgroundimageurl)} background-image: url('{$popup.backgroundimageurl}');
    {/if}
    }

    .popup_close_button {
        color: {$popup.closebuttoncolor|default:'#000000'};
        background-color: {$popup.closebuttonbackgroundcolor|default:'#ffffff'};
    }
</style>
<div id="popupteszt" class="shopmodal" style="display:none;">
    <div class="shopmodal-overlay popup_overlay"></div>
    <div class="shopmodal-content popup_content">
        <span class="shopmodal-close-button popup_close_button" data-popup-id="{$popup.id}">{$popup.closebuttontext}</span>
        <h1>{$popup.headertext}</h1>
        <p>{$popup.bodytext}</p>
    </div>
</div>

