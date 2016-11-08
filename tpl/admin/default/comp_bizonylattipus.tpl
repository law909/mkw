<div>
    {foreach $bizonylattipuslist as $bt}
        <div>
            <input id="bizonylattipuscb{$bt.id}" type="checkbox" name="bizonylattipus[]" value="{$bt.id}">
            <label for="bizonylattipuscb{$bt.id}">{$bt.caption}</label>
        </div>
    {/foreach}
</div>