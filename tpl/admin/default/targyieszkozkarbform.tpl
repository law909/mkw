<div id="mattkarb-header">
	<h3>{t('Tárgyieszköz')}</h3>
	<h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
			<li><a href="#BeszTab">{t('Beszerzés')}</a></li>
			<li><a href="#ECSTab">{t('Értékcsökkenés')}</a></li>
			<li><a href="#WebTab">{t('Webes adatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for=LeltariszamEdit">{t('Leltári szám')}:</label></td>
				<td><input id="LeltariszamEdit" name="leltariszam" type="text" size="30" maxlength="255" value="{$egyed.leltariszam}"  required autofocus></td>
			</tr>
			<tr>
				<td><label for="SorozatszamEdit">{t('Sorozatszám')}:</label></td>
				<td><input id="SorozatszamEdit" name="sorozatszam" type="text" size="30" maxlength="255" value="{$egyed.sorozatszam}" required></td>
			</tr>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required></td>
			</tr>
			<tr>
				<td><label for="TipusEdit">{t('Típus')}:</label></td>
				<td><select id="TipusEdit" name="tipus" required>
					<option value="">{t('válasszon')}</option>
				{foreach $egyed.tipuslista as $_tipus}<option value="{$_tipus.id}"{if ($_tipus.selected)} selected="selected"{/if}>{$_tipus.caption}</option>{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="CsoportEdit">{t('Csoport')}:</label></td>
				<td><select id="CsoportEdit" name="csoport">
					<option value="">{t('válasszon')}</option>
					{foreach $csoportlist as $_csoport}
						<option value="{$_csoport.id}"{if ($_csoport.selected)} selected="selected"{/if}>{$_csoport.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="AlkalmazottEdit">{t('Alkalmazott')}:</label></td>
				<td><select id="AlkalmazottEdit" name="alkalmazott">
					<option value="">{t('válasszon')}</option>
					{foreach $alkalmazottlist as $_alkalmazott}
						<option value="{$_alkalmazott.id}"{if ($_alkalmazott.selected)} selected="selected"{/if}>{$_alkalmazott.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="EcselszmodEdit">{t('ÉCS elszámolási mód')}:</label></td>
				<td><select id="EcselszmodEdit" name="ecselszmod" required>
					<option value="">{t('válasszon')}</option>
				{foreach $egyed.ecselszmodlista as $_ecselszmod}<option value="{$_ecselszmod.id}"{if ($_ecselszmod.selected)} selected="selected"{/if}>{$_ecselszmod.caption}</option>{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="NincsecsEdit">{t('Nem értékcsökkenthető')}:</label></td>
				<td><input id="NincsecsEdit" name="nincsecs" type="checkbox" {if ($egyed.nincsecs)}checked="checked"{/if}></td>
			</tr>
			<tr>
				<td><label for="HasznalatihelyEdit">{t('Használati hely')}:</label></td>
				<td><input id="HasznalatihelyEdit" name="hasznalatihely" type="text" value="{$egyed.hasznalatihely}"></td>
			</tr>
			<tr>
				<td><label for="AllapotEdit">{t('Állapot')}</label></td>
				<td><select id="AllapotEdit" name="allapot" required>
					<option value="">{t('válasszon')}</option>
				{foreach $egyed.allapotlista as $_allapot}<option value="{$_allapot.id}"{if ($_allapot.selected)} selected="selected"{/if}>{$_allapot.caption}</option>{/foreach}
				</select></td>
			</tr>
			<tr id="AllapotDatumTr">
				<td><label for="AllapotDatumEdit">{t('Eladás/selejtezés dátuma')}:</label></td>
				<td><input id="AllapotDatumEdit" name="allapotdatum" type="text" size="12" data-esedekes="{$egyed.allapotdatumstr}"></td>
			</tr>
			</tbody></table>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Beszerzés')}" data-refcontrol="#BeszTab"></div>
		{/if}
		<div id="BeszTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<table><tbody>
			<tr>
				<td><label for="BeszerzesDatumEdit">{t('Beszerzés dátuma')}:</label></td>
				<td><input id="BeszerzesDatumEdit" name="beszerzesdatum" type="text" size="12" data-esedekes="{$egyed.beszerzesdatumstr}"></td>
			</tr>
			<tr>
				<td><label for="BeszErtekEdit">{t('Beszerzés költsége')}:</label></td>
				<td><input id="BeszErtekEdit" name="beszerzesertek" type="number" step="any" size="11" maxlength="14" value="{$egyed.beszerzesertek}"></td>
			</tr>
			</tbody></table>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Értékcsökkenés')}" data-refcontrol="#ECSTab"></div>
		{/if}
		<div id="ECSTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<table>
			<thead><tr>
			<th>{t('Számviteli törvény szerint')}</th>
			<th></th>
			<th>{t('Társasági adó tv. szerint')}</th>
			<th></th>
			</tr></thead>
			<tbody>
			<tr>
				<td><label for="SzvtvleirasikulcsEdit">{t('Leírási kulcs')}:</label></td>
				<td><input id="SzvtvleirasikulcsEdit" name="szvtvleirasikulcs" type="number" step="any" size="11" maxlength="14" value="{$egyed.szvtvleirasikulcs}"> %</td>
				<td><label for="TatvleirasikulcsEdit">{t('Leírási kulcs')}:</label></td>
				<td><input id="TatvleirasikulcsEdit" name="tatvleirasikulcs" type="number" step="any" size="11" maxlength="14" value="{$egyed.tatvleirasikulcs}"> %</td>
			</tr>
			<tr>
				<td><label for="SzvtvmaradvanyertekEdit">{t('Maradványérték')}:</label></td>
				<td><input id="SzvtvmaradvanyertekEdit" name="szvtvmaradvanyertek" type="number" step="any" size="11" maxlength="14" value="{$egyed.szvtvmaradvanyertek}"></td>
			</tr>
			<tr>
				<td><label for="SzvtvelszkezdeteEdit">{t('Elszámolás kezdete')}:</label></td>
				<td><input id="SzvtvelszkezdeteEdit" name="szvtvelszkezdete" type="text" size="12" data-esedekes="{$egyed.szvtvelszkezdetestr}"></td>
				<td><label for="TatvelszkezdeteEdit">{t('Elszámolás kezdete')}:</label></td>
				<td><input id="TatvelszkezdeteEdit" name="tatvelszkezdete" type="text" size="12" data-esedekes="{$egyed.tatvelszkezdetestr}"></td>
			</tr>
			</tbody></table>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Webes adatok')}" data-refcontrol="#WebTab"></div>
		{/if}
		<div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<table><tbody>
			<tr>
				<td><label for="SlugEdit">{t('Permalink')}:</label></td>
				<td><input id="SlugEdit" name="slug" type="text" size="30" maxlength="255" value="{$egyed.slug}"></td>
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