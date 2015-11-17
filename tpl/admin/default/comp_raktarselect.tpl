<div>
    <label for="RaktarEdit">{t('Raktár')}:</label>
    <select id="RaktarEdit" name="raktar" class="mattable-important">
        <option value="">{t('mindegy')}</option>
        {foreach $raktarlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>