{capture "comppartnerselect"}
    {if ($setup.partnerautocomplete)}
        <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" size=90>
        <input class="js-partnerid" name="partner" type="hidden">
    {else}
        <select id="PartnerEdit" name="partner" class="js-partnerid mattable-important">
            <option value="">{at('válasszon')}</option>
            {foreach $partnerlist as $_mk}
                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
            {/foreach}
        </select>
    {/if}
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Partner" for="PartnerEdit" szeles=true}{$smarty.capture.comppartnerselect nofilter}{/mezo}
{else}
<div>
    <label for="PartnerEdit">{at('Partner')}:</label>
{$smarty.capture.comppartnerselect nofilter}
</div>
{/if}
