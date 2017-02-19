<div>
    <label for="NyelvEdit">{at('Nyelv')}:</label>
    <select id="NyelvEdit" name="nyelv" class="mattable-important">
        <option value="">{at('mindegy')}</option>
        {foreach $nyelvlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>