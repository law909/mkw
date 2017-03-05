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
                            <td><label for="TrLocaleEdit{$translation.id}">{at('Mező')}:</label></td>
                            <td><select id="TrLocaleEdit{$translation.id}" name="translationfield_{$translation.id}" value="{$translation.id}" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $translation.fieldlist as $_field}
                                        <option value="{$_field.id}"{if ($_field.selected)} selected="selected"{/if}>{$_field.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="TrContentEdit{$translation.id}">{at('Érték')}:</label></td>
                            <td>
                                {if ($translation.type == 1)}
                                    <input id="TrContentEdit{$translation.id}" type="text" size="83" name="translationcontent_{$translation.id}" value="{$translation.content}">
                                {elseif ($translation.type == 2)}
                                    <textarea id="TrContentEdit{$translation.id}" name="translationcontent_{$translation.id}">{$translation.content}</textarea>
                                {elseif ($translation.type == 3)}
                                    <textarea id="TrContentEdit{$translation.id}" name="translationcontent_{$translation.id}">{$translation.content}</textarea>
                                {else}
                                    <input id="TrContentEdit{$translation.id}" type="text" size="83" name="translationcontent_{$translation.id}" value="{$translation.content}">
                                {/if}
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
            <td>
                <a class="js-translationdelbutton" href="#" data-id="{$translation.id}" data-egyedid="{$egyed.id}"{if ($translation.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($translation.oper=='add')}
    <a class="js-translationnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}