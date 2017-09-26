<div id="fakarb-header">
	<h3>{$egyed.nev}</h3>
</div>
<form id="fakarb-form" method="post" action="/admin/termekfa/save" data-id="{$egyed.id}">
	<div id="fakarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            {if ($setup.multilang)}<li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>{/if}
			<li><a href="#WebTab">{at('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255" value="{$egyed.nev}" required autofocus></td>
				<input id="ParentIdEdit" name="parentid" type="hidden" value="{$egyed.parentid}">
			</tr>
			<tr>
				<td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}"></td>
			</tr>
            <tr>
                <td><label for="EmagidEdit">{at('eMAG id')}:</label></td>
                <td><input id="EmagidEdit" name="emagid" type="number" size="10" maxlength="10" value="{$egyed.emagid}"></td>
            </tr>
			</tbody></table>
			{include 'termekfaimagekarb.tpl'}
		</div>
		{if ($setup.multilang)}
		<div id="TranslationTab" class="mattkarb-page" data-visible="visible">
			{foreach $egyed.translations as $translation}
			{include 'translationkarb.tpl'}
			{/foreach}
			<a class="js-translationnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
		</div>
		{/if}
		<div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<input id="InaktivCheck" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}</input>
			<input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox"{if ($egyed.menu1lathato)}checked="checked"{/if}>{at('Főmenü')}</input>
			<input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox"{if ($egyed.menu2lathato)}checked="checked"{/if}>{at('Főmenü lenyíló')}</input>
			<input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox"{if ($egyed.menu3lathato)}checked="checked"{/if}>{at('Top kategória')}</input>
			<input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox"{if ($egyed.menu4lathato)}checked="checked"{/if}>{at('Kategória lista')}</input>
			<table><tbody>
			<tr>
				<td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
				<td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$egyed.oldalcim}"></td>
			</tr>
			<tr>
				<td><label for="RovidleirasEdit">{at('Rövid leírás')}:</label></td>
				<td><input id="RovidleirasEdit" name="rovidleiras" type="text" size="100" maxlength="255" value="{$egyed.rovidleiras}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{at('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
			</tr>
			<tr>
				<td><label for="Leiras2Edit">{at('Leírás 2')}:</label></td>
				<td><textarea id="Leiras2Edit" name="leiras2">{$egyed.leiras2}</textarea></td>
			</tr>
			<tr>
				<td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
				<td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="fakarb-okbutton" type="submit" value="{at('OK')}">
		<a id="fakarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
