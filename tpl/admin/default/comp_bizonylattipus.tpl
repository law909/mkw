<div class="bizonylattipuslist">
    {foreach $bizonylattipuslist as $bt}
        <div>
            <input id="bizonylattipuscb{$bt.id}" type="checkbox" name="bizonylattipus[]" value="{$bt.id}"{if (!empty($bizonylattipuschecked[$bt.id]))} checked="checked"{/if}>
            <label for="bizonylattipuscb{$bt.id}">{$bt.caption}</label>
        </div>
    {/foreach}
</div>