{capture "compbizonylatstatusz"}
    <select id="bizonylatstatusz" name="bizonylatstatusz">
        <option value="">{at('Mindegy')}</option>
        {foreach $bizonylatstatuszlist as $_role}
            <option value="{$_role.id}" data-bizonylattipus="{$_role.bizonylattipus|default:''}"{if ($_role.kozos|default)} data-kozos="1"{/if}{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
        {/foreach}
    </select>
{/capture}
{if ($mezo|default:false)}
    {mezo cimke="Státusz" for="bizonylatstatusz" ujsor=$ujsor|default:false szeles=$szeles|default:false}{$smarty.capture.compbizonylatstatusz nofilter}{/mezo}
{else}
<div>
    <label for="bizonylatstatusz">{at('Státusz')}:</label>
{$smarty.capture.compbizonylatstatusz nofilter}
</div>
{/if}
