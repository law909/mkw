<div id="mattkarb-header">
	<h3>{$headcaption}</h3>
	<h4>{$cimke.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$cimke.id}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
			<li><a href="#WebTab">{t('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$cimke.nev}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="CimkecsoportEdit">{t('Címkecsoport')}:</label></td>
				<td><select id="CimkecsoportEdit" name="cimkecsoport" required>
					<option value="">{t("válasszon")}</option>
					{foreach $cimkecsoportlist as $_ccs}
						<option value="{$_ccs.id}"{if ($_ccs.selected)} selected="selected"{/if}>{$_ccs.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$cimke.sorrend}"</td>
			</tr>
			<tr>
			{if ($kellkep)}
			{include 'cimkeimagekarb.tpl'}
			{/if}
			</tr>
			</tbody></table>
		</div>
		<div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox"{if ($cimke.menu1lathato)}checked="checked"{/if}>{t('Menü 1')}</input>
			<input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox"{if ($cimke.menu2lathato)}checked="checked"{/if}>{t('Menü 2')}</input>
			<input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox"{if ($cimke.menu3lathato)}checked="checked"{/if}>{t('Menü 3')}</input>
			<input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox"{if ($cimke.menu4lathato)}checked="checked"{/if}>{t('Menü 4')}</input>
			<input id="KiemeltCheck" name="kiemelt" type="checkbox"{if ($cimke.kiemelt)}checked="checked"{/if}>{t('Kiemelt')}</input>
			<table><tbody>
			<tr>
				<td><label for="OldalCimEdit">{t('Lap címe')}:</label></td>
				<td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$cimke.oldalcim}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{t('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$cimke.leiras}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$cimke.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}"/>
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>
