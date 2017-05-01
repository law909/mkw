<div>
    <label for="PenztarEdit">{at('Pénztár')}:</label>
    <select id="PenztarEdit" name="penztar" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $penztarlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>