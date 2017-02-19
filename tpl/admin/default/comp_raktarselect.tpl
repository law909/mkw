<div>
    <label for="RaktarEdit">{at('Raktár')}:</label>
    <select id="RaktarEdit" name="raktar" class="mattable-important">
        <option value="">{at('mindegy')}</option>
        {foreach $raktarlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>