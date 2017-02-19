<table id="keptable_{$kep.id}" data-oper="{$kep.oper}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <tr>
            <input type="hidden" name="kepid[]" value="{$kep.id}">
            <input type="hidden" name="kepoper_{$kep.id}" value="{$kep.oper}">
            <td>{if ($kep.url)}<a class="js-toflyout" href="{$mainurl}{$kep.url}" target="_blank"><img src="{$mainurl}{$kep.urlsmall}" alt="{$kep.url}" title="{$kep.url}"/></a>{/if}</td>
            <td>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="KepUrlEdit_{$kep.id}">{at('Kép')}:</label></td>
                            <td><input id="KepUrlEdit_{$kep.id}" name="kepurl_{$kep.id}" type="text" size="70" maxlength="255" value="{$kep.url}"></td>
                            <td><a class="js-kepbrowsebutton" href="#" data-id="{$kep.id}" title="{at('Browse')}">{at('...')}</a></td>
                        </tr>
                        <tr>
                            <td><label for="KepLeirasEdit_{$kep.id}">{at('Kép leírása')}:</label></td>
                            <td><input id="KepLeirasEdit_{$kep.id}" name="kepleiras_{$kep.id}" type="text" size="70" value="{$kep.leiras}"></td>
                            <td><a class="js-kepdelbutton" href="#" data-id="{$kep.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
                        </tr>
                        <tr>
                            <td><label for="KepRejtettEdit_{$kep.id}">{at('Rejtett')}:</label></td>
                            <td><input id="KepRejtettEdit_{$kep.id}" name="keprejtett_{$kep.id}" type="checkbox"{if ($kep.rejtett)} checked="checked"{/if}></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
{if ($kep.oper=='add')}
    <a class="js-kepnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}