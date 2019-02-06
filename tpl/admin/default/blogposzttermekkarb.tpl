<table id="termektable_{$termek.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <tr>
        <td><label for="AzonEdit{$termek.id}">{at('Termék')}:</label></td>
        <td>
            {if ($setup.termekautocomplete)}
                {if ($termek.oper === 'add')}
                    <input id="TermekSelect{$termek.id}" type="text" name="termeknev_{$termek.id}" class="js-termekselect termekselect mattable-important" value="{$termek.nev}">
                    <input class="js-termekid" name="termekid[]" type="hidden" value="{$termek.id}">
                {else}
                    {$termek.nev}
                {/if}
            {else}
                <select class="js-termekselectreal js-termekid" name="termek_{$tetel.id}">
                    <option value="">{t('válasszon')}</option>
                    {foreach $tetel.termeklist as $_termekadat}
                        <option value="{$_termekadat.id}"{if ($_termekadat.id == $tetel.termek)} selected="selected"{/if}>{$_termekadat.caption}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
        <td>
            <a class="js-termekdelbutton" href="#" data-tid="{$termek.id}" data-bid="{$egyed.id}"{if ($termek.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($termek.oper=='add')}
    <a class="js-termeknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}