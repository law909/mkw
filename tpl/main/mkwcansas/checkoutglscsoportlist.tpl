<select name="glscsoport">
    <option value="">válasszon</option>
{foreach $glscsoportlist as $f}
    <option value="{$f.id}"{if ($f.selected)} checked{/if}>{$f.caption}</option>
{/foreach}
</select>
