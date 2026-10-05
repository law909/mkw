<div id="meret{$termekid}" class="pull-left gvaltozatcontainer">
    <div class="pull-left gvaltozatnev termekvaltozat">{t('Méret')}:</div>
    <div class="pull-left gvaltozatselect">
        <div class="option-selector size-selector" data-termek="{$termekid}">
            {foreach $meretek as $_v}
                <div class="select-option {if ($_v.keszlet <= 0)} disabled{/if}" data-value="{$_v.id}">{$_v.caption}</div>
            {/foreach}
        </div>
        <select class="js-meretvaltozatedit custom-select valtozatselect" data-termek="{$termekid}">
            <option value="">{t('Válasszon')}</option>
            {foreach $meretek as $_v}
                <option value="{$_v.id}"{if ($_v.keszlet <= 0)} disabled="disabled" class="piros"{/if}
                        data-brutto="{number_format($_v.brutto|default:0,0,',',' ')}"
                        data-eredetibrutto="{if ($_v.eredetibrutto|default:0 > 0)}{number_format($_v.eredetibrutto,0,',',' ')}{/if}">{$_v.caption}</option>
            {/foreach}
        </select>
    </div>
</div>
