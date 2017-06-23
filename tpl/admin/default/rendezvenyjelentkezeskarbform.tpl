<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
    <h3>{at('Rendezvény jelentkezés')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/rendezvenyjelentkezes/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td class="mattable-important"><label for="PartnerEdit">{at('Partner')}:</label></td>
                    {if ($setup.partnerautocomplete)}
                        <td colspan="7">
                            <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" value="{$egyed.partnernev}" size=90 autofocus>
                            <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                            <input class="js-ujpartnercb" type="checkbox">Új</input>
                        </td>
                    {else}
                        <td colspan="7"><select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required" autofocus>
                                <option value="">{at('válasszon')}</option>
                                <option value="-1">{at('Új felvitel')}</option>
                                {foreach $partnerlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td><label>{at('Név')}:</label></td>
                    <td>
                        <input name="partnernev" value="{$egyed.partnernev}">
                    </td>
                    <td><label>{at('Vezetéknév')}:</td>
                    <td>
                        <input name="partnervezeteknev" value="{$egyed.partnervezeteknev}">
                    </td>
                    <td><label>{at('Keresztnév')}:</td>
                    <td colspan="3">
                        <input name="partnerkeresztnev" value="{$egyed.partnerkeresztnev}">
                    </td>
                </tr>
                <tr>
                    <td>{at('Számlázási cím')}:</td>
                    <td colspan="7">
                        <input name="partnerirszam" value="{$egyed.partnerirszam}" size="6" maxlength="10">
                        <input name="partnervaros" value="{$egyed.partnervaros}" size="20" maxlength="40">
                        <input name="partnerutca" value="{$egyed.partnerutca}" size="40" maxlength="60">
                    </td>
                </tr>
                <tr>
                    <td><label for="AdoszamEdit">{at('Adószám')}:</label></td>
                    <td>
                        <input id="AdoszamEdit" name="partneradoszam" value="{$egyed.partneradoszam}">
                    </td>
                    <td><label for="EUAdoszamEdit">{at('EU adószám')}:</label></td>
                    <td colspan="5">
                        <input id="EUAdoszamEdit" name="partnereuadoszam" value="{$egyed.partnereuadoszam}">
                    </td>
                </tr>
                <tr>
                    <td><label for="TelefonEdit">{at('Telefon')}:</label></td>
                    <td>
                        <input id="TelefonEdit" name="partnertelefon" value="{$egyed.partnertelefon}">
                    </td>
                    <td><label for="EmailEdit">{at('Email')}:</label></td>
                    <td colspan="5">
                        <input id="EmailEdit" name="partneremail" value="{$egyed.partneremail}">
                    </td>
                </tr>
                <tr>
                    <td><label for="DatumEdit" class="mattable-important">{at('Dátum')}:</label></td>
                    <td><input id="DatumEdit" name="datum" class="mattable-important" data-datum="{$egyed.datum}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="RendezvenyEdit" class="mattable-important">{at('Rendezvény')}:</label></td>
                    <td><select id="RendezvenyEdit" name="rendezveny"  class="mattable-important" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $rendezvenylist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                    <td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>