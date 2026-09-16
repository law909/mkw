<option value="">{at('válasszon')}</option>
{foreach $cimkekat as $_cimkekat}
    <optgroup label="{$_cimkekat.caption}">
        {foreach $_cimkekat.cimkek as $_cimke}
            <option value="{$_cimke.id}">{$_cimke.caption}</option>
        {/foreach}
    </optgroup>
{/foreach}
