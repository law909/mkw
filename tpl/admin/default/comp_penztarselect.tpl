{capture "comppenztarselect"}
    <select id="PenztarEdit" name="penztar" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $penztarlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Pénztár" for="PenztarEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.comppenztarselect nofilter}{/mezo}
{else}
<div>
    <label for="PenztarEdit">{at('Pénztár')}:</label>
{$smarty.capture.comppenztarselect nofilter}
</div>
{/if}
