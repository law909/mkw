<div>
    <label for="GyartoEdit">Gyártó:</label>
    <select id="GyartoEdit" name="gyarto">
        <option value="">{t('válasszon')}</option>
        {foreach $gyartolist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>
