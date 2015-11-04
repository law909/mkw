<div id="cimkefiltercontainer">
    {foreach $cimkekat as $_cimkekat}
        <div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkefiltercloseupbutton" data-refcontrol="#{$_cimkekat.sanitizedcaption}">
            <a href="#" class="mattedit-titlebar-close">
                <span class="ui-icon ui-icon-circle-triangle-n"></span>
            </a>
            <span>{$_cimkekat.caption}</span>
        </div>
        <div id="{$_cimkekat.sanitizedcaption}" class="js-cimkefilterpage cimkelista" data-visible="visible">
            {foreach $_cimkekat.cimkek as $_cimke}
                <a class="js-cimkefilter" href="#" data-id="{$_cimke.id}">{$_cimke.caption}</a>&nbsp;&nbsp;
            {/foreach}
        </div>
    {/foreach}
</div>
