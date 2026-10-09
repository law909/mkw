{capture "compraktarselect"}
    <select id="RaktarEdit" name="raktar" class="mattable-important">
        <option value="">{at('mindegy')}</option>
        {foreach $raktarlist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Raktár" for="RaktarEdit" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compraktarselect nofilter}{/mezo}
{else}
<div>
    <label for="RaktarEdit">{at('Raktár')}:</label>
{$smarty.capture.compraktarselect nofilter}
</div>
{/if}
