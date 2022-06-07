<table id="pagetranslationtable_{$translation.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="pagetranslationid_{$translation.pageid}[]" type="hidden" value="{$translation.id}">
        <input name="pagetranslationoper_{$translation.id}_{$translation.pageid}" type="hidden" value="{$translation.oper}">
        <tr>
            <td>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="PageTrLocaleEdit{$translation.id}">{at('Nyelv')}:</label></td>
                            <td><select id="PageTrLocaleEdit{$translation.id}" name="pagetranslationlocale_{$translation.id}_{$translation.pageid}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $localelist as $_locale}
                                        <option value="{$_locale}"{if ($translation.locale == $_locale)} selected="selected"{/if}>{$_locale}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="PageTrFieldEdit{$translation.id}">{at('Mező')}:</label></td>
                            <td><select id="PageTrFieldEdit{$translation.id}" class="js-fieldselect" name="pagetranslationfield_{$translation.id}_{$translation.pageid}" data-id="{$translation.id}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $translation.fieldlist as $_field}
                                        <option value="{$_field.id}"{if ($_field.selected)} selected="selected"{/if}>{$_field.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="PageTrContentEdit{$translation.id}">{at('Érték')}:</label></td>
                            <td>
                                <textarea id="PageTrContentEdit{$translation.id}" class="js-contenteditor_{$translation.id}{if ($translation.type == 2)} js-ckeditor{/if}" name="pagetranslationcontent_{$translation.id}_{$translation.pageid}">{$translation.content}</textarea>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
            <td>
                <a class="js-pagetranslationdelbutton" href="#" data-id="{$translation.id}" data-egyedid="{$translation.pageid}"{if ($translation.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($translation.oper=='add')}
    <a class="js-pagetranslationnewbutton" href="#" title="{at('Új fordítás')}" data-pageid="{$translation.pageid}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}