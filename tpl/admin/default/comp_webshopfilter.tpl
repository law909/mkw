{capture "compwebshopfilter"}
    <select id="WebshopnumEdit" name="webshopnum">
        {foreach $webshopfilterlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Webshop" for="WebshopnumEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compwebshopfilter nofilter}{/mezo}
{else}
<div>
    <label for="WebshopnumEdit">{at('Webshop')}:</label>
{$smarty.capture.compwebshopfilter nofilter}
</div>
{/if}
