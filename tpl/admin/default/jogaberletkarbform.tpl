<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
	<h3>{at('Jóga bérlet')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Partner')}:</label></td>
                {if ($setup.partnerautocomplete)}
                    <td>
                        <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" value="{$egyed.partnernev}" size=90 autofocus>
                        <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                        <input class="js-ujpartnercb" type="checkbox">Új</input>
                    </td>
                {else}
                    <td><select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required" autofocus>
                            <option value="">{at('válasszon')}</option>
                            <option value="-1">{at('Új felvitel')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                {/if}
			</tr>
            <tr>
                <td><label for="TermekEdit" class="mattable-important">{at('Bérlet')}:</label></td>
                <td>
                    <select id="TermekEdit" name="termek" class="mattable-important" required="required">
                        <option value="">{at('válassz')}</option>
                        {foreach $termeklist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
			<tr>
				<td><label for="VasarlasDatumEdit">{at('Vásárlás dátuma')}:</label></td>
                <td><input id="VasarlasDatumEdit" name="vasarlasnapja" type="text" size="12" data-datum="{$egyed.vasarlasnapja}" class="mattable-important"></td>
			</tr>
			<tr>
				<td><label for="ElfogyottAlkalomEdit">{at('Elfogyott alkalom')}:</label></td>
				<td><input id="ElfogyottAlkalomEdit" name="elfogyottalkalom" type="text" value="{$egyed.elfogyottalkalom}"></td>
			</tr>
            <tr>
                <td><label for="OElfogyottAlkalomEdit">{at('Offline elfogyott alkalom')}:</label></td>
                <td><input id="OElfogyottAlkalomEdit" name="offlineelfogyottalkalom" type="text" value="{$egyed.offlineelfogyottalkalom}"></td>
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