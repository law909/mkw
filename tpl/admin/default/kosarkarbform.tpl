<div id="mattkarb-header">
	<h3>{at('Ko')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
			<li><a href="#WebTab">{at('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			</tbody></table>
		</div>
		<div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>

		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>