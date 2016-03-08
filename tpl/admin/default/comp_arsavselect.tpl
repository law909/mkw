<div>
    <label for="ArsavEdit">{t('Ársáv')}:</label>
    <select id="ArsavEdit" name="arsav">
        <option value="">{t('válasszon')}</option>
        {foreach $arsavlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>