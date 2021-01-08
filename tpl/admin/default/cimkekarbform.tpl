<div id="mattkarb-header">
	<h3>{$headcaption}</h3>
	<h4>{$cimke.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$cimke.id}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
			<li><a href="#WebTab">{at('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$cimke.nev}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="CimkecsoportEdit">{at('Címkecsoport')}:</label></td>
				<td><select id="CimkecsoportEdit" name="cimkecsoport" required>
					<option value="">{at("válasszon")}</option>
					{foreach $cimkecsoportlist as $_ccs}
						<option value="{$_ccs.id}"{if ($_ccs.selected)} selected="selected"{/if}>{$_ccs.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$cimke.sorrend}"></td>
			</tr>
            {if ($cimketipus === 'termek')}
                <tr>
                    <td><label for="GyartoEdit">{at('Gyártó')}:</label></td>
                    <td colspan="3"><select id="GyartoEdit" name="gyarto">
                            <option value="">{at('válasszon')}</option>
                            {foreach $gyartolist as $_gyarto}
                                <option
                                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="SzinkodEdit">{at('Színkód')}:</label></td>
                    <td><input id="SzinkodEdit" name="szinkod" type="text" maxlength="7" value="{$cimke.szinkod}"></td>
                </tr>
                <tr>
                {include 'cimkeimagekarb.tpl'}
                </tr>
            {/if}
			</tbody></table>
		</div>
		<div id="WebTab" class="mattkarb-page">
			<input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox"{if ($cimke.menu1lathato)}checked="checked"{/if}>{at('Menü 1')}
			<input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox"{if ($cimke.menu2lathato)}checked="checked"{/if}>{at('Menü 2')}
			<input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox"{if ($cimke.menu3lathato)}checked="checked"{/if}>{at('Menü 3')}
			<input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox"{if ($cimke.menu4lathato)}checked="checked"{/if}>{at('Menü 4')}
			<input id="KiemeltCheck" name="kiemelt" type="checkbox"{if ($cimke.kiemelt)}checked="checked"{/if}>{at('Kiemelt')}
			<table><tbody>
			<tr>
				<td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
				<td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$cimke.oldalcim}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{at('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$cimke.leiras}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$cimke.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}"/>
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
