{capture "compbankszamlaselect"}
    <select id="BankszamlaEdit" name="bankszamla" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $bankszamlalist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Bankszámla" for="BankszamlaEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compbankszamlaselect nofilter}{/mezo}
{else}
<div>
    <label for="BankszamlaEdit">{at('Bankszámla')}:</label>
{$smarty.capture.compbankszamlaselect nofilter}
</div>
{/if}
