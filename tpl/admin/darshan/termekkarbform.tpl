<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('Termék')}</h3>
    <h4><a href="{$mainurl}/termek/{$egyed.slug}" target="_blank">{$egyed.nev}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/termek/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#JogaTab">{at('Jóga adatok')}</a></li>
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
            <li><a href="#DokTab">{at('Dokumentumok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="MozgatCheck" name="mozgat" type="checkbox"
                   {if ($egyed.mozgat)}checked="checked"{/if}>{at('Készletet mozgat')}
            <input id="EladhatoCheck" name="eladhato" type="checkbox"
                   {if ($egyed.eladhato)}checked="checked"{/if}>{at('Eladható')}
            <input id="KozvetitettCheck" name="kozvetitett" type="checkbox"
                   {if ($egyed.kozvetitett)}checked="checked"{/if}>{at('Közvetített szolgáltatás')}
            <table>
                <tbody>
                <tr>
                    <td><label>{at('Kategóriák')}:</label></td>
                    <td><span id="TermekKategoria1" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa1"
                              data-value="{$egyed.termekfa1}">{if ($egyed.termekfa1nev)}{$egyed.termekfa1nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria2" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa2"
                              data-value="{$egyed.termekfa2}">{if ($egyed.termekfa2nev)}{$egyed.termekfa2nev}{else}{at('válasszon')}{/if}</span>
                    </td>
                    <td><span id="TermekKategoria3" class="js-termekfabutton" data-text="{at('válasszon')}"
                              data-name="termekfa3"
                              data-value="{$egyed.termekfa3}">{if ($egyed.termekfa3nev)}{$egyed.termekfa3nev}{else}{at('válasszon')}{/if}</span>
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
                                           value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="KiirtNevEdit">{at('Bizonylaton szereplő név')}:</label></td>
                    <td colspan="3"><input id="KiirtNevEdit" name="kiirtnev" type="text" size="83" maxlength="255"
                                           value="{$egyed.kiirtnev}"></td>
                </tr>
                <tr>
                    <td><label for="CikkszamEdit">{at('Cikkszám')}:</label></td>
                    <td><input id="CikkszamEdit" name="cikkszam" type="text" size="30" maxlength="30"
                               value="{$egyed.cikkszam}"></td>
                </tr>
                {if ($setup.vonalkod)}
                    <tr>
                        <td><label for="VonalkodEdit">{at('Vonalkód')}:</label></td>
                        <td><input id="VonalkodEdit" name="vonalkod" type="text" size="30" maxlength="50"
                                   value="{$egyed.vonalkod}"></td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="MEEdit">{at('ME')}:</label></td>
                    <td><input id="MEEdit" name="me" type="text" size="20" maxlength="20" value="{$egyed.me}"></td>
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
                </tbody>
            </table>
            {if (!$setup.arsavok)}
            <table>
                <tbody>
                    <tr>
                        <td></td>
                        <td>HUF</td>
                    </tr>
                    <tr>
                        <td><label for="NettoEdit">{at('Nettó')}:</label></td>
                        <td><input id="NettoEdit" name="netto" type="number" step="any" value="{$egyed.netto}"></td>
                    </tr>
                    <tr>
                        <td><label for="BruttoEdit">{at('Bruttó')}:</label></td>
                        <td><input id="BruttoEdit" name="brutto" type="number" step="any" value="{$egyed.brutto}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkcioStartEdit">{at('Akció kezdete')}:</label></td>
                        <td><input id="AkcioStartEdit" name="akciostart" type="text" size="12"
                                   data-datum="{$egyed.akciostartstr}"></td>
                        <td><label for="AkcioStopEdit">{at('Akció vége')}:</label></td>
                        <td><input id="AkcioStopEdit" name="akciostop" type="text" size="12"
                                   data-datum="{$egyed.akciostopstr}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkciosNettoEdit">{at('Akciós nettó')}:</label></td>
                        <td><input id="AkciosNettoEdit" name="akciosnetto" type="number" step="any"
                                   value="{$egyed.akciosnetto}"></td>
                    </tr>
                    <tr>
                        <td><label for="AkciosBruttoEdit">{at('Akciós bruttó')}:</label></td>
                        <td><input id="AkciosBruttoEdit" name="akciosbrutto" type="number" step="any"
                                   value="{$egyed.akciosbrutto}"></td>
                    </tr>
                </tbody>
            </table>
            {/if}
        </div>
        <div id="JogaTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="JogaalkalomEdit">{at('Bérlet alkalom')}:</label></td>
                    <td><input id="JogaalkalomEdit" name="jogaalkalom" value="{$egyed.jogaalkalom}"></td>
                </tr>
                <tr>
                    <td><label for="JogaervenyessegEdit">{at('Bérlet érvényesség (hét)')}:</label></td>
                    <td><input id="JogaervenyessegEdit" name="jogaervenyesseg" value="{$egyed.jogaervenyesseg}"></td>
                </tr>
                </tbody>
            </table>
        </div>
        {if ($setup.arsavok)}
            <div id="ArsavTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.arak as $ar}
                    {include '../default/termektermekarkarb.tpl'}
                {/foreach}
                <a class="js-arnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
        {if ($setup.multilang)}
            <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.translations as $translation}
                    {include '../default/translationkarb.tpl'}
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
                            {include '../default/cimkeselector.tpl'}
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
                {foreach $egyed.receptek as $recept}
                    {include '../default/termektermekreceptkarb.tpl'}
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
                   data-termekid="{$egyed.id}"><span class="ui-button-text">{at('Mind törlése')}</span></a>
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
                                    <li data-value="{$kep.id}" data-valtozatid="gen" class="ui-state-default">
                                        {if ($kep.url)}<img src="{$mainurl}{$kep.url}"/>{/if}
                                    </li>
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
                {foreach $egyed.valtozatok as $valtozat}
                    {include '../default/termektermekvaltozatkarb.tpl'}
                {/foreach}
                <a class="js-valtozatnewbutton" href="#" title="{at('Új')}" data-termekid="{$egyed.id}"><span
                        class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        {/if}
        {if ($setup.kapcsolodotermekek)}
            <div id="KapcsolodoTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.kapcsolodok as $kapcsolodo}
                    {include '../default/termektermekkapcsolodokarb.tpl'}
                {/foreach}
                <a class="js-kapcsolodonewbutton" href="#" title="{at('Új')}"><span
                        class="ui-icon ui-icon-circle-plus"></span></a>
            </div>
        {/if}
        <div id="WebTab" class="mattkarb-page">
            <input id="LathatoCheck" name="lathato" type="checkbox"
                   {if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}
            <input id="NemkaphatoCheck" name="nemkaphato" type="checkbox"
                   {if ($egyed.nemkaphato)}checked="checked"{/if}>{at('Nem kapható')}
            <input id="FuggobenCheck" name="fuggoben" type="checkbox"
                   {if ($egyed.fuggoben)}checked="checked"{/if}>{at('Függőben')}
            <input id="AjanlottCheck" name="ajanlott" type="checkbox"
                   {if ($egyed.ajanlott)}checked="checked"{/if}>{at('Ajánlott')}
            <input id="KiemeltCheck" name="kiemelt" type="checkbox"
                   {if ($egyed.kiemelt)}checked="checked"{/if}>{at('Kiemelt')}
            <input id="HozzaszolasCheck" name="hozzaszolas" type="checkbox"
                   {if ($egyed.hozzaszolas)}checked="checked"{/if}>{at('Hozzá lehet szólni')}
            <input id="TermekExportbanSzerepel" name="termekexportbanszerepel" type="checkbox"
                   {if ($egyed.termekexportbanszerepel)}checked="checked"{/if}>{at('Termékexportokban szerepel')}
            <table>
                <tbody>
                <tr>
                    <td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
                    <td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255"
                               value="{$egyed.oldalcim}"></td>
                </tr>
                <tr>
                    <td><label for="RovidLeirasEdit">{at('Rövid leírás')}:</label></td>
                    <td><input id="RovidLeirasEdit" name="rovidleiras" type="text" size="100" maxlength="255"
                               value="{$egyed.rovidleiras}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
                    <td><textarea id="SeoDescriptionEdit" name="seodescription"
                                  cols="70">{$egyed.seodescription}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="KepTab" class="mattkarb-page" data-visible="visible">
            <div>
                <label for="RegikepurlEdit">{at('Régi kép url')}:</label>
                <input id="RegikepurlEdit" type="text" name="regikepurl" size=70 value="{$egyed.regikepurl}">
            </div>
            {include '../default/termekimagekarb.tpl'}
            {foreach $egyed.kepek as $kep}
                {include '../default/termektermekkepkarb.tpl'}
            {/foreach}
            <a class="js-kepnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
        <div id="DokTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.dokok as $dok}
                {include '../default/dokumentumtarkarb.tpl'}
            {/foreach}
            <a class="js-doknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
        <a class="js-saveas" href="#">{at('Mentés új termékként')}</a>
        {if ($oper=='add')}
            <a class="js-saveandreopen" href="#">{at('Mentés és szerkesztés')}</a>
        {/if}
    </div>
</form>
<form id="valtozatgeneratorform" method="post" action="/admin/termekvaltozat/generate" data-id="{$egyed.id}"></form>