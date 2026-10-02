<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"
     {if ($tetel.koltsegtermek|default)}data-koltsegtermek="1"{/if}>
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    <input name="tetelmozgat_{$tetel.id}" type="hidden" value="{$tetel.mozgat}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    {* The offset advance invoice. Hidden: the server re-derives it on save
       (bizonylatfejController::setTetelEloleg()); the posted value is never trusted. *}
    <input name="tetelelolegbizonylat_{$tetel.id}" type="hidden" value="{$tetel.elolegbizonylatszam|default}">
    {* fejléc: a termék és a hozzá tartozó linkek; a js-termekpicturerow_ alatt keresi a JS a képet, a linket és a kartont *}
    <div class="tetel-fej js-termekpicturerow_{$tetel.id}">
        <a class="js-toflyout tetel-kep" href="{$mainurl}{$tetel.kepurl|default:'/themes/main/empty.jpg'}" target="_blank"><img
                    src="{$mainurl}{$tetel.kiskepurl|default:'/themes/main/empty.jpg'}" alt=""
                    onerror="this.style.visibility='hidden'" onload="this.style.visibility=''"/></a>
        <div class="tetel-termek">
            <label class="mattable-important" for="TermekSelect{$tetel.id}">{at('Termék')}:</label>
            {* a termékválasztó és a js-termekid testvér marad: a választás a siblings()-be írja az id-t *}
            <div class="tetel-termekmezo">
                {if ($setup.termekautocomplete)}
                    {* A választó a már mentett tételen is ott van, hogy a termék cserélhető legyen.
                       Stornón nem: a stornó tételnek az eredetit kell tükröznie. *}
                    {if ($tetel.oper === 'storno')}
                        {$tetel.termeknev}
                    {else}
                        <input id="TermekSelect{$tetel.id}" type="text" name="teteltermeknev_{$tetel.id}"
                               class="js-termekselect termekselect mattable-important" value="{$tetel.termeknev|escape}" required="required">
                    {/if}
                    <input class="js-termekid" name="teteltermek_{$tetel.id}" type="hidden" value="{$tetel.termek}">
                    {include 'bizonylatteteltermekgombok.tpl'}
                {else}
                    {if ($tetel.oper === 'storno')}
                        {$tetel.termeknev}
                        <input class="js-termekid" name="teteltermek_{$tetel.id}" type="hidden" value="{$tetel.termek}">
                    {else}
                        <select class="js-termekselectreal js-termekid" name="teteltermek_{$tetel.id}">
                            <option value="">{t('válasszon')}</option>
                            {foreach $tetel.termeklist as $_termekadat}
                                <option value="{$_termekadat.id}"{if ($_termekadat.id == $tetel.termek)} selected="selected"{/if}>{$_termekadat.caption|escape}</option>
                            {/foreach}
                        </select>
                    {/if}
                    {include 'bizonylatteteltermekgombok.tpl'}
                {/if}
            </div>
            {* üres, ha a terméknek nincs változata; a JS termékváltáskor tölti újra a helyőrzőt *}
            <div class="tetel-valtozat">
                <label for="ValtozatSelect{$tetel.id}">{at('Változat')}:</label>
                <div id="ValtozatPlaceholder{$tetel.id}">{include "bizonylatteteltermekvaltozatselect.tpl"}</div>
            </div>
            <div class="tetel-termekinfo">
                <a class="js-termeklink" href="{$tetel.link}" target="_blank">{$tetel.link}</a>
                <a class="js-kartonlink" href="{$tetel.kartonurl|default:'#'}" target="_blank">Karton</a>
                {if ($tetel.elolegbizonylatszam|default)}
                    <span>{at('Beszámított előleg')}: {$tetel.elolegbizonylatszam}
                        {if ($tetel.elolegfizetesdatumstr|default)}({$tetel.elolegfizetesdatumstr}){/if}</span>
                {/if}
            </div>
        </div>
        <a class="js-teteldelbutton tetel-torles" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if}
           title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
    </div>
    <div class="tetel-mezok">
        {if ($showgarancialisadatok)}
            {mezocsoport cim="Garanciális adatok"}
                {mezo cimke="Termék leírás" for="MegjegyzesEdit{$tetel.id}" szeles=true}
                    <input id="MegjegyzesEdit{$tetel.id}" type="text" name="tetelmegjegyzes_{$tetel.id}" value="{$tetel.megjegyzes|escape}">
                {/mezo}
                {mezo cimke="Hiba leírás" for="Megjegyzes2Edit{$tetel.id}" szeles=true}
                    <input id="Megjegyzes2Edit{$tetel.id}" type="text" name="tetelmegjegyzes2_{$tetel.id}" value="{$tetel.megjegyzes2|escape}">
                {/mezo}
                {mezo cimke="Vásárlás dátuma" for="VasarlasdatumEdit{$tetel.id}"}
                    <input id="VasarlasdatumEdit{$tetel.id}" type="text" name="tetelvasarlasdatum_{$tetel.id}" value="{$tetel.vasarlasdatum}">
                {/mezo}
            {/mezocsoport}
        {/if}
        {mezocsoport cim="Azonosítás"}
            {mezo cimke="Név" for="NevEdit{$tetel.id}" szeles=true}
                <input id="NevEdit{$tetel.id}" name="tetelnev_{$tetel.id}" type="text" size="103" maxlength="255" value="{$tetel.termeknev|escape}"
                       required="required">
            {/mezo}
            {* nem írható: a termékből jön, a rejtett mezőt a JS tölti termékváltáskor, a mentés onnan olvassa *}
            {mezo cimke="Cikkszám"}
                <span class="tetel-cikkszam js-cikkszamszoveg_{$tetel.id}">{$tetel.cikkszam|escape}</span>
                <input name="tetelcikkszam_{$tetel.id}" type="hidden" value="{$tetel.cikkszam|escape}">
            {/mezo}
            {* a változat cikkszámát a JS írja változatváltáskor; üresen az egész pár rejtve (style.css) *}
            {mezo cimke="Változat"}
                <span class="tetel-cikkszam tetel-valtozatcikkszam js-valtozatcikkszam_{$tetel.id}">{$tetel.valtozatcikkszam|default|escape}</span>
            {/mezo}
            {* csak a termék beállítása dönt (kellegyediazonosito a termékből jön); egy már beírt azonosító rejtve is mentődik *}
            {$_egyedirejtve = !($tetel.kellegyediazonosito|default:false)}
            {mezo cimke="Egyedi azonosító" for="TermekegyediazonositoEdit{$tetel.id}" class="mezo-fontos js-egyediazonositorow_{$tetel.id}" szeles=true rejtve=$_egyedirejtve}
                <input id="TermekegyediazonositoEdit{$tetel.id}" name="teteltermekegyediazonosito_{$tetel.id}" type="text" size="103" maxlength="255"
                       value="{$tetel.termekegyediazonosito|default|escape}" class="js-egyediazonositoinput mattable-important"{if ($tetel.kellegyediazonosito|default)} required="required"{/if}>
                <input class="js-egyediazonositokell" name="tetelkellegyediazonosito_{$tetel.id}" type="hidden"
                       value="{if ($tetel.kellegyediazonosito|default)}1{else}0{/if}">
            {/mezo}
        {/mezocsoport}
        {* a #RaktarKeszlet tartalmát termék- és változatváltáskor a JS cseréli (getraktarkeszlet) *}
        <div class="tetelkeszlet">
            <div class="tetelkeszlet-cim">{at('Raktárkészlet')}</div>
            <div id="RaktarKeszlet{$tetel.id}">
                {include 'bizonylattetelraktarkeszlet.tpl' lista=$tetel.raktarkeszlet nemmozgat=$tetel.nemmozgat|default:false termekid=$tetel.termek valtozatid=$tetel.termekvaltozat}
            </div>
        </div>
        {mezocsoport cim="Adózás"}
            {mezo cimke="VTSZ" for="VtszSelect{$tetel.id}"}
                <select id="VtszSelect{$tetel.id}" name="tetelvtsz_{$tetel.id}" class="js-vtszselect" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $tetel.vtszlist as $_vtsz}
                        <option value="{$_vtsz.id}"{if ($_vtsz.selected)} selected="selected"{/if} data-afa="{$_vtsz.afa}">{$_vtsz.caption}</option>
                    {/foreach}
                </select>
            {/mezo}
            {mezo cimke="ÁFA" for="AfaSelect{$tetel.id}"}
                <select id="AfaSelect{$tetel.id}" name="tetelafa_{$tetel.id}" class="js-afaselect" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $tetel.afalist as $_afa}
                        <option value="{$_afa.id}"{if ($_afa.selected)} selected="selected"{/if} data-afakulcs="{$_afa.afakulcs}"
                                data-magyar="{if ($_afa.magyar)}1{else}0{/if}" data-navcase="{$_afa.navcase}">{$_afa.caption}</option>
                    {/foreach}
                </select>
            {/mezo}
        {/mezocsoport}
        {mezocsoport cim="Mennyiség"}
            {$_kiszerelesrejtve = !($tetel.termekkiszereles|default:false)}
            {mezo cimke="Gyűjtő" for="GyujtomennyisegEdit{$tetel.id}" class="js-kiszerelesrow_{$tetel.id}" rejtve=$_kiszerelesrejtve}
                {* letiltva nem küldi a böngésző, a mentés ilyenkor 0-t ír: amelyik kiszerelés nincs meg a terméknek, annak 0 a darabszáma *}
                <input id="GyujtomennyisegEdit{$tetel.id}" class="js-kiszerelesinput" name="tetelgyujtomennyiseg_{$tetel.id}" type="number"
                       step="any" value="{$tetel.gyujtomennyiseg}" size="6"{if (!($tetel.termekgyujto|default:0))} disabled{/if}>
                <input class="js-kiszerelesgyujto" name="tetelgyujto_{$tetel.id}" type="hidden" value="{$tetel.gyujto}">
                <input class="js-kiszerelessordoboz" name="tetelsordoboz_{$tetel.id}" type="hidden" value="{$tetel.sordoboz}">
            {/mezo}
            {mezo cimke="Sor/doboz" for="SordobozmennyisegEdit{$tetel.id}" class="js-kiszerelesrow_{$tetel.id}" rejtve=$_kiszerelesrejtve}
                <input id="SordobozmennyisegEdit{$tetel.id}" class="js-kiszerelesinput" name="tetelsordobozmennyiseg_{$tetel.id}" type="number"
                       step="any" value="{$tetel.sordobozmennyiseg}" size="6"{if (!($tetel.termeksordoboz|default:0))} disabled{/if}>
            {/mezo}
            {mezo cimke="Mennyiség" for="MennyisegEdit{$tetel.id}" class="mezo-fontos"}
                <input id="MennyisegEdit{$tetel.id}" class="js-mennyiseginput mattable-important" name="tetelmennyiseg_{$tetel.id}" type="number"
                       step="any" value="{$tetel.mennyiseg}" maxlength="20" size="10" required="required"{if (!$tetel.bonthato)} readonly="readonly"{/if}>
            {/mezo}
            {mezo cimke="ME" for="MESelect{$tetel.id}"}
                <select id="MESelect{$tetel.id}" name="tetelme_{$tetel.id}" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $tetel.melist as $_me}
                        <option value="{$_me.id}"{if ($_me.selected)} selected="selected"{/if}>{$_me.caption}</option>
                    {/foreach}
                </select>
            {/mezo}
        {/mezocsoport}
        {* a mezőket a JS név, osztály és id alapján éri el, a táblázat szerkezetére nem épít *}
        <div class="tetel-arcsoport">
            <div class="mattkarb-szakaszcim">{at('Árak')}</div>
            <table class="tetel-arak">
                <thead>
                <tr>
                    <th></th>
                    <th>{at('Nettó')}</th>
                    <th>{at('Bruttó')}</th>
                    {if ($showvalutanem)}
                        <th class="tetel-arak-huf">{at('Nettó HUF')}</th>
                        <th>{at('Bruttó HUF')}</th>
                    {/if}
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><label>{at('Er.egységár')}:</label></td>
                    <td><input name="tetelenettoegysar_{$tetel.id}" type="text" value="{$tetel.enettoegysar}" readonly class="js-enettoegysarinput"></td>
                    <td><input name="tetelebruttoegysar_{$tetel.id}" type="text" value="{$tetel.ebruttoegysar}" readonly class="js-ebruttoegysarinput"></td>
                    {if ($showvalutanem)}
                        <td class="tetel-arak-huf"><input name="tetelenettoegysarhuf_{$tetel.id}" type="text" value="{$tetel.enettoegysarhuf}" readonly></td>
                        <td><input name="tetelebruttoegysarhuf_{$tetel.id}" type="text" value="{$tetel.ebruttoegysarhuf}" readonly></td>
                    {/if}
                </tr>
                <tr>
                    <td><label for="KedvezmenyEdit{$tetel.id}">{at('Kedvezmény %')}:</label></td>
                    <td><input id="KedvezmenyEdit{$tetel.id}" name="tetelkedvezmeny_{$tetel.id}" type="text" value="{$tetel.kedvezmeny}" class="js-kedvezmeny"></td>
                    <td></td>
                    {if ($showvalutanem)}
                        <td class="tetel-arak-huf"></td>
                        <td></td>
                    {/if}
                </tr>
                <tr>
                    <td><label for="NettoegysarEdit{$tetel.id}">{at('Egységár')}:</label></td>
                    <td><input id="NettoegysarEdit{$tetel.id}" name="tetelnettoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysar}"
                               class="js-nettoegysarinput" required="required"></td>
                    <td><input name="tetelbruttoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysar}" class="js-bruttoegysarinput"
                               required="required"></td>
                    {if ($showvalutanem)}
                        <td class="tetel-arak-huf"><input name="tetelnettoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysarhuf}" readonly></td>
                        <td><input name="tetelbruttoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysarhuf}" readonly></td>
                    {/if}
                </tr>
                <tr class="tetel-arak-ertek">
                    <td class="mattable-important"><label for="NettoEdit{$tetel.id}">{at('Érték')}:</label></td>
                    <td><input id="NettoEdit{$tetel.id}" name="tetelnetto_{$tetel.id}" type="number" step="any" value="{$tetel.netto}"
                               class="mattable-important js-nettoinput"></td>
                    <td><input name="tetelbrutto_{$tetel.id}" type="number" step="any" value="{$tetel.brutto}" class="mattable-important js-bruttoinput"></td>
                    {if ($showvalutanem)}
                        <td class="tetel-arak-huf"><input name="tetelnettohuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettohuf}" readonly></td>
                        <td><input name="tetelbruttohuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttohuf}" readonly></td>
                    {/if}
                </tr>
                </tbody>
            </table>
            {if ($showhaszonszazalek)}
                <div class="tetel-arak-haszon">
                    <span>{at('Haszon %')}: <strong id="haszonszazalek_{$tetel.id}">{number_format($tetel.haszonszazalek,2,'.',' ')}</strong></span>
                    <span>{at('Eladási br.ár')}: <strong id="eladasibruttoar_{$tetel.id}"
                                                         data-ertek="{$tetel.eladasibrutto}">{number_format($tetel.eladasibrutto,2,'.',' ')}</strong></span>
                </div>
            {/if}
        </div>
    </div>

</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}