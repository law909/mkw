<div id="fakarb-header">
	<h3>{$fa.nev}</h3>
</div>
<form id="fakarb-form" method="post" action="/admin/termekfa/save" data-id="{$fa.id}">
	<div id="fakarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
            {if ($setup.multilang)}<li><a href="#TranslationTab">{t('Idegennyelvi adatok')}</a></li>{/if}
			<li><a href="#WebTab">{t('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255" value="{$fa.nev}" required autofocus></td>
				<input id="ParentIdEdit" name="parentid" type="hidden" value="{$fa.parentid}">
			</tr>
			<tr>
				<td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$fa.sorrend}"></td>
			</tr>
			</tbody></table>
			{include 'termekfaimagekarb.tpl'}
		</div>
		{if ($setup.multilang)}
		<div id="TranslationTab" class="mattkarb-page" data-visible="visible">
			{foreach $fa.translations as $translation}
			{include 'termekfatranslationkarb.tpl'}
			{/foreach}
			<a class="js-translationnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
		</div>
		{/if}
		<div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<input id="InaktivCheck" name="inaktiv" type="checkbox"{if ($fa.inaktiv)}checked="checked"{/if}>{t('Inaktív')}</input>
			<input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox"{if ($fa.menu1lathato)}checked="checked"{/if}>{t('Főmenü')}</input>
			<input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox"{if ($fa.menu2lathato)}checked="checked"{/if}>{t('Főmenü lenyíló')}</input>
			<input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox"{if ($fa.menu3lathato)}checked="checked"{/if}>{t('Top kategória')}</input>
			<input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox"{if ($fa.menu4lathato)}checked="checked"{/if}>{t('Kategória lista')}</input>
			<table><tbody>
			<tr>
				<td><label for="OldalCimEdit">{t('Lap címe')}:</label></td>
				<td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$fa.oldalcim}"></td>
			</tr>
			<tr>
				<td><label for="RovidleirasEdit">{t('Rövid leírás')}:</label></td>
				<td><input id="RovidleirasEdit" name="rovidleiras" type="text" size="100" maxlength="255" value="{$fa.rovidleiras}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{t('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$fa.leiras}</textarea></td>
			</tr>
			<tr>
				<td><label for="Leiras2Edit">{t('Leírás 2')}:</label></td>
				<td><textarea id="Leiras2Edit" name="leiras2">{$fa.leiras2}</textarea></td>
			</tr>
			<tr>
				<td><label for="SeoDescriptionEdit">{t('META leírás')}:</label></td>
				<td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$fa.seodescription}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$fa.id}">
	<div class="mattkarb-footer">
		<input id="fakarb-okbutton" type="submit" value="{t('OK')}">
		<a id="fakarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>
