<div>
    <label for="bizonylatstatusz">Státusz:</label>
    <select id="bizonylatstatusz" name="bizonylatstatusz">
        <option value="">Mindegy</option>
        {foreach $bizonylatstatuszlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
        {/foreach}
    </select>
</div>