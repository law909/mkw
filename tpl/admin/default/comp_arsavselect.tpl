{capture "comparsavselect"}
    <select id="ArsavEdit" name="arsav">
        <option value="">{at('válasszon')}</option>
        {foreach $arsavlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Ársáv" for="ArsavEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.comparsavselect nofilter}{/mezo}
{else}
<div>
    <label for="ArsavEdit">{at('Ársáv')}:</label>
{$smarty.capture.comparsavselect nofilter}
</div>
{/if}
