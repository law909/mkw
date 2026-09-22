<div>
    <label for="PartnertipusEdit">{at('Partnertípus')}:</label>
    <select id="PartnertipusEdit" name="partnertipus">
        <option value="">{at('válasszon')}</option>
        {foreach $partnertipuslist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>
