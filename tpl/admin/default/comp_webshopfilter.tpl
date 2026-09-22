<div>
    <label for="WebshopnumEdit">{at('Webshop')}:</label>
    <select id="WebshopnumEdit" name="webshopnum">
        {foreach $webshopfilterlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>
