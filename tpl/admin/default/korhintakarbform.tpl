<div id="mattkarb-header">
	<h3>{at('Körhinta')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$egyed.id}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><input id="LathatoCheck" name="lathato" type="checkbox"{if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}</input></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="text" value="{$egyed.sorrend}"></td>
			</tr>
			<tr>
				<td><label for="NevEdit">{at('Cím')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="SzovegEdit">{at('Szöveg')}:</label></td>
				<td><textarea id="SzovegEdit" name="szoveg">{$egyed.szoveg}</textarea></td>
			</tr>
			<tr>
				<td><label for="UrlEdit">{at('URL')}:</label></td>
				<td><input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}"></td>
			</tr>
			<tr>
			{include 'korhintaimagekarb.tpl'}
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