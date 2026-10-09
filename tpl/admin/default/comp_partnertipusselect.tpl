{capture "comppartnertipusselect"}
    <select id="PartnertipusEdit" name="partnertipus">
        <option value="">{at('válasszon')}</option>
        {foreach $partnertipuslist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Partnertípus" for="PartnertipusEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.comppartnertipusselect nofilter}{/mezo}
{else}
<div>
    <label for="PartnertipusEdit">{at('Partnertípus')}:</label>
{$smarty.capture.comppartnertipusselect nofilter}
</div>
{/if}
