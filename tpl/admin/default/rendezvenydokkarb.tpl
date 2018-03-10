<table id="doktable_{$dok.id}" data-oper="{$dok.oper}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <tr>
            <input type="hidden" name="dokid[]" value="{$dok.id}">
            <input type="hidden" name="dokoper_{$dok.id}" value="{$dok.oper}">
            <td>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="DokUrlEdit_{$dok.id}">{at('Web cím')}:</label></td>
                            <td><input id="DokUrlEdit_{$dok.id}" name="dokurl_{$dok.id}" type="text" size="70" maxlength="255" value="{$dok.url}"></td>
                            <td>{if ($dok.oper=='edit' && $dok.url)}<a class="js-dokopenbutton" href="{$dok.url}" title="{at('Megnyit')}" target="_blank">{at('Megnyit')}</a>{/if}</td>
                        </tr>
                        <tr>
                            <td><label for="DokPathEdit_{$dok.id}">{at('Dokumentum')}:</label></td>
                            <td><input id="DokPathEdit_{$dok.id}" name="dokpath_{$dok.id}" type="text" size="70" maxlength="255" value="{$dok.path}"></td>
                            <td><a class="js-dokbrowsebutton" href="#" data-id="{$dok.id}" title="{at('Browse')}">{at('...')}</a></td>
                        </tr>
                        <tr>
                            <td><label for="DokLeirasEdit_{$dok.id}">{at('Dokumentum leírása')}:</label></td>
                            <td><input id="DokLeirasEdit_{$dok.id}" name="dokleiras_{$dok.id}" type="text" size="70" value="{$dok.leiras}"></td>
                            <td><a class="js-dokdelbutton" href="#" data-id="{$dok.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
{if ($dok.oper=='add')}
    <a class="js-doknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}