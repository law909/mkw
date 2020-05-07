<table id="jogareszveteltable_{$egyed.id}" data-id="{$egyed.id}" class="js-reszveteltable ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <tr>
            <input type="hidden" name="jrid[]" value="{$egyed.id}">
            <input type="hidden" name="jroper_{$egyed.id}" value="{$egyed.oper}">
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td class="js-counter{$egyed.id}"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="JRUresTeremEdit_{$egyed.id}" class="mattable-important">{at('Üres terem')}:</label></td>
                        <td><input id="JRUresTeremEdit_{$egyed.id}" name="uresterem_{$egyed.id}" type="checkbox"></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="JRPartnerEdit_{$egyed.id}">{at('Résztvevő')}:</label></td>
                        <td colspan="3">
                            <select id="JRPartnerEdit_{$egyed.id}" name="partner_{$egyed.id}" data-id="{$egyed.id}" class="js-jrpartneredit mattable-important">
                                <option value="">{at('válassz')}</option>
                                <option value="-1">{at('Új felvitel')}</option>
                                {foreach $egyed.partnerlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="JRPartnervezeteknevEdit_{$egyed.id}" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                        <td><input id="JRPartnervezeteknevEdit_{$egyed.id}" name="partnervezeteknev_{$egyed.id}"></td>
                        <td><label for="JRPartnerkeresztnevEdit_{$egyed.id}" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                        <td><input id="JRPartnerkeresztnevEdit_{$egyed.id}" name="partnerkeresztnev_{$egyed.id}"></td>
                    </tr>
                    <tr>
                        <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                        <td colspan="7">
                            <input id="JRPartnerirszamEdit_{$egyed.id}" name="partnerirszam_{$egyed.id}" size="6" maxlength="10">
                            <input id="JRPartnervarosEdit_{$egyed.id}" name="partnervaros_{$egyed.id}" size="20" maxlength="40">
                            <input id="JRPartnerutcaEdit_{$egyed.id}" name="partnerutca_{$egyed.id}" size="40" maxlength="60">
                        </td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="JRPartneremailEdit_{$egyed.id}" class="mattable-important" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                        <td><input id="JRPartneremailEdit_{$egyed.id}" name="partneremail_{$egyed.id}"></td>
                        <td><label for="JRPartnertelefonEdit_{$egyed.id}" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                        <td><input id="JRPartnertelefonEdit_{$egyed.id}" name="partnertelefon_{$egyed.id}"></td>
                    </tr>
                    <tr>
                        <td><label for="JRBerletEdit_{$egyed.id}">{at('Bérlet')}:</label></td>
                        <td>
                            <select id="JRBerletEdit_{$egyed.id}" name="jogaberlet_{$egyed.id}" class="js-jrberletedit mattable-important" data-id="{$egyed.id}">
                                <option value="">{at('válassz')}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="JRTermekEdit_{$egyed.id}" class="mattable-important" title="{at('Milyen bérlettel, órajeggyel vett részt?')}">{at('Bérlet, órajegy')}:</label></td>
                        <td>
                            <select id="JRTermekEdit_{$egyed.id}" name="termek_{$egyed.id}" class="js-jrtermekedit mattable-important" data-id="{$egyed.id}">
                                <option value="">{at('válassz')}</option>
                                {foreach $egyed.termeklist as $_mk}
                                    <option value="{$_mk.id}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="JRArEdit_{$egyed.id}" class="mattable-important">{at('Ár')}:</label></td>
                        <td><input id="JRArEdit_{$egyed.id}" name="ar_{$egyed.id}" type="number" step="any" class="mattable-important"></td>
                    </tr>
                    <tr>
                        <td><label for="JRFizmodEdit_{$egyed.id}" class="mattable-important" title="{at('Hogyan fizetett?')}">{at('Fizetési mód')}:</label></td>
                        <td>
                            <select id="JRFizmodEdit_{$egyed.id}" name="fizmod_{$egyed.id}" class="mattable-important">
                                <option value="">{at('válassz')}</option>
                                {foreach $egyed.fizmodlist as $_mk}
                                    <option value="{$_mk.id}"
                                            data-tipus="{if ($_mk.bank)}B{else}P{/if}"
                                            data-szepkartya="{$_mk.szepkartya}"
                                            data-sportkartya="{$_mk.sportkartya}"
                                            data-aycm="{$_mk.aycm}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class="js-jrreszveteldelbutton" href="#" title="{at('Töröl')}" data-id="{$egyed.id}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>