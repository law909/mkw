<div id="mattkarb-header">
	<h3>{t('Fizetési mód')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
            <li><a href="#HatarTab">{t('Rugalmas határok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table>
				<tbody>
                    <tr>
                        <td><label for="NevEdit">{t('Név')}:</label></td>
                        <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                    </tr>
                    <tr>
                        <td><label for="LeirasEdit">{t('Leírás')}:</label></td>
                        <td><textarea id="LeirasEdit" name="leiras" type="text">{$egyed.leiras}</textarea></td>
                    </tr>
                    <tr>
                        <td><label for="TipusEdit">{t('Típus')}:</label></td>
                        <td>
                            <select id="TipusEdit" name="tipus">
                                <option value="B"{if ($egyed.tipus == 'B')} selected="selected"{/if}>Bank</option>
                                <option value="P"{if ($egyed.tipus == 'P')} selected="selected"{/if}>Pénztár</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="HaladekEdit">{t('Haladék')}:</label></td>
                        <td><input id="HaladekEdit" name="haladek" type="number" value="{$egyed.haladek}"> nap</td>
                    </tr>
                    <tr>
                        <td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
                        <td><input id="SorrendEdit" name="sorrend" type="number" value="{$egyed.sorrend}"></td>
                    </tr>
                    <tr>
                        <td><label for="WebesEdit">{t('Webes')}:</label></td>
                        <td><input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="RugalmasEdit">{t('Rugalmas')}:</label></td>
                        <td><input id="RugalmasEdit" name="rugalmas" type="checkbox"{if ($egyed.rugalmas)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek1Edit">{t('Osztott haladék 1')}:</label></td>
                        <td><input id="Osztotthaladek1Edit" name="osztotthaladek1" type="number" value="{$egyed.osztotthaladek1}"> nap</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek1Edit">{t('Osztott százalék 1')}:</label></td>
                        <td><input id="Osztottszazalek1Edit" name="osztottszazalek1" type="number" step="any" value="{$egyed.osztottszazalek1}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek2Edit">{t('Osztott haladék 2')}:</label></td>
                        <td><input id="Osztotthaladek2Edit" name="osztotthaladek2" type="number" value="{$egyed.osztotthaladek2}"> nap</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek2Edit">{t('Osztott százalék 2')}:</label></td>
                        <td><input id="Osztottszazalek2Edit" name="osztottszazalek2" type="number" step="any" value="{$egyed.osztottszazalek2}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek3Edit">{t('Osztott haladék 3')}:</label></td>
                        <td><input id="Osztotthaladek3Edit" name="osztotthaladek3" type="number" value="{$egyed.osztotthaladek3}"> nap</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek3Edit">{t('Osztott százalék 3')}:</label></td>
                        <td><input id="Osztottszazalek3Edit" name="osztottszazalek3" type="number" step="any" value="{$egyed.osztottszazalek3}"> %</td>
                    </tr>
			    </tbody>
			</table>
		</div>
        <div id="HatarTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.hatarok as $hatar}
                {include 'fizmodfizmodhatarkarb.tpl'}
            {/foreach}
            <a class="js-hatarnewbutton" href="#" title="{t('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
    </div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>