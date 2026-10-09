{capture "compgyartoselect"}
    <select id="GyartoEdit" name="gyarto">
        <option value="">{at('válasszon')}</option>
        {foreach $gyartolist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Gyártó" for="GyartoEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compgyartoselect nofilter}{/mezo}
{else}
<div>
    <label for="GyartoEdit">{at('Gyártó')}:</label>
{$smarty.capture.compgyartoselect nofilter}
</div>
{/if}
