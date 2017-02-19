<div>
    <label for="BankszamlaEdit">{at('Bankszámla')}:</label>
    <select id="BankszamlaEdit" name="bankszamla" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $bankszamlalist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>