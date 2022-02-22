<div id="mattkarb-header">
	<h3>{at('Kupon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/kupon/save">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="IdEdit">{at('Id')}:</label></td>
				<td><input id="IdEdit" type="text" name="xid" value="{$egyed.id}"></td>
			</tr>
            <tr>
                <td><label for="TipusEdit">{at('Típus')}:</label></td>
                <td><select id="TipusEdit" name="tipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tipuslist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
            <tr>
                <td><label for="LejaratEdit">{at('Állapot')}:</label></td>
                <td><select id="LejaratEdit" name="lejart">
                        <option value="">{at('válasszon')}</option>
                        {foreach $lejaratlist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
            <tr>
                <td><label for="OsszegEdit">{at('Összeg')}:</label></td>
                <td><input id="OsszegEdit" type="text" name="osszeg" value="{$egyed.osszeg}"></td>
            </tr>
            <tr>
                <td><label for="MinOsszegEdit">{at('Minimum kosárérték')}:</label></td>
                <td><input id="MinOsszegEdit" type="text" name="minimumosszeg" value="{$egyed.minimumosszeg}"></td>
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