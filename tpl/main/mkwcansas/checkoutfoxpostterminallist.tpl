<select name="foxpostterminal" class="js-chkrefresh">
    <option value="">válasszon</option>
{foreach $foxpostterminallist as $f}
    <option value="{$f.id}"{if ($f.selected)} selected{/if}>{$f.caption} - {$f.cim}</option>
{/foreach}
</select>
