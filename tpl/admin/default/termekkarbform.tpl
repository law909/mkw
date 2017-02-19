<div id="mattkarb-header">
    {if ($termek.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$termek.kepurlsmall}"/>
    {/if}
    <h3>{at('Termék')}</h3>
    <h4><a href="{$mainurl}/termek/{$termek.slug}" target="_blank">{$termek.nev}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/termek/save" data-id="{$termek.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            {if ($setup.arsavok)}
                <li><a href="#ArsavTab">{at('Ársávok')}</a></li>
            {/if}
            {if ($setup.multilang)}
                <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            {/if}
            <li><a href="#CimkeTab">{at('Címkék')}</a></li>
            <li><a href="#KepTab">{at('Képek')}</a></li>
            {if ($setup.receptura)}
                <li><a href="#RecepturaTab">{at('Receptúra')}</a></li>
            {/if}
            {if ($setup.termekvaltozat)}
                <li><a href="#ValtozatTab">{at('Változatok')}</a></li>
            {/if}
            {if ($setup.kapcsolodotermekek)}
                <li><a href="#KapcsolodoTab">{at('Kapcsolódó termékek')}</a></li>
            {/if}
            <li><a href="#WebTab">{at('Webes adatok')}</a></li>
            <li><a href="#CsomagolasTab">{at('Csomagolási adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($termek.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="MozgatCheck" name="mozgat" type="checkbox"
                   {if ($termek.mozgat)}checked="checked"{/if}>{at('Készletet mozgat')}
            <input id="KozvetitettCheck" name="kozvetitett" type="checkbox"
                   {if ($termek.kozvetitett)}checked="checked"{/if}>{at('Közvetített szolgáltatás')}
            <table>
                <tbody>
                <tr>
                    <td><label>{at('Kategóriák')}:</label></td>
                    <td><span id="TermekKategoria1" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa1"
                              data-value="{$termek.termekfa1}">{if ($termek.termekfa1nev)}{$termek.termekfa1nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria2" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa2"
                              data-value="{$termek.termekfa2}">{if ($termek.termekfa2nev)}{$termek.termekfa2nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria3" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa3"
                              data-value="{$termek.termekfa3}">{if ($termek.termekfa3nev)}{$termek.termekfa3nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                <tr>
                    <td><label for="TermekcsoportEdit">{at('Termékcsoport')}:</label></td>
                    <td><select id="TermekcsoportEdit" name="termekcsoport">
                            <option value="">{at('válasszon')}</option>
                            {foreach $termekcsoportlist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$termek.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="CikkszamEdit">{at('Cikkszám')}:</label></td>
                    <td><input id="CikkszamEdit" name="cikkszam" type="text" size="30" maxlength="30"
                               value="{$termek.cikkszam}"></td>
                    <td><label for="IdegenCikkszamEdit">{at('Szállítói cikkszám')}:</label></td>
                    <td><input id="IdegenCikkszamEdit" name="idegencikkszam" type="text" size="30" maxlength="30"
                               value="{$termek.idegencikkszam}"></td>
                </tr>
                {if ($setup.vonalkod)}
                    <tr>
                        <td><label for="VonalkodEdit">{at('Vonalkód')}:</label></td>
                        <td><input id="VonalkodEdit" name="vonalkod" type="text" size="30" maxlength="50"
                                   value="{$termek.vonalkod}"></td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="MEEdit">{at('ME')}:</label></td>
                    <td><input id="MEEdit" name="me" type="text" size="20" maxlength="20" value="{$termek.me}"></td>
                    <td><label for="IdegenkodEdit">{at('Idegen kód')}:</label></td>
                    <td><input id="IdegenkodEdit" name="idegenkod" type="text" size="20" maxlength="255"
                               value="{$termek.idegenkod}"></td>
                </tr>
                <tr>
                    <td><label for="VtszEdit">{at('VTSZ')}:</label></td>
                    <td><select id="VtszEdit" name="vtsz">
                            <option value="">{at('válasszon')}</option>
                            {foreach $vtszlist as $_vtsz}
                                <option
                                    value="{$_vtsz.id}"{if ($_vtsz.selected)} selected="selected"{/if}>{$_vtsz.caption}</option>
                            {/foreach}
                        </select></td>
                    <td><label for="AfaEdit">{at('ÁFA')}:</label></td>
                    <td><select id="AfaEdit" name="afa" required>
                            <option value="">{at('válasszon')}</option>
                            {foreach $afalist as $_afa}
                                <option
                                    value="{$_afa.id}"{if ($_afa.selected)} selected="selected"{/if}>{$_afa.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="GyartoEdit">{at('Gyártó')}:</label></td>
                    <td colspan="3"><select id="GyartoEdit" name="gyarto">
                            <option value="">{at('válasszon')}</option>
                            {foreach $gyartolist as $_gyarto}
                                <option
                                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                <tr>
                    <td><label for="HparanyEdit">{at('Hűségpont arány')}:</label></td>
                    <td><input id="HparanyEdit" name="hparany" type="number" step="any" value="{$termek.hparany}"
                               maxlength="5" size="5"></td>
                    <td><label for="SzallitasiidoEdit">{at('Szállítási idő')}:</label></td>
                    <td><input id="SzallitasiidoEdit" name="szallitasiido" type="number" step="any"
                               value="{$termek.szallitasiido}" maxlength="5" size="5"></td>
                </tr>
                {if (!$setup.arsavok)}
                    <tr>
                        <td></td>
                        <td>HUF</td>
                    </tr>
                    <tr>
                        <td><label for="NettoEdit">{at('Nettó')}:</label></td>
                        <td><input id="NettoEdit" name="netto" type="number" step="any" value="{$termek.netto}"></td>
                    </tr>
                    <tr>
                        <td><label for="BruttoEdit">{at('Bruttó')}:</label></td>
                        <td><input id="BruttoEdit" name="brutto" type="number" step="any" value="{$termek.brutto}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkcioStartEdit">{at('Akció kezdete')}:</label></td>
                        <td><input id="AkcioStartEdit" name="akciostart" type="text" size="12"
                                   data-datum="{$termek.akciostartstr}"></td>
                        <td><label for="AkcioStopEdit">{at('Akció vége')}:</label></td>
                        <td><input id="AkcioStopEdit" name="akciostop" type="text" size="12"
                                   data-datum="{$termek.akciostopstr}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkciosNettoEdit">{at('Akciós nettó')}:</label></td>
                        <td><input id="AkciosNettoEdit" name="akciosnetto" type="number" step="any"
                                   value="{$termek.akciosnetto}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkciosBruttoEdit">{at('Akciós bruttó')}:</label></td>
                        <td><input id="AkciosBruttoEdit" name="akciosbrutto" type="number" step="any"
                                   value="{$termek.akciosbrutto}"></td>
                    </tr>
                {/if}
                </tbody>
            </table>
        </div>
        {if ($setup.arsavok)}
            <div id="ArsavTab" class="mattkarb-page" data-visible="visible">
                {foreach $termek.arak as $ar}
                    {include 'termektermekarkarb.tpl'}
                {/foreach}
                <a class="js-arnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
        {if ($setup.multilang)}
            <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
                {foreach $termek.translations as $translation}
                    {include 'termektermektranslationkarb.tpl'}
                {/foreach}
                <a class="js-translationnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
        <div id="CimkeTab" class="mattkarb-page" data-visible="visible">
            <div id="cimkekarbcontainer">
                <div id="cimkekarbcontainerhead"><a id="cimkekarbcollapse" href="#">{at('Kinyit/becsuk')}</a></div>
                {foreach $cimkekat as $_cimkekat}
                    <div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkekarbcloseupbutton"
                         data-refcontrol="#termekkarb{$_cimkekat.id}">
                        <a href="#" class="mattedit-titlebar-close">
                            <span class="ui-icon ui-icon-circle-triangle-s"></span>
                        </a>
                        <span>{$_cimkekat.caption}</span>
                    </div>
                    <div id="termekkarb{$_cimkekat.id}" class="js-cimkekarbpage cimkelista" data-visible="hidden">
                        {foreach $_cimkekat.cimkek as $_cimke}
                            {include 'cimkeselector.tpl'}
                        {/foreach}
                        <input id="ujcimkenev_{$_cimkekat.id}" type="text">&nbsp;<a class="js-cimkeadd" href="#"
                                                                                    data-refcontrol="#ujcimkenev_{$_cimkekat.id}">
                            &nbsp;+&nbsp;</a>
                    </div>
                {/foreach}
            </div>
        </div>
        {if ($setup.receptura)}
            <div id="RecepturaTab" class="mattkarb-page" data-visible="visible">
                {foreach $termek.receptek as $recept}
                    {include 'termektermekreceptkarb.tpl'}
                {/foreach}
                <a class="js-receptnewbutton" href="#" title="{at('Új')}"><span
                        class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        {/if}
        {if ($setup.termekvaltozat)}
            <div id="ValtozatTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <label for="ValtozatAdattipusEdit">{at('Látható tulajdonság')}:</label>
                    <select id="ValtozatAdattipusEdit" name="valtozatadattipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valtozatadattipuslist as $_valtozat}
                            <option
                                value="{$_valtozat.id}"{if ($_valtozat.selected)} selected="selected"{/if}>{$_valtozat.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <a class="js-valtozatdelallbutton" href="#" title="{at('Mind törlése')}"
                   data-termekid="{$termek.id}"><span class="ui-button-text">{at('Mind törlése')}</span></a>
                <table id="valtozatgenerator" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <tbody>
                    <tr>
                        <td class="mattable-cell">
                            <label for="VElerhetoEdit">{at('Elérhető')}:
                                <input id="VElerhetoEdit" form="valtozatgeneratorform" name="valtozatelerheto"
                                       type="checkbox">
                            </label>
                        </td>
                        <td class="mattable-cell">
                            <label for="VLathatoEdit">{at('Látható')}:
                                <input id="VLathatoEdit" form="valtozatgeneratorform" name="valtozatlathato"
                                       type="checkbox">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="mattable-cell">
                            <select name="valtozatadattipus1" form="valtozatgeneratorform">
                                <option value="">{at('válasszon')}</option>
                                {foreach $valtozatadattipuslist as $at}
                                    <option value="{$at.id}">{$at.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="mattable-cell">
                            <input name="valtozatertek1" form="valtozatgeneratorform" type="text">
                        </td>
                        <td class="mattable-cell">
                            <label for="NettoEdit">{at('Nettó')}:</label>
                        </td>
                        <td class="mattable-cell">
                            <input class="js-valtozatnettogen" form="valtozatgeneratorform" id="NettoEdit"
                                   name="valtozatnettogen">
                        </td>
                    </tr>
                    <tr>
                        <td class="mattable-cell">
                            <select name="valtozatadattipus2" form="valtozatgeneratorform">
                                <option value="">{at('válasszon')}</option>
                                {foreach $valtozatadattipuslist as $at}
                                    <option value="{$at.id}">{$at.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="mattable-cell">
                            <input name="valtozatertek2" form="valtozatgeneratorform" type="text">
                        </td>
                        <td class="mattable-cell">
                            <label for="VBruttoEdit">{at('Bruttó')}:</label>
                        </td>
                        <td class="mattable-cell">
                            <input class="js-valtozatbruttogen" id="VBruttoEdit" form="valtozatgeneratorform"
                                   name="valtozatbruttogen">
                        </td>
                    </tr>
                    <tr>
                        <td class="mattable-cell">
                            <label for="VCikkszamEdit">{at('Cikkszám')}:</label>
                        </td>
                        <td class="mattable-cell">
                            <input id="VCikkszamEdit" name="valtozatcikkszamgen" type="text"
                                   form="valtozatgeneratorform">
                        </td>
                        <td class="mattable-cell">
                            <label for="VIdegenCikkszamEdit">{at('Szállítói cikkszám')}:</label>
                        </td>
                        <td class="mattable-cell">
                            <input id="VIdegenCikkszamEdit" name="valtozatidegencikkszamgen" type="text"
                                   form="valtozatgeneratorform">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ValtozatTermekKepCB">{at('A kép a termék főképe')}:</label>
                            <input id="ValtozatTermekKepCB" form="valtozatgeneratorform" name="valtozattermek"
                                   type="checkbox">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="ValtozatKepEdit_gen">{at('Kép')}:</label></td>
                        <td colspan="3">
                            <ul id="ValtozatKepEdit_gen" class="valtozatkepedit js-valtozatkepedit">
                                {foreach $keplist as $kep}
                                    <li data-value="{$kep.id}" data-valtozatid="gen" class="ui-state-default"><img
                                            src="{$mainurl}{$kep.url}"/></li>
                                {/foreach}
                            </ul>
                            <input id="ValtozatKepId_gen" name="valtozatkepid" form="valtozatgeneratorform"
                                   type="hidden">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input id="valtozatgeneratorbutton" form="valtozatgeneratorform" type="submit"
                                   value="{at('Generál')}">
                        </td>
                    </tr>
                    </tbody>
                </table>
                {foreach $termek.valtozatok as $valtozat}
                    {include 'termektermekvaltozatkarb.tpl'}
                {/foreach}
                <a class="js-valtozatnewbutton" href="#" title="{at('Új')}" data-termekid="{$termek.id}"><span
                        class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        {/if}
        {if ($setup.kapcsolodotermekek)}
            <div id="KapcsolodoTab" class="mattkarb-page" data-visible="visible">
                {foreach $termek.kapcsolodok as $kapcsolodo}
                    {include 'termektermekkapcsolodokarb.tpl'}
                {/foreach}
                <a class="js-kapcsolodonewbutton" href="#" title="{at('Új')}"><span
                        class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        {/if}
        <div id="WebTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
            <input id="LathatoCheck" name="lathato" type="checkbox"
                   {if ($termek.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}
            <input id="NemkaphatoCheck" name="nemkaphato" type="checkbox"
                   {if ($termek.nemkaphato)}checked="checked"{/if}>{at('Nem kapható')}
            <input id="FuggobenCheck" name="fuggoben" type="checkbox"
                   {if ($termek.fuggoben)}checked="checked"{/if}>{at('Függőben')}
            <input id="AjanlottCheck" name="ajanlott" type="checkbox"
                   {if ($termek.ajanlott)}checked="checked"{/if}>{at('Ajánlott')}
            <input id="KiemeltCheck" name="kiemelt" type="checkbox"
                   {if ($termek.kiemelt)}checked="checked"{/if}>{at('Kiemelt')}
            <input id="HozzaszolasCheck" name="hozzaszolas" type="checkbox"
                   {if ($termek.hozzaszolas)}checked="checked"{/if}>{at('Hozzá lehet szólni')}
            <input id="TermekExportbanSzerepel" name="termekexportbanszerepel" type="checkbox"
                   {if ($termek.termekexportbanszerepel)}checked="checked"{/if}>{at('Termékexportokban szerepel')}
            <table>
                <tbody>
                <tr>
                    <td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
                    <td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255"
                               value="{$termek.oldalcim}"></td>
                </tr>
                <tr>
                    <td><label for="RovidLeirasEdit">{at('Rövid leírás')}:</label></td>
                    <td><input id="RovidLeirasEdit" name="rovidleiras" type="text" size="100" maxlength="255"
                               value="{$termek.rovidleiras}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras">{$termek.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
                    <td><textarea id="SeoDescriptionEdit" name="seodescription"
                                  cols="70">{$termek.seodescription}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="KepTab" class="mattkarb-page" data-visible="visible">
            <div>
                <label for="RegikepurlEdit">{at('Régi kép url')}:</label>
                <input id="RegikepurlEdit" type="text" name="regikepurl" size=70 value="{$termek.regikepurl}">
            </div>
            {include 'termekimagekarb.tpl'}
            {foreach $termek.kepek as $kep}
                {include 'termektermekkepkarb.tpl'}
            {/foreach}
            <a class="js-kepnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
        <div id="CsomagolasTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
            <table>
                <tbody>
                <tr>
                    <td colspan="2"><input id="OsszehajthatoEdit" type="checkbox"
                                           name="osszehajthato"{if ($termek.osszehajthato)} checked="checked"{/if}>{at('Összehajtható')}
                    </td>
                </tr>
                <tr>
                    <td><label for="SzelessegEdit">{at('Szélesség')}:</label></td>
                    <td><input id="SzelessegEdit" type="number" step="any" name="szelesseg" value="{$termek.szelesseg}">
                    </td>
                </tr>
                <tr>
                    <td><label for="MagassagEdit">{at('Magasság')}:</label></td>
                    <td><input id="MagassagEdit" type="number" step="any" name="magassag" value="{$termek.magassag}">
                    </td>
                </tr>
                <tr>
                    <td><label for="HosszusagEdit">{at('Hosszúság')}:</label></td>
                    <td><input id="HosszusagEdit" type="number" step="any" name="hosszusag" value="{$termek.hosszusag}">
                    </td>
                </tr>
                <tr>
                    <td><label for="SulyEdit">{at('Súly')}:</label></td>
                    <td><input id="SulyEdit" type="text" name="suly" value="{$termek.suly}"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$termek.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
        <a class="js-saveas" href="#">{at('Mentés új termékként')}</a>
        {if ($oper=='add')}
            <a class="js-saveandreopen" href="#">{at('Mentés és szerkesztés')}</a>
        {/if}
    </div>
</form>
<form id="valtozatgeneratorform" method="post" action="/admin/termekvaltozat/generate" data-id="{$termek.id}"></form>