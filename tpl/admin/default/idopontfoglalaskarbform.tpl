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
            {mezocsoport}
                {if ($oper == 'add')}
                    {mezo cimke="Időpont" for="IdopontEdit" szeles=true}
                        <select id="IdopontEdit" name="idopont" class="js-idopontedit" required="required" autofocus>
                            <option value="">{at('válasszon')}</option>
                            {foreach $idopontlist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}
                                        data-ismetlodo="{$_d.ismetlodo}" data-nap="{$_d.nap}"
                                        data-datum="{$_d.datum}">{$_d.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Alkalom napja" for="DatumEdit"}
                        <input id="DatumEdit" name="datum" data-datum="{$egyed.datum}" required="required">
                    {/mezo}
                    {mezo cimke="Partner" for="PartnerEdit" szeles=true}
                        <div class="mattkarb-mezogomb">
                            {if ($setup.partnerautocomplete|default:0)}
                                <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete"
                                    value="{$egyed.partnernev}" size="60">
                                <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partnerid}">
                                <input class="js-ujpartnercb" type="checkbox">{at('Új')}
                            {else}
                                <select id="PartnerEdit" name="partner" class="js-partnerid">
                                    <option value="">{at('válasszon')}</option>
                                    <option value="-1">{at('Új felvitel')}</option>
                                    {foreach $partnerlist as $_d}
                                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </div>
                    {/mezo}
                    {mezo cimke="Név" for="PartnernevEdit" ujsor=true}
                        <input id="PartnernevEdit" name="partnernev" value="{$egyed.partnernev}">
                    {/mezo}
                    {mezo cimke="Telefon" for="PartnertelefonEdit"}
                        <input id="PartnertelefonEdit" name="partnertelefon" value="{$egyed.partnertelefon}">
                    {/mezo}
                    {mezo cimke="Email" for="PartneremailEdit" szeles=true}
                        <input id="PartneremailEdit" name="partneremail" type="email" size="60"
                            value="{$egyed.partneremail}">
                    {/mezo}
                {elseif ($egyed.idoponttipus == 'rendezveny')}
                    {* rendezvény jelentkezésnél az időpont/partner/dátum eddig is szerkeszthető volt *}
                    {mezo cimke="Időpont" for="IdopontEdit" szeles=true}
                        <select id="IdopontEdit" name="idopont" class="js-idopontedit">
                            <option value="">{at('válasszon')}</option>
                            {foreach $idopontlist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}
                                        data-ismetlodo="{$_d.ismetlodo}" data-nap="{$_d.nap}"
                                        data-datum="{$_d.datum}">{$_d.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Alkalom napja" for="DatumEdit"}
                        <input id="DatumEdit" name="datum" data-datum="{$egyed.datum}">
                    {/mezo}
                    {mezo cimke="Partner" for="PartnerEdit" szeles=true}
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
                    {/mezo}
                    {mezo cimke="Email" ujsor=true}
                        {$egyed.partneremail}
                    {/mezo}
                    {mezo cimke="Telefon"}
                        {$egyed.partnertelefon}
                    {/mezo}
                    {mezo cimke="Jelentkezés ideje"}
                        {$egyed.foglalasido}
                    {/mezo}
                {else}
                    {mezo cimke="Időpont"}
                        {$egyed.datum} {$egyed.napnev} {$egyed.idopontkezdet}
                    {/mezo}
                    {mezo cimke="Téma"}
                        {$egyed.idoponttemanev}
                    {/mezo}
                    {mezo cimke="Tanár"}
                        {$egyed.idopontdolgozonev}
                    {/mezo}
                    {mezo cimke="Helyszín"}
                        {$egyed.idoponthelyszinnev}
                    {/mezo}
                    {mezo cimke="Foglaló"}
                        {$egyed.partnernev}
                    {/mezo}
                    {mezo cimke="Email"}
                        {$egyed.partneremail}
                    {/mezo}
                    {mezo cimke="Telefon"}
                        {$egyed.partnertelefon}
                    {/mezo}
                    {mezo cimke="Foglalás ideje"}
                        {$egyed.foglalasido}
                    {/mezo}
                {/if}
                {mezo cimke="Online vesz részt" for="OnlineEdit"}
                    <input id="OnlineEdit" name="online" type="checkbox"{if ($egyed.online)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Várólistás" for="VarolistasEdit"}
                    <input id="VarolistasEdit" name="varolistas" type="checkbox"{if ($egyed.varolistas)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Fizetési mód" for="FizmodEdit" szeles=true}
                    <select id="FizmodEdit" name="fizmod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fizmodlist as $_d}
                            <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Megjegyzés" for="MegjegyzesEdit" szeles=true}
                    <textarea id="MegjegyzesEdit" name="megjegyzes" rows="3" cols="60">{$egyed.megjegyzes}</textarea>
                {/mezo}
            {/mezocsoport}
            {if ($egyed.kerdoivvalaszok)}
                <fieldset class="mattkarb-doboz">
                    <legend>{at('Kérdőív válaszai')}</legend>
                    <table>
                        <tbody>
                        {foreach $egyed.kerdoivvalaszok as $_v}
                            <tr>
                                <td>{$_v.kerdes}</td>
                                <td><b>{$_v.valasz}</b></td>
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
