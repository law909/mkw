<table id="translationtable_{$locale}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="translationid[]" type="hidden" value="{$locale}">
    <input name="translationoper_{$locale}" type="hidden" value="{$translation.oper}">
    <tr>
        <td>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <label for="TrLocaleEdit{$locale}">{t('Nyelv')}:</label>
                        </td>
                        <td>
                            <select id="TrLocaleEdit{$locale}" name="translationlocale_{$locale}" value="{$locale}" required="required">
                                <option value="">{t('válasszon')}</option>
                                {foreach $localelist as $_locale}
                                    <option value="{$_locale}"{if ($locale == $_locale)} selected="selected"{/if}>{$_locale}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <label for="TrNevEdit{$locale}">{t('Név')}:</label>
                        </td>
                        <td>
                            <input id="TrNevEdit{$locale}" type="text" size="83" name="translationnev_{$locale}" value="{$translation.nev}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="TrOldalcimEdit{$locale}">{t('Oldalcím')}:</label>
                        </td>
                        <td>
                            <input id="TrOldalcimEdit{$locale}" type="text" name="translationoldalcim_{$locale}" value="{$translation.oldalcim}">
                        </td>
                    </tr>
                <tbody>
            </table>
        </td>
        <td>
            <a class="js-translationdelbutton" href="#" data-id="{$locale}" data-termekid="{$termek.id}"{if ($translation.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($translation.oper=='add')}
    <a class="js-arnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}