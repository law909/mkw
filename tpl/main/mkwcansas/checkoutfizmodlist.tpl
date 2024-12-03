{foreach $fizmodlist as $fizmod}
    <label class="radio">
        <input type="radio" name="fizetesimod" class="js-chkrefresh" value="{$fizmod.id}"{if ($fizmod.selected)} checked{/if}
               data-caption="{$fizmod.caption}"
               data-biztonsagikerdeskell="{$fizmod.biztonsagikerdeskell}"
        >
        {$fizmod.caption}{if $fizmod.novelo} (díja: {number_format($fizmod.novelo,0,',',' ')} Ft){/if}
    </label>
    {if ($fizmod.leiras)}
        <div class="chk-courierdesc folyoszoveg">{$fizmod.leiras}</div>
    {/if}
{/foreach}
