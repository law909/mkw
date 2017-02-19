<div id="mattkarb-header">
	<h3>{at('Jelenléti ív')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label>{at('Dolgozó')}:</label></td>
				<td><select id="DolgozoEdit" name="dolgozo" required autofocus>
					<option value="">{at('válasszon')}</option>
					{foreach $dolgozolist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="DatumEdit">{at('Dátum')}:</label></td>
				<td><input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required></td>
			</tr>
			<tr>
				<td><label for="JelenlettipusEdit">{at('Jelenlét')}:</label></td>
				<td><select id="JelenlettipusEdit" name="jelenlettipus" required>
					<option value="">{at('válasszon')}</option>
					{foreach $jelenlettipuslist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><input id="MunkaidoEdit" name="munkaido" type="text" size="5" maxlength="2" value="{$egyed.munkaido}" required> {at('óra')}</td>
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