<table id="naplotable_{$naplo.id}" data-oper="{$naplo.oper}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <tr>
            <input type="hidden" name="naploid[]" value="{$naplo.id}">
            <input type="hidden" name="naplooper_{$naplo.id}" value="{$naplo.oper}">
            <td>
                <table>
                    <tbody>
                        {if ($naplo.created)}
                        <tr>
                            <td><label>{at('Dátum')}:</label></td>
                            <td>{$naplo.created}</td>
                            <td><a class="js-naplodelbutton" href="#" data-id="{$naplo.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
                        </tr>
                        {/if}
                        <tr>
                            <td><label for="NaploEsemenyidoEdit_{$naplo.id}">{at('Esemény ideje')}:</label></td>
                            <td><input id="NaploEsemenyidoEdit_{$naplo.id}" name="naploesemenyido_{$naplo.id}" type="datetime-local" value="{$naplo.esemenyido}"></td>
                            {if (!$naplo.created)}
                            <td><a class="js-naplodelbutton" href="#" data-id="{$naplo.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
                            {/if}
                        </tr>
                        <tr>
                            <td><label>{at('Irány')}:</label></td>
                            <td>
                                <input id="NaploIranyBeEdit_{$naplo.id}" type="radio" name="naploirany_{$naplo.id}" value="1"{if ($naplo.irany >= 0)} checked="checked"{/if}><label for="NaploIranyBeEdit_{$naplo.id}">{at('Bejövő')}</label>
                                <input id="NaploIranyKiEdit_{$naplo.id}" type="radio" name="naploirany_{$naplo.id}" value="-1"{if ($naplo.irany < 0)} checked="checked"{/if}><label for="NaploIranyKiEdit_{$naplo.id}">{at('Kimenő')}</label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="NaploKuldoEdit_{$naplo.id}">{at('Küldő')}:</label></td>
                            <td><input id="NaploKuldoEdit_{$naplo.id}" name="naplokuldo_{$naplo.id}" type="text" size="70" maxlength="255" value="{$naplo.kuldo}"></td>
                        </tr>
                        <tr>
                            <td><label for="NaploFogadoEdit_{$naplo.id}">{at('Fogadó')}:</label></td>
                            <td><input id="NaploFogadoEdit_{$naplo.id}" name="naplofogado_{$naplo.id}" type="text" size="70" maxlength="255" value="{$naplo.fogado}"></td>
                        </tr>
                        <tr>
                            <td><label for="NaploSzovegEdit_{$naplo.id}">{at('Szöveg')}:</label></td>
                            <td><textarea id="NaploSzovegEdit_{$naplo.id}" name="naploszoveg_{$naplo.id}" rows="4" cols="70">{$naplo.szoveg}</textarea></td>
                        </tr>
                        <tr>
                            <td><label for="NaploMegjegyzesEdit_{$naplo.id}">{at('Megjegyzés')}:</label></td>
                            <td><textarea id="NaploMegjegyzesEdit_{$naplo.id}" name="naplomegjegyzes_{$naplo.id}" rows="3" cols="70">{$naplo.megjegyzes}</textarea></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
{if ($naplo.oper=='add')}
    <a class="js-naplonewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
