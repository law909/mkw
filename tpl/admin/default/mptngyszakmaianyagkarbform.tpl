<div id="mattkarb-header">
	<h3>{at('Szakmai anyag')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/mptngyszakmaianyag/save">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
			<li><a href="#WebTab">{at('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table>
				<tbody>
				<tr>
					<td></td>
					<td>
						<label for="biralatkeszEdit">{at('Bírálat kész')}:</label>
						<input id="biralatkeszEdit" type="checkbox" name="biralatkesz"{if ($egyed.biralatkesz)} checked{/if}>
						<label for="kszEdit">{at('Konferencián szerepelhet')}:</label>
						<input id="kszEdit" type="checkbox" name="konferencianszerepelhet"{if ($egyed.konferencianszerepelhet)} checked{/if}>
					</td>
				</tr>
				<tr>
					<td><label for="CimEdit">{at('Cím')}:</label></td>
					<td><input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim}" required></td>
				</tr>
				<tr>
					<td><label for="kezdodatumEdit">{at('Kezdés')}:</label></td>
					<td>
						<select id="kezdodatumEdit" name="kezdodatum">
							<option value="">{at('válasszon')}</option>
							{foreach $datumlist as $_mk}
								<option
									value="{$_mk.id}"
									{if ($_mk.selected)} selected="selected"{/if}
								>{$_mk.caption}</option>
							{/foreach}
						</select>
						<input name="kezdoido" value="{$egyed.kezdoido}">
					</td>
				</tr>
				<tr>
					<td><label for="tipusEdit">{at('Típus')}:</label></td>
					<td>
						<select id="tipusEdit" name="tipus" required>
							<option value="">{at('válasszon')}</option>
							{foreach $tipuslist as $_mk}
								<option
									value="{$_mk.id}"
									{if ($_mk.selected)} selected="selected"{/if}
									{if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
								>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="tulajdonosEdit">{at('Tulajdonos')}:</label></td>
					<td>
						<select id="tulajdonosEdit" name="tulajdonos" required>
							<option value="">{at('válasszon')}</option>
							{foreach $tulajdonoslist as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="szerzo1emailEdit">{at('Szerzők')}:</label></td>
					<td>
						<input id="szerzo1emailEdit" name="szerzo1email" type="email" value="{$egyed.szerzo1email}">
						<input name="szerzo2email" type="email" value="{$egyed.szerzo2email}">
						<input name="szerzo3email" type="email" value="{$egyed.szerzo3email}">
						<input name="szerzo4email" type="email" value="{$egyed.szerzo4email}">
						<input name="szerzo5email" type="email" value="{$egyed.szerzo5email}" class="onlyszimpozium hidden">
					</td>
				</tr>
				<tr>
					<td><label for="szerzo1Edit">{at('Szerzők')}:</label></td>
					<td>
						<select id="szerzo1Edit" name="szerzo1">
							<option value="">{at('válasszon')}</option>
							{foreach $szerzo1list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="szerzo2Edit" name="szerzo2">
							<option value="">{at('válasszon')}</option>
							{foreach $szerzo2list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="szerzo3Edit" name="szerzo3">
							<option value="">{at('válasszon')}</option>
							{foreach $szerzo3list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="szerzo4Edit" name="szerzo4">
							<option value="">{at('válasszon')}</option>
							{foreach $szerzo4list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="szerzo5Edit" name="szerzo5" class="onlyszimpozium hidden">
							<option value="">{at('válasszon')}</option>
							{foreach $szerzo5list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="biralo1Edit">{at('Bírálók')}:</label></td>
					<td>
						<select id="biralo1Edit" name="biralo1">
							<option value="">{at('válasszon')}</option>
							{foreach $biralo1list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="biralo2Edit" name="biralo2">
							<option value="">{at('válasszon')}</option>
							{foreach $biralo2list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<select id="biralo3Edit" name="biralo3">
							<option value="">{at('válasszon')}</option>
							{foreach $biralo3list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr class="onlyszimpozium hidden">
					<td><label for="eloadas1Edit">{at('Előadás 1')}:</label></td>
					<td>
						<select id="eloadas1Edit" name="eloadas1">
							<option value="">{at('válasszon')}</option>
							{foreach $eloadas1list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr class="onlyszimpozium hidden">
					<td><label for="eloadas2Edit">{at('Előadás 2')}:</label></td>
					<td>
						<select id="eloadas2Edit" name="eloadas2">
							<option value="">{at('válasszon')}</option>
							{foreach $eloadas2list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr class="onlyszimpozium hidden">
					<td><label for="eloadas3Edit">{at('Előadás 3')}:</label></td>
					<td>
						<select id="eloadas3Edit" name="eloadas3">
							<option value="">{at('válasszon')}</option>
							{foreach $eloadas3list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr class="onlyszimpozium hidden">
					<td><label for="eloadas4Edit">{at('Előadás 4')}:</label></td>
					<td>
						<select id="eloadas4Edit" name="eloadas4">
							<option value="">{at('válasszon')}</option>
							{foreach $eloadas4list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr class="onlyszimpozium hidden">
					<td><label for="eloadas5Edit">{at('Előadás 5')}:</label></td>
					<td>
						<select id="eloadas5Edit" name="eloadas5">
							<option value="">{at('válasszon')}</option>
							{foreach $eloadas5list as $_mk}
								<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="kulcsszo1Edit">{at('Kulcsszavak')}:</label></td>
					<td>
						<input id="kulcsszo1Edit" name="kulcsszo1" type="text" value="{$egyed.kulcsszo1}">
						<input name="kulcsszo2" type="text" value="{$egyed.kulcsszo2}">
						<input name="kulcsszo3" type="text" value="{$egyed.kulcsszo3}">
						<input name="kulcsszo4" type="text" value="{$egyed.kulcsszo4}">
						<input name="kulcsszo5" type="text" value="{$egyed.kulcsszo5}">
					</td>
				</tr>
				<tr>
					<td><label for="tartalomEdit">{at('Tartalom')}:</label></td>
					<td><textarea id="tartalomEdit" name="tartalom" rows="20" cols="80" required>{$egyed.tartalom}</textarea></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="WebTab" class="mattkarb-page">

		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>