<div>
    {foreach $bizonylattipuslist as $bt}
        <div>
            <input type="checkbox" name="bizonylattipus[]" value="{$bt.id}">
            <label>{$bt.caption}</label>
        </div>
    {/foreach}
</div>