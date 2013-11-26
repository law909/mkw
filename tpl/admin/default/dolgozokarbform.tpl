<div id="mattkarb-header">
	<h3>{t('Dolgozó')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="SzulidoEdit">{t('Születési idő')}:</label></td>
				<td><input id="SzulidoEdit" name="szulido" type="text" size="12" data-datum="{$egyed.szulidostr}"></td>
				<td><label for="SzulhelyEdit">{t('Születési hely')}:</label></td>
				<td><input id="SzulhelyEdit" name="szulhely" type="text" size="40" maxlength="60" value="{$egyed.szulhely}"></td>
			</tr>
			<tr>
				<td><label for="IrszamEdit">{t('Cím')}:</label></td>
				<td colspan="3"><input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$egyed.irszam}">
				<input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$egyed.varos}">
				<input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$egyed.utca}"></td>
			</tr>
			<tr>
				<td><label for="TelefonEdit">{t('Telefon')}:</label></td>
				<td><input id="TelefonEdit" name="telefon" type="text" size="20" maxlength="40" value="{$egyed.telefon}"></td>
            </tr>
            <tr>
				<td><label for="EmailEdit">{t('Email')}:</label></td>
				<td><input id="EmailEdit" name="email" type="email" size="40" maxlength="100" value="{$egyed.email}" required></td>
			</tr>
            {if (($egyed.id == $loggedinuser.id) || $loggedinuser.admin)}
            <tr>
				<td><label for="Pass1Edit">{t('Jelszó 1')}:</label></td>
				<td><input id="Pass1Edit" name="jelszo1" type="password" size="40"{if ($oper == 'add')} required{/if}></td>
				<td><label for="Pass2Edit">{t('Jelszó 2')}:</label></td>
				<td><input id="Pass2Edit" name="jelszo2" type="password" size="40"{if ($oper == 'add')} required{/if}></td>
			</tr>
            {/if}
            {if ($loggedinuser.admin)}
			<tr>
				<td><label for="MunkakorEdit">{t('Munkakör')}:</label></td>
				<td><select id="MunkakorEdit" name="munkakor">
					<option value="">{t('válasszon')}</option>
					{foreach $munkakorlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><label for="MunkaviszonykezdeteEdit">{t('Munkaviszony kezdete')}:</label></td>
				<td><input id="MunkaviszonykezdeteEdit" name="munkaviszonykezdete" type="text" size="12" data-datum="{$egyed.munkaviszonykezdetestr}" required></td>
			</tr>
			<tr>
				<td><label for="EvesmaxszabiEdit">{t('Éves max. szabadság')}:</label></td>
				<td colspan="3"><input id="EvesmaxszabiEdit" name="evesmaxszabi" type="number" size="5" maxlength="5" value="{$egyed.evesmaxszabi}"> {t('nap')}</td>
			</tr>
            {/if}
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