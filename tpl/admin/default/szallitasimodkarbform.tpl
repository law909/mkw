<div id="mattkarb-header">
	<h3>{t('Szállítási mód')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
            <li><a href="#HatarTab">{t('Összeghatárok')}</a></li>
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
                        <td><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
                    </tr>
                    <tr>
                        <td><label for="FizmodEdit">{t('Fizetési módok')}:</label></td>
                        <td><input id="FizmodEdit" name="fizmodok" type="text" size="80" maxlength="255" value="{$egyed.fizmodok}"></td>
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
                        <td><label for="VanSzallktgEdit">{t('Van száll.költség')}:</label></td>
                        <td><input id="VanSzallktgEdit" name="vanszallitasiktg" type="checkbox"{if ($egyed.vanszallitasiktg)} checked="checked"{/if}></td>
                    </tr>
			    </tbody>
			</table>
		</div>
        <div id="HatarTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.hatarok as $hatar}
                {include 'szallitasimodhatarkarb.tpl'}
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