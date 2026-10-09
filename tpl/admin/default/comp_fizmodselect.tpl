{capture "compfizmodselect"}
    <select id="FizmodEdit" name="fizmod" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $fizmodlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Fizetési mód" for="FizmodEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compfizmodselect nofilter}{/mezo}
{else}
<div>
    <label for="FizmodEdit">{at('Fizetési mód')}:</label>
{$smarty.capture.compfizmodselect nofilter}
</div>
{/if}
