<div>
    <label for="NyelvEdit">{t('Nyelv')}:</label>
    <select id="NyelvEdit" name="nyelv" class="mattable-important">
        <option value="">{t('mindegy')}</option>
        {foreach $nyelvlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>