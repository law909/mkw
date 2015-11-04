<div>
    <label for="PartnerEdit">{t('Partner')}:</label>
    <select id="PartnerEdit" name="partner" class="mattable-important">
        <option value="">{t('válasszon')}</option>
        {foreach $partnerlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>