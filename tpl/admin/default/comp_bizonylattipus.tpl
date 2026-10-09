{if ($mezo|default:false)}
    {mezo cimke="Bizonylattípus" szeles=true}
        <div class="mattkarb-pipak">
            {foreach $bizonylattipuslist as $bt}
                <label><input id="bizonylattipuscb{$bt.id}" type="checkbox" name="bizonylattipus[]" value="{$bt.id}"{if (!empty($bizonylattipuschecked[$bt.id]))} checked="checked"{/if}>{$bt.caption}</label>
            {/foreach}
        </div>
        {if ($hint|default:'')}<div class="mattkarb-megjegyzes">{$hint}</div>{/if}
    {/mezo}
{else}
<div class="bizonylattipuslist">
    {foreach $bizonylattipuslist as $bt}
        <div>
            <input id="bizonylattipuscb{$bt.id}" type="checkbox" name="bizonylattipus[]" value="{$bt.id}"{if (!empty($bizonylattipuschecked[$bt.id]))} checked="checked"{/if}>
            <label for="bizonylattipuscb{$bt.id}">{$bt.caption}</label>
        </div>
    {/foreach}
</div>
{/if}
