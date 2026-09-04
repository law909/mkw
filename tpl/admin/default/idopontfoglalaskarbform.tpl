<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete|default:0}">
    <h3>{at('Időpont jelentkezés')}</h3>
    <h4>{$egyed.partnernev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idopontfoglalas/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                {if ($oper == 'add')}
                    <tr>
                        <td><label for="IdopontEdit">{at('Időpont')}:</label></td>
                        <td colspan="3"><select id="IdopontEdit" name="idopont" class="js-idopontedit" required="required" autofocus>
                                <option value="">{at('válasszon')}</option>
                                {foreach $idopontlist as $_d}
                                    <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}
                                            data-ismetlodo="{$_d.ismetlodo}" data-nap="{$_d.nap}"
                                            data-datum="{$_d.datum}">{$_d.caption}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="DatumEdit">{at('Alkalom napja')}:</label></td>
                        <td colspan="3"><input id="DatumEdit" name="datum" data-datum="{$egyed.datum}" required="required"></td>
                    </tr>
                    <tr>
                        <td><label for="PartnerEdit">{at('Partner')}:</label></td>
                        {if ($setup.partnerautocomplete|default:0)}
                            <td colspan="3">
                                <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete"
                                       value="{$egyed.partnernev}" size="60">
                                <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partnerid}">
                                <input class="js-ujpartnercb" type="checkbox">{at('Új')}
                            </td>
                        {else}
                            <td colspan="3"><select id="PartnerEdit" name="partner" class="js-partnerid">
                                    <option value="">{at('válasszon')}</option>
                                    <option value="-1">{at('Új felvitel')}</option>
                                    {foreach $partnerlist as $_d}
                                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td><label for="PartnernevEdit">{at('Név')}:</label></td>
                        <td><input id="PartnernevEdit" name="partnernev" value="{$egyed.partnernev}"></td>
                        <td><label for="PartnertelefonEdit">{at('Telefon')}:</label></td>
                        <td><input id="PartnertelefonEdit" name="partnertelefon" value="{$egyed.partnertelefon}"></td>
                    </tr>
                    <tr>
                        <td><label for="PartneremailEdit">{at('Email')}:</label></td>
                        <td colspan="3"><input id="PartneremailEdit" name="partneremail" type="email" size="60"
                                               value="{$egyed.partneremail}"></td>
                    </tr>
                {elseif ($egyed.idoponttipus == 'rendezveny')}
                    {* rendezvény jelentkezésnél az időpont/partner/dátum eddig is szerkeszthető volt *}
                    <tr>
                        <td><label for="IdopontEdit">{at('Időpont')}:</label></td>
                        <td colspan="3"><select id="IdopontEdit" name="idopont" class="js-idopontedit">
                                <option value="">{at('válasszon')}</option>
                                {foreach $idopontlist as $_d}
                                    <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}
                                            data-ismetlodo="{$_d.ismetlodo}" data-nap="{$_d.nap}"
                                            data-datum="{$_d.datum}">{$_d.caption}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="DatumEdit">{at('Alkalom napja')}:</label></td>
                        <td colspan="3"><input id="DatumEdit" name="datum" data-datum="{$egyed.datum}"></td>
                    </tr>
                    <tr>
                        <td><label for="PartnerEdit">{at('Partner')}:</label></td>
                        <td colspan="3">
                            {if ($setup.partnerautocomplete|default:0)}
                                <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete"
                                       value="{$egyed.partnernev}" size="60">
                                <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partnerid}">
                            {else}
                                <select id="PartnerEdit" name="partner" class="js-partnerid">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $partnerlist as $_d}
                                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>{at('Email')}:</td>
                        <td>{$egyed.partneremail}</td>
                        <td>{at('Telefon')}:</td>
                        <td>{$egyed.partnertelefon}</td>
                    </tr>
                    <tr>
                        <td>{at('Jelentkezés ideje')}:</td>
                        <td>{$egyed.foglalasido}</td>
                    </tr>
                {else}
                    <tr>
                        <td>{at('Időpont')}:</td>
                        <td>{$egyed.datum} {$egyed.napnev} {$egyed.idopontkezdet}</td>
                    </tr>
                    <tr>
                        <td>{at('Téma')}:</td>
                        <td>{$egyed.idoponttemanev}</td>
                    </tr>
                    <tr>
                        <td>{at('Tanár')}:</td>
                        <td>{$egyed.idopontdolgozonev}</td>
                    </tr>
                    <tr>
                        <td>{at('Helyszín')}:</td>
                        <td>{$egyed.idoponthelyszinnev}</td>
                    </tr>
                    <tr>
                        <td>{at('Foglaló')}:</td>
                        <td>{$egyed.partnernev}</td>
                    </tr>
                    <tr>
                        <td>{at('Email')}:</td>
                        <td>{$egyed.partneremail}</td>
                    </tr>
                    <tr>
                        <td>{at('Telefon')}:</td>
                        <td>{$egyed.partnertelefon}</td>
                    </tr>
                    <tr>
                        <td>{at('Foglalás ideje')}:</td>
                        <td>{$egyed.foglalasido}</td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="OnlineEdit">{at('Online vesz részt')}:</label></td>
                    <td colspan="3"><input id="OnlineEdit" name="online" type="checkbox"{if ($egyed.online)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="VarolistasEdit">{at('Várólistás')}:</label></td>
                    <td colspan="3"><input id="VarolistasEdit" name="varolistas" type="checkbox"{if ($egyed.varolistas)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                    <td colspan="3"><select id="FizmodEdit" name="fizmod">
                            <option value="">{at('válasszon')}</option>
                            {foreach $fizmodlist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                    <td colspan="3"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="3" cols="60">{$egyed.megjegyzes}</textarea></td>
                </tr>
                </tbody>
            </table>
            {if ($egyed.kerdoivvalaszok)}
                <fieldset class="mattkarb-doboz">
                    <legend>{at('Kérdőív válaszai')}</legend>
                    <table>
                        <tbody>
                        {foreach $egyed.kerdoivvalaszok as $_v}
                            <tr>
                                <td>{$_v.kerdes|escape}</td>
                                <td><b>{$_v.valasz|escape}</b></td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </fieldset>
            {/if}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
