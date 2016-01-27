<div>
    <label for="BankszamlaEdit">{t('Bankszámla')}:</label>
    <select id="BankszamlaEdit" name="bankszamla" class="mattable-important">
        <option value="">{t('válasszon')}</option>
        {foreach $bankszamlalist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
</div>