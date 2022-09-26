<table id="valtozattable_{$valtozat.id}"  class="ui-widget ui-widget-content ui-corner-all mattable-repeatable valtozattable">
    <tbody>
        <input name="valtozatid[]" type="hidden" value="{$valtozat.id}">
        <input name="valtozatoper_{$valtozat.id}" type="hidden" value="{$valtozat.oper}">
        <tr>
            <td class="mattable-cell">
                <label for="VElerhetoEdit{$valtozat.id}">{at('Elérhető')}:
                    <input id="VElerhetoEdit{$valtozat.id}" name="valtozatelerheto_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto)} checked="checked"{/if}>
                </label>
            </td>
            <td class="mattable-cell">
                <label for="VLathatoEdit{$valtozat.id}">{at('Látható')}:
                    <input id="VLathatoEdit{$valtozat.id}" name="valtozatlathato_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato)} checked="checked"{/if}>
                </label>
            </td>
            <td colspan="3" class="mattable-cell">
                <a class="js-valtozatdelbutton" href="#" data-id="{$valtozat.id}"{if ($valtozat.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
        {if ($setup.multishop)}
            <tr>
                <td class="mattable-cell">
                    <label for="VElerheto2Edit{$valtozat.id}">{at('Elérhető 2')}:
                        <input id="VElerheto2Edit{$valtozat.id}" name="valtozatelerheto2_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto2)} checked="checked"{/if}>
                    </label>
                </td>
                <td class="mattable-cell">
                    <label for="VLathato2Edit{$valtozat.id}">{at('Látható 2')}:
                        <input id="VLathato2Edit{$valtozat.id}" name="valtozatlathato2_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato2)} checked="checked"{/if}>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="mattable-cell">
                    <label for="VElerheto3Edit{$valtozat.id}">{at('Elérhető 3')}:
                        <input id="VElerheto3Edit{$valtozat.id}" name="valtozatelerheto3_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto3)} checked="checked"{/if}>
                    </label>
                </td>
                <td class="mattable-cell">
                    <label for="VLathato3Edit{$valtozat.id}">{at('Látható 3')}:
                        <input id="VLathato3Edit{$valtozat.id}" name="valtozatlathato3_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato3)} checked="checked"{/if}>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="mattable-cell">
                    <label for="VElerheto4Edit{$valtozat.id}">{at('Elérhető 4')}:
                        <input id="VElerheto4Edit{$valtozat.id}" name="valtozatelerheto4_{$valtozat.id}" type="checkbox"{if ($valtozat.elerheto4)} checked="checked"{/if}>
                    </label>
                </td>
                <td class="mattable-cell">
                    <label for="VLathato4Edit{$valtozat.id}">{at('Látható 4')}:
                        <input id="VLathato4Edit{$valtozat.id}" name="valtozatlathato4_{$valtozat.id}" type="checkbox"{if ($valtozat.lathato4)} checked="checked"{/if}>
                    </label>
                </td>
            </tr>

        {/if}
        <tr>
            <td class="mattable-cell">
                <label>{at('Készlet')}:</label>
            </td>
            <td class="mattable-cell">
                {$valtozat.keszlet}
            </td>
            <td class="mattable-cell"><label for="BeerkezesdatumEdit{$valtozat.id}">{at('Beérkezés')}:</label></td>
            <td class="mattable-cell"><input id="BeerkezesdatumEdit{$valtozat.id}" name="valtozatbeerkezesdatum_{$valtozat.id}" class="js-valtozatbeerkezesdatumedit" type="text" size="12" data-datum="{$valtozat.beerkezesdatumstr}"></td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <select name="valtozatadattipus1_{$valtozat.id}" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $valtozat.adattipus1lista as $at}
                        <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                    {/foreach}
                </select>
            </td>
            <td class="mattable-cell">
                <input name="valtozatertek1_{$valtozat.id}" type="text" value="{$valtozat.ertek1}" required="required">
            </td>
            <td class="mattable-cell">
                <label for="NettoEdit_{$valtozat.id}">{at('Nettó')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="js-valtozatnetto" id="NettoEdit_{$valtozat.id}" name="valtozatnetto_{$valtozat.id}" type="number" step="any" value="{$valtozat.netto}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <select name="valtozatadattipus2_{$valtozat.id}">
                    <option value="">{at('válasszon')}</option>
                    {foreach $valtozat.adattipus2lista as $at}
                        <option value="{$at.id}"{if ($at.selected)} selected="selected"{/if}>{$at.caption}</option>
                    {/foreach}
                </select>
            </td>
            <td class="mattable-cell">
                <input name="valtozatertek2_{$valtozat.id}" type="text" value="{$valtozat.ertek2}">
            </td>
            <td class="mattable-cell">
                <label for="BruttoEdit_{$valtozat.id}">{at('Bruttó')}:</label>
            </td>
            <td class="mattable-cell">
                <input class="js-valtozatbrutto" id="BruttoEdit_{$valtozat.id}" name="valtozatbrutto_{$valtozat.id}" type="number" step="any" value="{$valtozat.brutto}">
            </td>
        </tr>
        <tr>
            <td class="mattable-cell">
                <label for="CikkszamEdit_{$valtozat.id}">{at('Cikkszám')}:</label>
            </td>
            <td class="mattable-cell">
                <input id="CikkszamEdit_{$valtozat.id}" name="valtozatcikkszam_{$valtozat.id}" type="text" value="{$valtozat.cikkszam}">
            </td>
            <td class="mattable-cell">
                <label for="IdegenCikkszamEdit_{$valtozat.id}">{at('Szállítói cikkszám')}:</label>
            </td>
            <td class="mattable-cell">
                <input id="IdegenCikkszamEdit_{$valtozat.id}" name="valtozatidegencikkszam_{$valtozat.id}" type="text" value="{$valtozat.idegencikkszam}">
            </td>
        </tr>
        {if ($setup.vonalkod)}
            <tr>
                <td class="mattable-cell">
                    <label for="VonalkodEdit_{$valtozat.id}">{at('Vonalkód')}:</label>
                </td>
                <td class="mattable-cell">
                    <input id="VonalkodEdit_{$valtozat.id}" name="valtozatvonalkod_{$valtozat.id}" type="text" value="{$valtozat.vonalkod}">
                </td>
            </tr>
        {/if}
        <tr>
            <td>
                <label for="ValtozatTermekKepCB_{$valtozat.id}">{at('A kép a termék főképe')}:</label>
                <input id="ValtozatTermekKepCB__{$valtozat.id}" name="valtozattermekfokep_{$valtozat.id}" type="checkbox"{if ($valtozat.termekfokep)} checked="checked"{/if}>
            </td>
        </tr>
        <tr>
            <td><label for="ValtozatKepEdit_{$valtozat.id}">{at('Kép')}:</label></td>
            <td colspan="3">
                <ul id="ValtozatKepEdit_{$valtozat.id}" class="valtozatkepedit js-valtozatkepedit">
                    {foreach $valtozat.keplista as $kep}
                        <li data-value="{$kep.id}" data-valtozatid="{$valtozat.id}" class="ui-state-default{if ($valtozat.kepid==$kep.id)} ui-selected ui-state-highlight{/if}">
                            {if ($kep.url)}<img src="{$mainurl}{$kep.url}"/>{/if}
                        </li>
                    {/foreach}
                </ul>
                <input id="ValtozatKepId_{$valtozat.id}" name="valtozatkepid_{$valtozat.id}" type="hidden" value="{$valtozat.kepid}">
            </td>
        </tr>
    </tbody>
</table>
{if ($valtozat.oper=='add')}
    <a class="js-valtozatnewbutton" href="#" title="{at('Új')}" data-termekid="{$valtozat.termek.id}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}