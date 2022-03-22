<table id="pagetranslationtable_{$translation.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="pagetranslationid[]" type="hidden" value="{$translation.id}">
        <input name="pagetranslationoper_{$translation.id}" type="hidden" value="{$translation.oper}">
        <tr>
            <td>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="TrLocaleEdit{$translation.id}">{at('Nyelv')}:</label></td>
                            <td><select id="TrLocaleEdit{$translation.id}" name="pagetranslationlocale_{$translation.id}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $localelist as $_locale}
                                        <option value="{$_locale}"{if ($translation.locale == $_locale)} selected="selected"{/if}>{$_locale}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="TrFieldEdit{$translation.id}">{at('Mező')}:</label></td>
                            <td><select id="TrFieldEdit{$translation.id}" class="js-fieldselect" name="pagetranslationfield_{$translation.id}" data-id="{$translation.id}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $translation.fieldlist as $_field}
                                        <option value="{$_field.id}"{if ($_field.selected)} selected="selected"{/if}>{$_field.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="TrContentEdit{$translation.id}">{at('Érték')}:</label></td>
                            <td>
                                <textarea id="TrContentEdit{$translation.id}" class="js-contenteditor_{$translation.id}{if ($translation.type == 2)} js-ckeditor{/if}" name="pagetranslationcontent_{$translation.id}">{$translation.content}</textarea>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
            <td>
                <a class="js-pagetranslationdelbutton" href="#" data-id="{$translation.id}" data-egyedid="{$egyed.id}"{if ($translation.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($translation.oper=='add')}
    <a class="js-pagetranslationnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}