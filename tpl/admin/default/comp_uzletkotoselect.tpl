{capture "compuzletkotoselect"}
    <select id="UzletkotoEdit" name="uzletkoto" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $uklist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Üzletkötő" for="UzletkotoEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compuzletkotoselect nofilter}{/mezo}
{else}
<div>
    <label for="UzletkotoEdit">{at('Üzletkötő')}:</label>
{$smarty.capture.compuzletkotoselect nofilter}
</div>
{/if}
