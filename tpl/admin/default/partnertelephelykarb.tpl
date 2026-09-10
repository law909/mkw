<table id="telephelytable_{$tp.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="telephelyid[]" type="hidden" value="{$tp.id}">
    <input name="telephelyoper_{$tp.id}" type="hidden" value="{$tp.oper}">
    <input name="telephelymigrid_{$tp.id}" type="hidden" value="{$tp.migrid}">
    <tr>
        <td><label for="TelephelyNevEdit{$tp.id}">{at('Név')}:</label></td>
        <td><input id="TelephelyNevEdit{$tp.id}" type="text" name="telephelynev_{$tp.id}" size="40" maxlength="255" value="{$tp.nev}"></td>
        <td><label for="TelephelyIrszamEdit{$tp.id}">{at('Irányítószám')}:</label></td>
        <td><input id="TelephelyIrszamEdit{$tp.id}" type="text" name="telephelyirszam_{$tp.id}" size="8" maxlength="10" value="{$tp.irszam}"></td>
        <td><label for="TelephelyVarosEdit{$tp.id}">{at('Város')}:</label></td>
        <td><input id="TelephelyVarosEdit{$tp.id}" type="text" name="telephelyvaros_{$tp.id}" size="25" maxlength="40" value="{$tp.varos}"></td>
    </tr>
    <tr>
        <td><label for="TelephelyUtcaEdit{$tp.id}">{at('Utca')}:</label></td>
        <td><input id="TelephelyUtcaEdit{$tp.id}" type="text" name="telephelyutca_{$tp.id}" size="40" maxlength="60" value="{$tp.utca}"></td>
        <td><label for="TelephelyOrszagEdit{$tp.id}">{at('Ország')}:</label></td>
        <td colspan="2"><select id="TelephelyOrszagEdit{$tp.id}" name="telephelyorszag_{$tp.id}">
                <option value="">{at('válasszon')}</option>
                {foreach $tp.orszaglist as $_orszag}
                    <option value="{$_orszag.id}"{if ($_orszag.selected)} selected="selected"{/if}>{$_orszag.caption}</option>
                {/foreach}
            </select>
        </td>
        <td>
            <a class="js-telephelydelbutton" href="#" data-id="{$tp.id}"{if ($tp.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($tp.oper=='add')}
    <a class="js-telephelynewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
