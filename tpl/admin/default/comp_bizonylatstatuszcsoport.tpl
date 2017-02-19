<div>
    <label for="bizonylatstatuszcsoport">{at('Státusz csoport')}:</label>
    <select id="bizonylatstatuszcsoport" name="bizonylatstatuszcsoport">
        <option value="">{at('Mindegy')}</option>
        {foreach $bizonylatstatuszcsoportlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
        {/foreach}
    </select>
</div>