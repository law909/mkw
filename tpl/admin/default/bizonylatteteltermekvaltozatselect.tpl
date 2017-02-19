{if (count($tetel.valtozatlist))}
<select id="ValtozatSelect{$tetel.id}" name="tetelvaltozat_{$tetel.id}" class="js-tetelvaltozat" required>
	<option value="">{at('Válasszon')}</option>
    {if ($maintheme == 'mkwcansas')}
        {foreach $tetel.valtozatlist as $_v}
            <option value="{$_v.id}"{if ($_v.selected)} selected{/if}{if (!$_v.elerheto)} class="nemelerhetovaltozat"{/if}>{$_v.caption}</option>
        {/foreach}
    {else}
        {foreach $tetel.valtozatlist as $_v}
            <option value="{$_v.id}"{if ($_v.selected)} selected{/if}{if (!$_v.elerheto || ($_v.keszlet <= 0))} class="nemelerhetovaltozat"{/if}>{$_v.caption} ({$_v.keszlet})</option>
        {/foreach}
    {/if}
</select>
{/if}