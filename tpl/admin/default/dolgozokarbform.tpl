<div id="mattkarb-header">
	<h3>{at('Dolgozó')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
                <tr>
                    <td><label for="InaktivEdit">{at('Inaktív')}:</label></td>
                    <td><input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked{/if}></td>
                </tr>
                <tr>
                    <td><label for="oraelmaradaskonyvelonekEdit">{at('Óra elmaradásról értesítés a könyvelőnek')}:</label></td>
                    <td><input id="oraelmaradaskonyvelonekEdit" name="oraelmaradaskonyvelonek" type="checkbox"{if ($egyed.oraelmaradaskonyvelonek)} checked{/if}></td>
                </tr>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="SzulidoEdit">{at('Születési idő')}:</label></td>
				<td><input id="SzulidoEdit" name="szulido" type="text" size="12" data-datum="{$egyed.szulidostr}"></td>
				<td><label for="SzulhelyEdit">{at('Születési hely')}:</label></td>
				<td><input id="SzulhelyEdit" name="szulhely" type="text" size="40" maxlength="60" value="{$egyed.szulhely}"></td>
			</tr>
			<tr>
				<td><label for="IrszamEdit">{at('Cím')}:</label></td>
				<td colspan="3"><input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$egyed.irszam}">
				<input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$egyed.varos}">
				<input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$egyed.utca}"></td>
			</tr>
			<tr>
				<td><label for="TelefonEdit">{at('Telefon')}:</label></td>
				<td><input id="TelefonEdit" name="telefon" type="text" size="20" maxlength="40" value="{$egyed.telefon}"></td>
            </tr>
            <tr>
				<td><label for="EmailEdit">{at('Email')}:</label></td>
				<td><input id="EmailEdit" name="email" type="email" size="40" maxlength="100" value="{$egyed.email}" required></td>
			</tr>
            <tr>
                <td><label for="UrlEdit">{at('URL')}:</label></td>
                <td><input id="UrlEdit" name="url" type="text" size="40" maxlength="255" value="{$egyed.url}"></td>
            </tr>
            {if (($egyed.id == $loggedinuser.id) || $loggedinuser.admin)}
            <tr>
				<td><label for="Pass1Edit">{at('Jelszó 1')}:</label></td>
				<td><input id="Pass1Edit" name="jelszo1" type="password" size="40"{if ($oper == 'add')} required{/if}></td>
				<td><label for="Pass2Edit">{at('Jelszó 2')}:</label></td>
				<td><input id="Pass2Edit" name="jelszo2" type="password" size="40"{if ($oper == 'add')} required{/if}></td>
			</tr>
            {/if}
            {if ($loggedinuser.admin)}
			<tr>
				<td><label for="MunkakorEdit">{at('Munkakör')}:</label></td>
				<td><select id="MunkakorEdit" name="munkakor">
					<option value="">{at('válasszon')}</option>
					{foreach $munkakorlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><label for="MunkaviszonykezdeteEdit">{at('Munkaviszony kezdete')}:</label></td>
				<td><input id="MunkaviszonykezdeteEdit" name="munkaviszonykezdete" type="text" size="12" data-datum="{$egyed.munkaviszonykezdetestr}" required></td>
			</tr>
			<tr>
				<td><label for="EvesmaxszabiEdit">{at('Éves max. szabadság')}:</label></td>
				<td colspan="3"><input id="EvesmaxszabiEdit" name="evesmaxszabi" type="number" size="5" maxlength="5" value="{$egyed.evesmaxszabi}"> {at('nap')}</td>
			</tr>
            {/if}
            <tr>
                <td><label for="HavilevonasEdit">{at('Havi levonás')}</label></td>
                <td><input id="HavilevonasEdit" name="havilevonas" type="number" step="any" value="{$egyed.havilevonas}"></td>
				<td><label for="NapilevonasEdit">{at('Napi levonás')}</label></td>
				<td><input id="NapilevonasEdit" name="napilevonas" type="number" step="any" value="{$egyed.napilevonas}"></td>
                <td><label for="SzamlatadEdit">{at('Számlát ad')}</label></td>
                <td><input id="SzamlatadEdit" name="szamlatad" type="checkbox"{if ($egyed.szamlatad)} checked="checked"{/if}></td>
            </tr>
            <tr>
                <td><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                <td><select id="FizmodEdit" name="fizmod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fizmodlist as $_fizmod}
                            <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                        {/foreach}
                    </select></td>
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