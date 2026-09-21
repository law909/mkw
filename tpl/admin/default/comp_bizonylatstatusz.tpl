<div>
    <label for="bizonylatstatusz">{at('Státusz')}:</label>
    <select id="bizonylatstatusz" name="bizonylatstatusz">
        <option value="">{at('Mindegy')}</option>
        {foreach $bizonylatstatuszlist as $_role}
            <option value="{$_role.id}" data-bizonylattipus="{$_role.bizonylattipus|default:''}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
        {/foreach}
    </select>
</div>