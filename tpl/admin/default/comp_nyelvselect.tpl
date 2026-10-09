{capture "compnyelvselect"}
    <select id="NyelvEdit" name="nyelv" class="mattable-important">
        <option value="">{at('mindegy')}</option>
        {foreach $nyelvlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Nyelv" for="NyelvEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compnyelvselect nofilter}{/mezo}
{else}
<div>
    <label for="NyelvEdit">{at('Nyelv')}:</label>
{$smarty.capture.compnyelvselect nofilter}
</div>
{/if}
