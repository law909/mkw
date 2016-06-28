<div id="mattkarb-header">
	<h3>{t('Kupon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/kupon/save">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><span>{t('Id')}:</span></td>
				<td><span>{$egyed.id}</span></td>
			</tr>
            <tr>
                <td><label for="TipusEdit">{t('Típus')}:</label></td>
                <td><select id="TipusEdit" name="tipus">
                        <option value="">{t('válasszon')}</option>
                        {foreach $tipuslist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
            <tr>
                <td><label for="LejaratEdit">{t('Lejárat')}:</label></td>
                <td><select id="LejaratEdit" name="lejart">
                        <option value="">{t('válasszon')}</option>
                        {foreach $lejaratlist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>