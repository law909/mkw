{capture "compbizonylatstatuszcsoport"}
    <select id="bizonylatstatuszcsoport" name="bizonylatstatuszcsoport">
        <option value="">{at('Mindegy')}</option>
        {foreach $bizonylatstatuszcsoportlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Státusz csoport" for="bizonylatstatuszcsoport" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compbizonylatstatuszcsoport nofilter}{/mezo}
{else}
<div>
    <label for="bizonylatstatuszcsoport">{at('Státusz csoport')}:</label>
{$smarty.capture.compbizonylatstatuszcsoport nofilter}
</div>
{/if}
