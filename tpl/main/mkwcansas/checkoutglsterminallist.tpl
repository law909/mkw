<select name="glsterminal" class="js-chkrefresh">
    <option value="">válasszon</option>
{foreach $glsterminallist as $f}
    <option value="{$f.id}"{if ($f.selected)} checked{/if}>{$f.caption} - {$f.cim}</option>
{/foreach}
</select>
