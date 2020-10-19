<select name="foxpostcsoport">
    <option value="">válasszon</option>
{foreach $foxpostcsoportlist as $f}
    <option value="{$f.id}"{if ($f.selected)} checked{/if}>{$f.caption}</option>
{/foreach}
</select>
