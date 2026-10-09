{capture "compdolgozoselect"}
    <select id="DolgozoEdit" name="dolgozo" class="mattable-important">
        <option value="">{at('válasszon')}</option>
        {foreach $dolgozolist as $_mk}
            <option value="{$_mk.id}" data-munkakor="{$_mk.munkakor|default}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Dolgozó" for="DolgozoEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compdolgozoselect nofilter}{/mezo}
{else}
<div>
    <label for="DolgozoEdit">{at('Dolgozó')}:</label>
{$smarty.capture.compdolgozoselect nofilter}
</div>
{/if}
