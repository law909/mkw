<div>
    <label for="FizmodEdit">{at('Fizetési mód')}:</label>
    <select id="FizmodEdit" name="fizmod" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $fizmodlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>