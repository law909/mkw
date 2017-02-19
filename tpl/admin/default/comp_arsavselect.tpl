<div>
    <label for="ArsavEdit">{at('Ársáv')}:</label>
    <select id="ArsavEdit" name="arsav">
        <option value="">{at('válasszon')}</option>
        {foreach $arsavlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>