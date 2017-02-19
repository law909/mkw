<table id="translationtable_{$translation.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="translationid[]" type="hidden" value="{$translation.id}">
        <input name="translationoper_{$translation.id}" type="hidden" value="{$translation.oper}">
        <tr>
            <td>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="TrLocaleEdit{$translation.id}">{at('Nyelv')}:</label></td>
                            <td><select id="TrLocaleEdit{$translation.id}" name="translationlocale_{$translation.id}" value="{$translation.id}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $localelist as $_locale}
                                        <option value="{$_locale}"{if ($translation.locale == $_locale)} selected="selected"{/if}>{$_locale}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="TrNevEdit{$translation.id}">{at('Név')}:</label></td>
                            <td>
                                <input id="TrNevEdit{$translation.id}" type="text" size="83" name="translationnev_{$translation.id}" value="{$translation.nev}">
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
            <td>
                <a class="js-translationdelbutton" href="#" data-id="{$translation.id}" data-termekid="{$termek.id}"{if ($translation.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($translation.oper=='add')}
    <a class="js-translationnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}