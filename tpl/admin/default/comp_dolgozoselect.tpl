<div>
    <label for="DolgozoEdit">{at('Dolgozó')}:</label>
    <select id="DolgozoEdit" name="dolgozo" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $dolgozolist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>