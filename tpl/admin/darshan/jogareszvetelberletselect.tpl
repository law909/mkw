    <option value="">{at('válassz')}</option>
    {foreach $berletlist as $_mk}
        <option value="{$_mk.id}" data-termekid="{$_mk.termekid}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
    {/foreach}
