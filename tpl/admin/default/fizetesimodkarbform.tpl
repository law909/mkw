<div id="mattkarb-header">
	<h3>{at('Fizetési mód')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#HatarTab">{at('Rugalmas határok')}</a></li>
            {if ($setup.multilang)}
                <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            {/if}
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table>
				<tbody>
                    <tr>
                        <td><label for="NevEdit">{at('Név')}:</label></td>
                        <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                    </tr>
                    <tr>
                        <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                        <td><textarea id="LeirasEdit" name="leiras" type="text">{$egyed.leiras}</textarea></td>
                    </tr>
                    <tr>
                        <td><label for="TipusEdit">{at('Típus')}:</label></td>
                        <td>
                            <select id="TipusEdit" name="tipus">
                                <option value="B"{if ($egyed.tipus == 'B')} selected="selected"{/if}>{at('Bank')}</option>
                                <option value="P"{if ($egyed.tipus == 'P')} selected="selected"{/if}>{at('Pénztár')}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="HaladekEdit">{at('Haladék')}:</label></td>
                        <td><input id="HaladekEdit" name="haladek" type="number" value="{$egyed.haladek}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                        <td><input id="SorrendEdit" name="sorrend" type="number" value="{$egyed.sorrend}"></td>
                    </tr>
                    <tr>
                        <td><label for="EmagidEdit">{at('eMAG id')}:</label></td>
                        <td><input id="EmagidEdit" name="emagid" type="number" value="{$egyed.emagid}"></td>
                    </tr>
                    <tr>
                        <td><label for="WebesEdit">{at('Webes')}:</label></td>
                        <td><input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="NincspenzmozgasEdit">{at('Nincs pénzmozgás')}:</label></td>
                        <td><input id="NincspenzmozgasEdit" name="nincspenzmozgas" type="checkbox"{if ($egyed.nincspenzmozgas)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="RugalmasEdit">{at('Rugalmas')}:</label></td>
                        <td><input id="RugalmasEdit" name="rugalmas" type="checkbox"{if ($egyed.rugalmas)} checked="checked"{/if}></td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek1Edit">{at('Osztott haladék 1')}:</label></td>
                        <td><input id="Osztotthaladek1Edit" name="osztotthaladek1" type="number" value="{$egyed.osztotthaladek1}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek1Edit">{at('Osztott százalék 1')}:</label></td>
                        <td><input id="Osztottszazalek1Edit" name="osztottszazalek1" type="number" step="any" value="{$egyed.osztottszazalek1}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek2Edit">{at('Osztott haladék 2')}:</label></td>
                        <td><input id="Osztotthaladek2Edit" name="osztotthaladek2" type="number" value="{$egyed.osztotthaladek2}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek2Edit">{at('Osztott százalék 2')}:</label></td>
                        <td><input id="Osztottszazalek2Edit" name="osztottszazalek2" type="number" step="any" value="{$egyed.osztottszazalek2}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek3Edit">{at('Osztott haladék 3')}:</label></td>
                        <td><input id="Osztotthaladek3Edit" name="osztotthaladek3" type="number" value="{$egyed.osztotthaladek3}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek3Edit">{at('Osztott százalék 3')}:</label></td>
                        <td><input id="Osztottszazalek3Edit" name="osztottszazalek3" type="number" step="any" value="{$egyed.osztottszazalek3}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek4Edit">{at('Osztott haladék 4')}:</label></td>
                        <td><input id="Osztotthaladek4Edit" name="osztotthaladek4" type="number" value="{$egyed.osztotthaladek4}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek4Edit">{at('Osztott százalék 4')}:</label></td>
                        <td><input id="Osztottszazalek4Edit" name="osztottszazalek4" type="number" step="any" value="{$egyed.osztottszazalek4}"> %</td>
                    </tr>
                    <tr>
                        <td><label for="Osztotthaladek5Edit">{at('Osztott haladék 5')}:</label></td>
                        <td><input id="Osztotthaladek5Edit" name="osztotthaladek5" type="number" value="{$egyed.osztotthaladek5}"> {at('nap')}</td>
                    </tr>
                    <tr>
                        <td><label for="Osztottszazalek5Edit">{at('Osztott százalék 5')}:</label></td>
                        <td><input id="Osztottszazalek5Edit" name="osztottszazalek5" type="number" step="any" value="{$egyed.osztottszazalek5}"> %</td>
                    </tr>
			    </tbody>
			</table>
		</div>
        <div id="HatarTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.hatarok as $hatar}
                {include 'fizmodfizmodhatarkarb.tpl'}
            {/foreach}
            <a class="js-hatarnewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
        {if ($setup.multilang)}
            <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.translations as $translation}
                    {include 'translationkarb.tpl'}
                {/foreach}
                <a class="js-translationnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
    </div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>