<div>
    <label for="BelsoUzletkotoEdit">{t('Belső üzletkötő')}:</label>
    <select id="BelsoUzletkotoEdit" name="belsouzletkoto" class="mattable-important">
        <option value="">{t('válasszon')}</option>
        {foreach $belsouklist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>