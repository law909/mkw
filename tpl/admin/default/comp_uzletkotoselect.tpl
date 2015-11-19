<div>
    <label for="UzletkotoEdit">{t('Üzletkötő')}:</label>
    <select id="UzletkotoEdit" name="uzletkoto" class="mattable-important">
        <option value="">{t('válasszon')}</option>
        {foreach $uklist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>