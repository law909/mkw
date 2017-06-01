<select id="{$tagid}" name="{$variable}">
    <option value="">{at('válasszon')}</option>
    {foreach $terminallist as $_mk}
        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
    {/foreach}
</select>