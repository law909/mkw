{if ($nostorno)}
    <h3>A számla még nincs beküldve a NAV-hoz, nem stornózhatja! Várja meg a beküldés eredményét.</h3>
{elseif ($noinherit|default:false)}
    <h3>{at('Rontott vagy stornózott bizonylatból nem képezhető újabb bizonylat.')}</h3>
{elseif ($bekuldetlentipus|default:false)}
    <h3>{at('Amíg van beküldetlen')} {$bekuldetlentipus}, {at('nem képezhető újabb.')}</h3>
{elseif ($nosource|default:false)}
    <h3>{at('A forrás bizonylat nem található, előbb mentse el.')}</h3>
{else}
    <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}" data-irany="{$egyed.irany|default:0}">
        <h3>{$pagetitle} - {$egyed.id}{if ($egyed.parentid|default)} ({$egyed.parentid}){/if}</h3>
        {if ($readonly|default)}
            {if ($showmunkalapadatok && $egyed.munkalapkiszamlazva)}
                <div class="mattkarb-readonly-uzenet">{at('A munkalap ki van számlázva, ezért már nem módosítható.')}</div>
            {else}
                <div class="mattkarb-readonly-uzenet">{at('A bizonylat ki van nyomtatva, ezért már nem módosítható.')}</div>
            {/if}
        {/if}
    </div>
    <form id="mattkarb-form" method="post" action="{$formaction}" data-lastname="{$loggedinuser['lastname']}"
          data-funnypartnermessage="{$maintheme=='superzoneb2b'}" data-tarsbiztipus="{$tarsbiztipus}"
          data-tulajaam="{if ($tulajalanyiafamentes)}1{else}0{/if}" data-magyarorszagid="{$magyarorszagid}"
          data-unasdefaulttermek="{$unasdefaulttermek|default}"
          data-defaulttermek="{$defaulttermek|default}"
          data-tipuspenztmozgat="{if ($egyed.tipuspenztmozgat|default)}1{else}0{/if}"
          data-eredetifizmod="{$egyed.fizmod}" data-eredetipenztmozgat="{if ($egyed.penztmozgat)}1{else}0{/if}"
          data-readonly="{if ($readonly|default)}1{else}0{/if}"
          data-nyomtatasikerdes="{if ($nyomtatasikerdes|default)}1{else}0{/if}"
          data-nyomtatni="{if ($nyomtatni|default)}1{else}0{/if}" data-sendemail="{if ($sendemail|default)}1{else}0{/if}"
          data-editprinted="{if ($tipuseditprinted|default)}1{else}0{/if}">
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
                <li><a href="#DokTab">{at('Dokumentumok')}</a></li>
            </ul>
            <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
                {if ($showmunkalapadatok)}
                    {* A jármű a tételekével azonos termékválasztóval, VAGY az egyedi azonosítójával
                       választható ki. Az azonosító az erősebb: kitöltve a mentés abból oldja fel a
                       járművet, mert az konkrét példányt jelöl, és a partner is arról a bizonylatról jön.
                       Bizonylattétel nélküli (külső) azonosító is beírható, akkor a jármű kézzel választható. *}
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Munkalap adatok')}</legend>
                        <table class="mattkarb-racs mattkarb-racs-kitolt">
                            <tbody>
                            <tr>
                                <td class="mattable-important"><label for="MunkalapTermekEdit">{at('Jármű')}:</label></td>
                                {if ($setup.termekautocomplete)}
                                    <td>
                                        <input id="MunkalapTermekEdit" type="text" name="munkalaptermeknev" size="60" autocomplete="off"
                                               class="js-munkalaptermekselect termekselect mattable-important" value="{$egyed.munkalaptermeknev}">
                                        <input class="js-munkalaptermekid" name="munkalaptermek" type="hidden" value="{$egyed.munkalaptermek}">
                                    </td>
                                {else}
                                    <td>
                                        <select id="MunkalapTermekEdit" name="munkalaptermek" class="js-munkalaptermekid js-munkalaptermekselectreal mattable-important">
                                            <option value="">{at('válasszon')}</option>
                                            {foreach $egyed.munkalaptermeklist as $_mt}
                                                <option value="{$_mt.id}"{if ($_mt.selected)} selected="selected"{/if}>{$_mt.caption}</option>
                                            {/foreach}
                                        </select>
                                    </td>
                                {/if}
                                <td><label for="MunkalapValtozatEdit">{at('Változat')}:</label></td>
                                <td><select id="MunkalapValtozatEdit" name="munkalaptermekvaltozat" class="js-munkalapvaltozat">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $egyed.munkalapvaltozatlist as $_mv}
                                            <option value="{$_mv.id}"{if ($_mv.selected)} selected="selected"{/if}>{$_mv.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            <tr>
                                <td><label for="MunkalapEgyediazonositoEdit">{at('Egyedi azonosító')}:</label></td>
                                <td colspan="3"><input id="MunkalapEgyediazonositoEdit" name="munkalapegyediazonosito" type="text" size="30" maxlength="255"
                                                       value="{$egyed.munkalapegyediazonosito}" class="js-munkalapazonosito" autocomplete="off"
                                                       data-kulso="{if ($egyed.munkalapazonositokulso)}1{else}0{/if}">
                                    <span class="js-munkalapazonositouzenet"></span></td>
                            </tr>
                            <tr>
                                <td><label for="MunkalapKmoraallasEdit">{at('Km óra állás')}:</label></td>
                                <td><input id="MunkalapKmoraallasEdit" name="munkalapkmoraallas" type="number" step="1" min="0" size="10"
                                           value="{$egyed.munkalapkmoraallas}"></td>
                                <td><label for="MunkalapKovetkezoSzervizEdit">{at('Következő szerviz')}:</label></td>
                                <td>
                                    <div class="mattkarb-mezogomb">
                                        <input id="MunkalapKovetkezoSzervizEdit" name="munkalapkovetkezoszerviz" type="text" size="12"
                                               data-datum="{$egyed.munkalapkovetkezoszervizstr}">
                                        <label for="MunkalapKovetkezoSzervizKmEdit">{at('km')}:</label>
                                        <input id="MunkalapKovetkezoSzervizKmEdit" name="munkalapkovetkezoszervizkm" type="number" step="1" min="0"
                                               size="10" value="{$egyed.munkalapkovetkezoszervizkm}">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="MunkalapHibaleirasEdit">{at('Hiba leírása')}:</label></td>
                                <td colspan="3"><textarea id="MunkalapHibaleirasEdit" name="munkalaphibaleiras" rows="3" cols="100">{$egyed.munkalaphibaleiras}</textarea></td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                {/if}
                <table class="mattkarb-racs mattkarb-racs-4 mattkarb-racs-kitolt">
                    <tbody>
                    {if ($showforditottadozas)}
                        <tr>
                            <td><label for="ForditottadozasEdit">Fordított adózás:</label></td>
                            <td><input id="ForditottadozasEdit" type="checkbox" name="forditottadozas"{if ($egyed.forditottadozas)} checked{/if}></td>
                        </tr>
                    {/if}
                    {if ($showrendszeres)}
                        <tr>
                            <td><label for="RendszeresEdit">{at('Rendszeres')}:</label></td>
                            <td><input id="RendszeresEdit" type="checkbox" name="rendszeres"{if ($egyed.rendszeres)} checked{/if}></td>
                        </tr>
                    {/if}
                    {if ($showbizonylatstatuszeditor)}
                        <tr>
                            <td class="mattable-important"><label for="BizonylatStatuszEdit">Státusz:</label></td>
                            <td><select id="BizonylatStatuszEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $egyed.bizonylatstatuszlist as $_role}
                                        <option value="{$_role.id}" data-vanemailtemplate="{if ($_role.vanemailtemplate)}1{else}0{/if}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select></td>
                            <td><label for="BizonylatStatuszErtesitoEdit">Értesítés kell:</label></td>
                            <td><input id="BizonylatStatuszErtesitoEdit" type="checkbox" name="bizonylatstatuszertesito"></td>
                        </tr>
                    {/if}
                    {if ($setup.fakekintlevoseg)}
                        <tr>
                            <td><label for="FakekintlevosegEdit">{at('Fake kintlévőség')}:</label></td>
                            <td><input id="FakekintlevosegEdit" type="checkbox" name="fakekintlevoseg"{if ($egyed.fakekintlevoseg)} checked{/if}></td>
                            <td><label for="FakekifizetveEdit">{at('Fake kifizetve')}:</label></td>
                            <td><input id="FakekifizetveEdit" type="checkbox" name="fakekifizetve"{if ($egyed.fakekifizetve)} checked{/if}></td>
                            <td><label for="FakeKifizetesdatumEdit">{at('Fake kifiz.dátum')}:</label></td>
                            <td><input id="FakeKifizetesdatumEdit" name="fakekifizetesdatum" type="text" size="12" data-datum="{$egyed.fakekifizetesdatumstr}">
                            </td>
                        </tr>
                    {/if}
                    {if ($showfelhasznalo)}
                        <tr>
                            <td class="mattable-important"><label for="DolgozoEdit">{at('Dolgozó')}:</label></td>
                            <td colspan="7"><select id="DolgozoEdit" name="felhasznalo" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $felhasznalolist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/if}
                    <tr class="mattkarb-szakaszsor">
                        <td colspan="8"><div class="mattkarb-szakaszcim">{at('Partner')}</div></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="PartnerEdit">{at('Partner')}:</label></td>
                        {if ($setup.partnerautocomplete)}
                            <td colspan="7">
                                <div class="mattkarb-mezogomb">
                                    <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important"
                                           value="{$egyed.partnernev}" size=90
                                           {if (!$showmunkalapadatok)}autofocus{/if}{if ($egyed.partnerafa)} data-afa="{$egyed.partnerafa}" data-afakulcs="{$egyed.partnerafakulcs}"{/if}>
                                    <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                                    <label class="mattkarb-pipacimke"><input class="js-ujpartnercb" type="checkbox">{at('Új')}</label>
                                </div>
                            </td>
                        {else}
                            <td colspan="7"><select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required"
                                                    {if (!$showmunkalapadatok)}autofocus{/if}{if ($egyed.partnerafa)} data-afa="{$egyed.partnerafa}" data-afakulcs="{$egyed.partnerafakulcs}"{/if}>
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
                            <input id="NevEdit" name="partnernev" value="{$egyed.partnernev}">
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
                            <div class="mattkarb-mezogomb">
                                <input id="IrszamEdit" name="partnerirszam" value="{$egyed.partnerirszam}" size="6" maxlength="10">
                                <input id="VarosEdit" name="partnervaros" value="{$egyed.partnervaros}" size="20" maxlength="40">
                                <input id="UtcaEdit" name="partnerutca" value="{$egyed.partnerutca}" size="40" maxlength="60">
                                <input id="HazszamEdit" name="partnerhazszam" value="{$egyed.partnerhazszam}" size="40" maxlength="40">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="OrszagEdit">{at('Ország')}:</label></td>
                        <td><select id="OrszagEdit" name="partnerorszag">
                                <option value="">{at('válasszon')}</option>
                                {foreach $orszaglist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><label for="SzamlatipusEdit">{at('Származás')}:</label></td>
                        <td><select id="SzamlatipusEdit" name="partnerszamlatipus" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $szamlatipuslist as $_szt}
                                    <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                                {/foreach}
                            </select></td>
                        <td><label for="VatstatusEdit">{at('NAV státusz')}:</label></td>
                        <td><select id="VatstatusEdit" name="partnervatstatus" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $vatstatuslist as $_szt}
                                    <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="AdoszamEdit" class="mattable-important">{at('Adószám')}:</label></td>
                        <td>
                            <div class="mattkarb-mezogomb">
                                <input id="AdoszamEdit" name="partneradoszam" value="{$egyed.partneradoszam}">
                                <button class="js-querytaxpayer" style="display: none;">NAV</button>
                            </div>
                        </td>
                        <td><label for="EUAdoszamEdit">{at('EU adószám')}:</label></td>
                        <td>
                            <input id="EUAdoszamEdit" name="partnereuadoszam" value="{$egyed.partnereuadoszam}">
                        </td>
                        <td><label for="ThirdAdoszamEdit">{at('Harmadik ország adószáma')}:</label></td>
                        <td>
                            <input id="ThirdAdoszamEdit" name="partnerthirdadoszam" value="{$egyed.partnerthirdadoszam}">
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
                            <span class="mattkarb-megjegyzes">{at('Létező partner emailjét nem írja felül, csak ha nála üres.')}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="TelephelyEdit">{at('Telephely')}:</label></td>
                        <td colspan="7"><select id="TelephelyEdit" name="telephely">
                                <option value="">{at('válasszon')}</option>
                                {foreach $telephelylist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-szallnev="{$_mk.szallnev}" data-irszam="{$_mk.irszam}" data-varos="{$_mk.varos}" data-utca="{$_mk.utca}" data-orszag="{$_mk.orszag}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                            {if ($egyed.telephelynev && !$egyed.telephely)}<span class="mattable-important">{at('Mentett telephely')}: {$egyed.telephelynev}</span>{/if}
                        </td>
                    </tr>
                    <tr class="mattkarb-szakaszsor">
                        <td colspan="8"><div class="mattkarb-szakaszcim">{at('Szállítás')}</div></td>
                    </tr>
                    {if ($showszallitasicim)}
                        <tr>
                            <td><label for="SzallnevEdit">{at('Szállítási név')}:</label></td>
                            <td colspan="7">
                                <input id="SzallnevEdit" name="szallnev" value="{$egyed.szallnev}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="SzallirszamEdit">{at('Szállítási cím')}:</label></td>
                            <td colspan="7">
                                <div class="mattkarb-mezogomb">
                                    <input id="SzallirszamEdit" name="szallirszam" value="{$egyed.szallirszam}" size="6" maxlength="10">
                                    <input name="szallvaros" value="{$egyed.szallvaros}" size="20" maxlength="40">
                                    <input name="szallutca" value="{$egyed.szallutca}" size="40" maxlength="60">
                                    <input name="szallhazszam" value="{$egyed.szallhazszam}" size="40" maxlength="40">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="SzallOrszagEdit">{at('Szállítási ország')}:</label></td>
                            <td><select id="SzallOrszagEdit" name="partnerszallorszag">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $szallorszaglist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td><label for="RaktarEdit">{at('Raktár')}:</label></td>
                        <td colspan="7"><select id="RaktarEdit" name="raktar" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $raktarlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="mattkarb-szakaszsor">
                        <td colspan="8"><div class="mattkarb-szakaszcim">{at('Fizetés')}</div></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                        <td><select id="FizmodEdit" name="fizmod" class="mattable-important" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $fizmodlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-fizhatido="{$_mk.fizhatido}"
                                            data-bank="{$_mk.bank}"
                                            data-keszpenz="{$_mk.keszpenz}"
                                            data-nincspenzmozgas="{if ($_mk.nincspenzmozgas)}1{else}0{/if}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                            {if ($egyed.unasfizetesstatusz)}
                                <span class="{if ($egyed.unasfizetve)}greentext{else}redtext{/if}">UNAS: {$egyed.unasfizetesstatusznev}</span>
                            {/if}
                        </td>
                        <td class="mattable-important"><label for="SzallitasimodEdit">{at('Szállítási mód')}:</label></td>
                        <td><select id="SzallitasimodEdit" name="szallitasimod"
                                    class="mattable-important"{if ($maintheme=='mkwcansas' || $maintheme=='superzoneb2b')} required="required"{/if}>
                                <option value="">{at('válasszon')}</option>
                                {foreach $szallitasimodlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-terminaltipus="{$_mk.terminaltipus}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="js-csomagpontrow" data-terminaltipus="{$csomagpontterminaltipus}"{if ($csomagpontterminaltipus == '')} style="display: none;"{/if}>
                        <td><label for="CsomagTerminalCsoportEdit">{at('Csomagpont')}:</label></td>
                        <td colspan="7">
                            <div class="mattkarb-mezogomb">
                                <select id="CsomagTerminalCsoportEdit">
                                    <option value="">{at('város')}</option>
                                    {foreach $csomagterminalcsoportlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                                <select id="CsomagTerminalEdit" name="csomagterminal">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $csomagterminallist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </td>
                    </tr>
                    {if ($showpenztar)}
                        <tr class="js-penztarrow" style="display: none;">
                            <td><label for="PenztarEdit">{at('Pénztár')}:</label></td>
                            <td colspan="7"><select id="PenztarEdit" name="penztar">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $penztarlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td><label for="PenztmozgatEdit">{at('Kintlévőséget/tartozást képez')}:</label></td>
                        <td><input id="PenztmozgatEdit" type="checkbox" name="penztmozgat"{if ($egyed.penztmozgat)} checked{/if}>
                            <input id="RontkapcsolodopenzmozgasEdit" type="hidden" name="rontkapcsolodopenzmozgas" value="0">
                            <input id="IgazitpenzmozgasosszegetEdit" type="hidden" name="igazitpenzmozgasosszeget" value="0">
                            <input id="StornopenzmozgasEdit" type="hidden" name="stornopenzmozgas" value="0"></td>
                    </tr>
                    {if isset($tarsbizonylatlist)}
                        <tr>
                            <td><label for="TarsbizonylatEdit">{at('Kapcsolódó megrendelés')}:</label></td>
                            <td colspan="3"><select id="TarsbizonylatEdit" name="tarsbizonylat">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $tarsbizonylatlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/if}
                    {if ($maintheme === 'darshan')}
                        <tr class="szepkartya">
                            <td><label for="SZEPKartyaTipusEdit">{at('Kártya típusa')}:</label></td>
                            <td>
                                <select id="SZEPKartyaTipusEdit" name="szepkartyatipus">
                                    <option value="">{at('válassz')}</option>
                                    <option value="1"{if ($egyed.szepkartyatipus == 1)} selected="selected"{/if}>{at('OTP')}</option>
                                    <option value="2"{if ($egyed.szepkartyatipus == 2)} selected="selected"{/if}>{at('MKB')}</option>
                                    <option value="3"{if ($egyed.szepkartyatipus == 3)} selected="selected"{/if}>{at('K&H')}</option>
                                </select>
                            </td>
                            <td><label for="SZEPKartyaSzamEdit">{at('Kártya száma')}:</label></td>
                            <td><input id="SZEPKartyaSzamEdit" name="szepkartyaszam" type="text" value="{$egyed.szepkartyaszam}"></td>
                        </tr>
                        <tr class="szepkartya">
                            <td><label for="SZEPKartyaNevEdit">{at('Kártyára írt név')}:</label></td>
                            <td><input id="SZEPKartyaNevEdit" name="szepkartyanev" type="text" value="{$egyed.szepkartyanev}"></td>
                            <td><label for="SZEPKartyaErvenyessegEdit">{at('Kártya érvényessége')}:</label></td>
                            <td><input id="SZEPKartyaErvenyessegEdit" name="szepkartyaervenyesseg" type="text" size="12"
                                       data-datum="{$egyed.szepkartyaervenyessegstr}"></td>
                        </tr>
                    {/if}
                    <tr>
                        <td class="mattable-important"><label for="UzletkotoEdit">{at('Üzletkötő')}:</label></td>
                        <td><select id="UzletkotoEdit" name="uzletkoto" class="mattable-important">
                                <option value="">{at('válasszon')}</option>
                                {foreach $uzletkotolist as $_mk}
                                    <option value="{$_mk.id}" data-jutalek="{$_mk.jutalek}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><label for="UKJutalekEdit">{at('Jutalék')} %:</label></td>
                        <td><input id="UKJutalekEdit" name="uzletkotojutalek" type="number" step="any" size="5" value="{$egyed.uzletkotojutalek}"></td>
                    </tr>
                    {if (haveJog(90))}
                        <tr>
                            <td class="mattable-important"><label for="BelsoUzletkotoEdit">{at('Belső üzletkötő')}:</label></td>
                            <td><select id="BelsoUzletkotoEdit" name="belsouzletkoto" class="mattable-important">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $belsouzletkotolist as $_mk}
                                        <option value="{$_mk.id}" data-jutalek="{$_mk.jutalek}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td><label for="BelsoUKJutalekEdit">{at('Jutalék')} %:</label></td>
                            <td><input id="BelsoUKJutalekEdit" name="belsouzletkotojutalek" type="number" step="any" size="5"
                                       value="{$egyed.belsouzletkotojutalek}"></td>
                        </tr>
                    {/if}
                    <tr class="mattkarb-szakaszsor">
                        <td colspan="8"><div class="mattkarb-szakaszcim">{at('Dátumok és pénznem')}</div></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="KeltEdit">{at('Kelt')}:</label></td>
                        <td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required">
                        </td>
                        {if ($showteljesites)}
                            <td class="mattable-important"><label for="TeljesitesEdit">{at('Teljesítés')}:</label></td>
                            <td><input id="TeljesitesEdit" name="teljesites" type="text" size="12" data-datum="{$egyed.teljesitesstr}"
                                       class="mattable-important" required="required"></td>
                        {/if}
                        {if ($showesedekesseg)}
                            <td class="mattable-important"><label for="EsedekessegEdit">{at('Esedékesség')}:</label></td>
                            <td><input id="EsedekessegEdit" name="esedekesseg" type="text" size="12" data-datum="{$egyed.esedekessegstr}"
                                       class="mattable-important" required="required"></td>
                        {/if}
                        {if ($showbeerkezes)}
                            <td class="mattable-important"><label for="BeerkezesEdit">{at('Beérkezés')}:</label></td>
                            <td><input id="BeerkezesEdit" name="beerkezes" type="text" size="12" data-datum="{$egyed.beerkezesstr}"
                                       class="mattable-important"></td>
                        {/if}
                    </tr>
                    <tr>
                        {if ($showhatarido)}
                            <td class="mattable-important"><label for="HataridoEdit">{at('Határidő')}:</label></td>
                            <td><input id="HataridoEdit" name="hatarido" type="text" size="12" data-datum="{$egyed.hataridostr}" class="mattable-important">
                            </td>
                            <td class="mattable-important"><label for="ShipDateEdit">{at('Feladás')}:</label></td>
                            <td><input id="ShipDateEdit" name="shipdate" type="text" size="12" data-datum="{$egyed.shipdatestr}"
                                       class="mattable-important">
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                        <td><select id="ValutanemEdit" name="valutanem" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $valutanemlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}
                                            data-bankszamla="{$_mk.bankszamla}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><label for="ArfolyamEdit">{at('Árfolyam')}:</label></td>
                        <td><input id="ArfolyamEdit" name="arfolyam" type="number" step="any" size="5" value="{$egyed.arfolyam}" required="required"></td>
                        <td><label for="BankszamlaEdit">{at('Bankszámla')}:</label></td>
                        <td colspan="3"><select id="BankszamlaEdit" name="bankszamla">
                                <option value="">{at('válasszon')}</option>
                                {foreach $bankszamlalist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        {if ($setup.multilang)}
                            <td><label for="BizonylatnyelvEdit">{at('Adatok nyelve')}:</label></td>
                            <td><select id="BizonylatnyelvEdit" name="bizonylatnyelv">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $bizonylatnyelvlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        {/if}
                        <td><label for="WebshopnumEdit">{at('Webshop')}:</label></td>
                        <td><select id="WebshopnumEdit" name="webshopnum">
                                {foreach $webshoplist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><label for="ReportfileEdit">{at('Nyomtatási forma')}:</label></td>
                        <td><select id="ReportfileEdit" name="reportfile">
                                <option value="">{at('válasszon')}</option>
                                {foreach $reportfilelist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="mattkarb-szakaszsor">
                        <td colspan="8"><div class="mattkarb-szakaszcim">{at('Egyéb')}</div></td>
                    </tr>
                    {if ($showerbizonylatszam)}
                        <tr>
                            <td><label for="ErbizonylatszamEdit">{at('Eredeti biz.szám')}:</label></td>
                            <td><input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}"></td>
                        </tr>
                    {/if}
                    {if ($showkupon)}
                        <tr>
                            <td><label for="KuponEdit">{at('Kupon')}:</label></td>
                            <td><input id="KuponEdit" name="kupon" type="text" value="{$egyed.kupon}"></td>
                        </tr>
                    {/if}
                    {if ($showfuvarlevelszam)}
                        <tr>
                            <td><label for="FuvarlevelszamEdit">{at('Fuvarlevélszám')}:</label></td>
                            <td colspan="7"><textarea id="FuvarlevelszamEdit" name="fuvarlevelszam" rows="1" cols="100">{$egyed.fuvarlevelszam}</textarea></td>
                        </tr>
                    {/if}
                    <tr>
                        <td><label for="CsomagcountEdit">{at('Csomagok száma')}:</label></td>
                        <td colspan="7"><input id="CsomagcountEdit" name="csomagcount" type="number" step="any" size="5" value="{$egyed.csomagcount}"></td>
                    </tr>
                    <tr>
                        <td><label for="SzallitasiktgkellEdit">{at('Szállítási költséget kell számolni')}:</label></td>
                        <td><input id="SzallitasiktgkellEdit" name="szallitasiktgkell" type="checkbox"></td>
                    </tr>
                    <tr>
                        <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                        <td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
                    </tr>
                    <tr>
                        <td><label for="BelsomegjegyzesEdit">{at('Belső megjegyzés')}:</label></td>
                        <td colspan="7"><textarea id="BelsomegjegyzesEdit" name="belsomegjegyzes" rows="1" cols="100">{$egyed.belsomegjegyzes}</textarea></td>
                    </tr>
                    {if ($showuzenet)}
                        <tr>
                            <td><label for="WebshopmessageEdit">{at('Üzenet a webáruháznak')}:</label></td>
                            <td colspan="7"><textarea id="WebshopmessageEdit" name="webshopmessage" rows="1" cols="100">{$egyed.webshopmessage}</textarea></td>
                        </tr>
                        <tr>
                            <td><label for="CouriermessageEdit">{at('Üzenet a futárnak')}:</label></td>
                            <td colspan="7"><textarea id="CouriermessageEdit" name="couriermessage" rows="1" cols="100">{$egyed.couriermessage}</textarea></td>
                        </tr>
                    {/if}
                    </tbody>
                </table>
                {if ($pos|default)}
                    {* Vonalkódos tételfelvitel: a fej fölötte változatlanul a klasszikus. *}
                    <div class="js-bizonylatpos bizonylatpos">
                        <table class="bizonylatpos-tetelek ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <thead>
                            <tr>
                                <th>{at('Cikkszám')}</th>
                                <th>{at('Termék')}</th>
                                <th>{at('Raktáron')}</th>
                                <th>{at('Mennyiség')}</th>
                                <th>{at('Kedvezmény')} %</th>
                                <th>{at('Bruttó egységár')}</th>
                                <th>{at('Bruttó')}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="js-postetelek">{foreach $postetelek|default:[] as $_postetel}{$_postetel}{/foreach}</tbody>
                        </table>
                        {* a kereső a tételek alatt, mint a bolti eladáson – a felvett sorok fölé nőnek *}
                        <div class="bizonylatpos-keresosor">
                            <label for="BizonylatposVonalkodEdit">{at('Vonalkód / keresés')}:</label>
                            <input id="BizonylatposVonalkodEdit" class="js-poskereso" type="text" autocomplete="off">
                            <span class="js-poskereshiba bizonylatpos-hiba"></span>
                        </div>
                        <div class="js-posvaltozatvalaszto bizonylatpos-valtozatvalaszto"></div>
                    </div>
                {else}
                    {if (!$quick)}
                        <div class="bizonylattetel-importsor">
                            {if ($showelolegbeszamitas)}
                                <a class="js-elolegbeszamitasbutton js-karbmodosito" href="#">{at('Előleg beszámítása')}</a>
                            {/if}
                            <a class="js-tetelimportbutton js-karbmodosito" href="#">{at('Tételek betöltése xlsx-ből')}</a>
                            {if ($maintheme === 'superzoneb2b')}
                                <a class="js-fcmotoimportbutton js-karbmodosito" href="#">{at('FC-Moto rendelés')}</a>
                            {/if}
                            {if ($maintheme === 'galad')}
                                <a class="js-oxfordimportbutton js-karbmodosito" href="#">{at('Oxford')}</a>
                            {/if}
                            <span class="js-tetelimportuzenet"></span>
                        </div>
                    {/if}
                    <div>
                        {foreach $egyed.tetelek as $tetel}
                            {include 'bizonylattetelkarb.tpl'}
                        {/foreach}
                        <a class="{if ($quick)}js-quicktetelnewbutton{else}js-tetelnewbutton{/if}" href="#" title="{at('Új')}"><span
                                class="ui-icon ui-icon-circle-plus"></span></a>
                    </div>
                {/if}
                <div class="js-bizonylatosszesito ui-widget-content ui-corner-all bizonylatosszesito">
                    <div class="bizonylatosszesito-cim">{at('Összesen')}</div>
                    <div class="bizonylatosszesito-ertek bizonylatosszesito-keskeny"><span class="bizonylatosszesito-cimke">{at('Tételszám')}</span><span class="js-tetelszamsum"></span></div>
                    <div class="bizonylatosszesito-ertek bizonylatosszesito-keskeny"><span class="bizonylatosszesito-cimke">{at('Mennyiség')}</span><span class="js-mennyisegsum"></span></div>
                    <div class="bizonylatosszesito-ertek"><span class="bizonylatosszesito-cimke">{at('Nettó')}</span><span class="js-nettosum"></span></div>
                    <div class="bizonylatosszesito-ertek bizonylatosszesito-fo"><span class="bizonylatosszesito-cimke">{at('Bruttó')}</span><span class="js-bruttosum"></span></div>
                    {if ($showvalutanem)}
                        <div class="bizonylatosszesito-ertek"><span class="bizonylatosszesito-cimke">{at('Nettó HUF')}</span><span class="js-nettohufsum"></span></div>
                        <div class="bizonylatosszesito-ertek"><span class="bizonylatosszesito-cimke">{at('Bruttó HUF')}</span><span class="js-bruttohufsum"></span></div>
                    {/if}
                </div>
            </div>
            <div id="DokTab" class="mattkarb-page" data-visible="visible">
                {include 'dokumentumfeltoltes.tpl'}
                {foreach $egyed.dokok as $dok}
                    {include 'dokumentumtarkarb.tpl'}
                {/foreach}
                <a class="js-doknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        </div>
        <input name="quick" type="hidden" value="{$quick}">
        {* no name: only the item check reads it, it is not saved *}
        <input id="BizTetelkotelezo" type="hidden" value="{if ($tetelkotelezo|default:true)}1{else}0{/if}">
        <input name="pos" type="hidden" value="{if ($pos|default)}1{else}0{/if}">
        <input name="oper" type="hidden" value="{$oper}">
        <input name="id" type="hidden" value="{$egyed.id}">
        <input id="AfaellenorzesnemkellEdit" name="afaellenorzesnemkell" type="hidden" value="{if ($egyed.afaellenorzesnemkell|default)}1{else}0{/if}">
        {if ($egyed.parentid|default)}
            <input name="parentid" type="hidden" value="{$egyed.parentid}">
        {/if}
        {if ($egyed.stornotip|default)}
            <input name="stornotip" type="hidden" value="{$egyed.stornotip}">
        {/if}
        <div class="mattkarb-footer">
            {if ($egyed.nemrossz)}
                <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
            {/if}
            <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
            {if (!$quick && !($readonly|default))}
                <a class="js-elonezet" href="#" data-biztipus="{$biztipusid}" title="{at('Előnézet')}">{at('Előnézet')}</a>
            {/if}
            {if ($oper == 'edit' && $egyed.id && $egyed.nemrossz)}
                <a class="js-tetelellenorzes" href="/admin/bizonylatellenorzes/view?id={$egyed.id|escape:'url'}" target="_blank"
                   title="{at('Tételek ellenőrzése')}">{at('Tételek ellenőrzése')}</a>
            {/if}
            {if ($oper == 'edit' && !$egyed.hibas && $egyed.nemrossz)}
                {if ($showszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="szamlafej" data-oper="inherit" title="{at('Számla')}"
                    >{at('Számla')}</a>
                {/if}
                {if ($showkeziszamlabutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="keziszamlafej" data-oper="inherit"
                       title="{at('Kézi számla')}">{at('Kézi számla')}</a>
                {/if}
                {if ($showkivetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="kivetfej" data-oper="inherit" title="{at('Kivét')}"
                    >{at('Kivét')}</a>
                {/if}
                {if ($showbevetbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="bevetfej" data-oper="inherit" title="{at('Bevét')}"
                    >{at('Bevét')}</a>
                {/if}
                {if ($showszallmegrbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="szallmegrfej" data-oper="inherit"
                       title="{at('Szállítói megrendelés')}">{at('Szállítói megrendelés')}</a>
                {/if}
                {if ($showboltieladasbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="boltieladasfej" data-oper="inherit"
                       title="{at('Bolti eladás')}">{at('Bolti eladás')}</a>
                {/if}
                {if ($showautokiserobutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="autokiserofej" data-oper="inherit"
                       title="{at('Gépjármű kísérő')}">{at('Gépjármű kísérő')}</a>
                {/if}
                {if ($showmegrendelesbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="megrendelesfej" data-oper="inherit"
                       title="{at('Megrendelés')}">{at('Megrendelés')}</a>
                {/if}
                {if ($showb2bmegrendelesbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="b2brendelesfej" data-oper="inherit"
                       title="{at('B2B rendelés')}">{at('B2B rendelés')}</a>
                {/if}
                {if ($showwebshopmegrendelesbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="webshopbizfej" data-oper="inherit"
                       title="{at('Webshop rendelés')}">{at('Webshop rendelés')}</a>
                {/if}
                {if ($showelolegbutton)}
                    <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="elolegszamlafej" data-oper="inherit"
                       title="{at('Előlegszámla')}">{at('Előlegszámla')}</a>
                {/if}
            {/if}
        </div>
    </form>
{/if}