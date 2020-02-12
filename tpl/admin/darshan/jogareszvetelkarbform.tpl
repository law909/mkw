<div id="mattkarb-header">
	<h3>{at('Óra látogatás')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
                <td class="mattable-important"><label for="UresTeremEdit" class="mattable-important">{at('Üres terem')}:</label></td>
                <td><input id="UresTeremEdit" name="uresterem" type="checkbox"></td>
			</tr>
            <tr>
                <td class="mattable-important"><label for="PartnerEdit">{at('Résztvevő')}:</label></td>
                <td colspan="3">
                    <select id="PartnerEdit" name="partner" class="js-partneredit mattable-important">
                        <option value="">{at('válassz')}</option>
                        <option value="-1">{at('Új felvitel')}</option>
                        {foreach $egyed.partnerlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="PartnervezeteknevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                <td><input id="PartnervezeteknevEdit" name="partnervezeteknev"></td>
                <td><label for="PartnerkeresztnevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                <td><input id="PartnerkeresztnevEdit" name="partnerkeresztnev"></td>
            </tr>
            <tr>
                <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                <td colspan="7">
                    <input id="PartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
                    <input id="PartnervarosEdit" name="partnervaros" size="20" maxlength="40">
                    <input id="PartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
                </td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="PartneremailEdit" class="mattable-important" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                <td><input id="PartneremailEdit" name="partneremail"></td>
                <td><label for="PartnertelefonEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                <td><input id="PartnertelefonEdit" name="partnertelefon"></td>
            </tr>
            <tr>
                <td><label for="TermekEdit" class="mattable-important" title="{at('Milyen bérlettel, órajeggyel vett részt?')}">{at('Bérlet, órajegy')}:</label></td>
                <td>
                    <select id="TermekEdit" name="termek" class="js-termekedit mattable-important" data-id="{$egyed.id}">
                        <option value="">{at('válassz')}</option>
                        {foreach $egyed.termeklist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="ArEdit" class="mattable-important">{at('Ár')}:</label></td>
                <td><input id="ArEdit" name="ar" type="number" step="any" class="mattable-important" value="{$egyed.bruttoegysar}"></td>
            </tr>
            <tr>
                <td><label for="JRFizmodEdit_{$egyed.id}" class="mattable-important" title="{at('Hogyan fizetett?')}">{at('Fizetési mód')}:</label></td>
                <td>
                    <select id="JRFizmodEdit_{$egyed.id}" name="fizmod" class="mattable-important">
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
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>