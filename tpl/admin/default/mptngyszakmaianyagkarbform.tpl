<div id="mattkarb-header">
    <h3>{at('Szakmai anyag')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/mptngyszakmaianyag/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#BiralatTab">{at('Bírálók és bírálatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td>{at('Azonosító')}:</td>
                    <td>{$egyed.id}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <label for="veglegesEdit">{at('Beküldve')}:</label>
                        <input id="veglegesEdit" type="checkbox" name="vegleges"{if ($egyed.vegleges)} checked{/if}>
                        <label for="biralatkeszEdit">{at('Bírálat kész')}:</label>
                        <input id="biralatkeszEdit" type="checkbox" name="biralatkesz"{if ($egyed.biralatkesz)} checked{/if} disabled>
                        <label for="kszEdit">{at('Konferencián szerepelhet')}:</label>
                        <input id="kszEdit" type="checkbox" name="konferencianszerepelhet"{if ($egyed.konferencianszerepelhet)} checked{/if} disabled>
                    </td>
                </tr>
                <tr>
                    <td><label for="CimEdit">{at('Cím')}:</label></td>
                    <td><input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim|htmlentities}" required></td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="kezdodatumEdit">{at('Kezdés')}:</label></td>
                    <td>
                        <select id="kezdodatumEdit" name="kezdodatum">
                            <option value="">{at('válasszon')}</option>
                            {foreach $datumlist as $_mk}
                                <option
                                    value="{$_mk.id}"
                                        {if ($_mk.selected)} selected="selected"{/if}
                                >{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <input name="kezdoido" value="{$egyed.kezdoido}"> -
                        <input name="vegido" value="{$egyed.vegido}">
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="teremEdit">{at('Terem')}:</label></td>
                    <td>
                        <select id="teremEdit" name="terem">
                            <option value="">{at('válasszon')}</option>
                            {foreach $teremlist as $_mk}
                                <option
                                    value="{$_mk.id}"
                                        {if ($_mk.selected)} selected="selected"{/if}
                                        {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                                >{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="tipusEdit">{at('Típus')}:</label></td>
                    <td>
                        <select id="tipusEdit" name="tipus">
                            <option value="">{at('válasszon')}</option>
                            {foreach $tipuslist as $_mk}
                                <option
                                    value="{$_mk.id}"
                                        {if ($_mk.selected)} selected="selected"{/if}
                                        {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                                >{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="tulajdonosEdit">{at('Tulajdonos')}:</label></td>
                    <td>
                        <select id="tulajdonosEdit" name="tulajdonos">
                            <option value="">{at('válasszon')}</option>
                            {foreach $tulajdonoslist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="szerzo1Edit">{at('Szerzők')}:</label></td>
                    <td>
                        <input id="szerzo1emailEdit" name="szerzo1email" type="email" value="{$egyed.szerzo1email}">
                        <select id="szerzo1Edit" name="szerzo1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input id="szerzo2emailEdit" name="szerzo2email" type="email" value="{$egyed.szerzo2email}">
                        <select id="szerzo2Edit" name="szerzo2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input id="szerzo3emailEdit" name="szerzo3email" type="email" value="{$egyed.szerzo3email}">
                        <select id="szerzo3Edit" name="szerzo3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input id="szerzo4emailEdit" name="szerzo4email" type="email" value="{$egyed.szerzo4email}">
                        <select id="szerzo4Edit" name="szerzo4">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo4list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="egyebszerzokEdit">{at('Egyéb szerzők')}:</label></td>
                    <td><textarea id="egyebszerzokEdit" name="egyebszerzok" rows="10" cols="80">{$egyed.egyebszerzok}</textarea></td>
                </tr>
                <tr>
                    <td><label for="egyebszerzokorgEdit">{at('Eredeti egyéb szerzők')}:</label></td>
                    <td><textarea id="egyebszerzokorgEdit" rows="10" cols="80" disabled>{$egyed.egyebszerzokorg}</textarea></td>
                </tr>
                <tr style="height: 1em;" class="onlyszimpozium"></tr>
                <tr class="onlyszimpozium hidden">
                    <td><label for="opponensEdit">{at('Opponens')}:</label></td>
                    <td>
                        <input id="szerzo5emailEdit" name="szerzo5email" type="email" value="{$egyed.szerzo5email}" class="onlyszimpozium hidden">
                        <select id="szerzo5Edit" name="szerzo5" class="onlyszimpozium hidden">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szerzo5list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;" class="onlyszimpozium"></tr>
                <tr class="onlyszimpozium hidden">
                    <td><label for="eloadas1Edit">{at('Előadás 1')}:</label></td>
                    <td>
                        <select id="eloadas1Edit" name="eloadas1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $eloadas1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr class="onlyszimpozium hidden">
                    <td><label for="eloadas2Edit">{at('Előadás 2')}:</label></td>
                    <td>
                        <select id="eloadas2Edit" name="eloadas2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $eloadas2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr class="onlyszimpozium hidden">
                    <td><label for="eloadas3Edit">{at('Előadás 3')}:</label></td>
                    <td>
                        <select id="eloadas3Edit" name="eloadas3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $eloadas3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr class="onlyszimpozium hidden">
                    <td><label for="eloadas4Edit">{at('Előadás 4')}:</label></td>
                    <td>
                        <select id="eloadas4Edit" name="eloadas4">
                            <option value="">{at('válasszon')}</option>
                            {foreach $eloadas4list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.id} - {$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="temakor1Edit">{at('Témakörök')}:</label></td>
                    <td>
                        <select id="temakor1Edit" name="temakor1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <select id="temakor2Edit" name="temakor2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <select id="temakor3Edit" name="temakor3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temakor3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="temaEdit">{at('Téma')}:</label></td>
                    <td>
                        <select id="temaEdit" name="tema">
                            <option value="">{at('válasszon')}</option>
                            {foreach $temalist as $_mk}
                                <option
                                    value="{$_mk.id}"
                                        {if ($_mk.selected)} selected="selected"{/if}
                                        {if ($_mk.szimpozium)}data-szimpozium="{$_mk.szimpozium}"{/if}
                                >{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="kulcsszo1Edit">{at('Kulcsszavak')}:</label></td>
                    <td>
                        <input id="kulcsszo1Edit" name="kulcsszo1" type="text" value="{$egyed.kulcsszo1}">
                        <input name="kulcsszo2" type="text" value="{$egyed.kulcsszo2}">
                        <input name="kulcsszo3" type="text" value="{$egyed.kulcsszo3}">
                        <input name="kulcsszo4" type="text" value="{$egyed.kulcsszo4}">
                        <input name="kulcsszo5" type="text" value="{$egyed.kulcsszo5}">
                    </td>
                </tr>
                <tr>
                    <td><label for="tartalomEdit">{at('Tartalom')}:</label></td>
                    <td><textarea id="tartalomEdit" name="tartalom" rows="20" cols="80">{$egyed.tartalom}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="BiralatTab" class="mattkarb-page">
            <table>
                <tbody>
                <tr>
                    <td><label for="biralo1Edit">{at('Bíráló')}:</label></td>
                    <td>
                        <select id="biralo1Edit" name="biralo1">
                            <option value="">{at('válasszon')}</option>
                            {foreach $biralo1list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="b1biralatkeszEdit">{at('Bírálat kész')}:</label></td>
                    <td><input id="b1biralatkeszEdit" type="checkbox" name="b1biralatkesz"{if ($egyed.b1biralatkesz)} checked{/if}></td>
                </tr>
                <tr>
                    <td><label for="b1szempont1Edit">{$szempont1nev}</label></td>
                    <td><input id="b1szempont1Edit" type="number" name="b1szempont1" value="{$egyed.b1szempont1}"></td>
                </tr>
                <tr>
                    <td><label for="b1szempont2Edit">{$szempont2nev}</label></td>
                    <td><input id="b1szempont2Edit" type="number" name="b1szempont2" value="{$egyed.b1szempont2}"></td>
                </tr>
                <tr>
                    <td><label for="b1szempont3Edit">{$szempont3nev}</label></td>
                    <td><input id="b1szempont3Edit" type="number" name="b1szempont3" value="{$egyed.b1szempont3}"></td>
                </tr>
                <tr>
                    <td><label for="b1szempont4Edit">{$szempont4nev}</label></td>
                    <td><input id="b1szempont4Edit" type="number" name="b1szempont4" value="{$egyed.b1szempont4}"></td>
                </tr>
                <tr>
                    <td><label for="b1szempont5Edit">{$szempont5nev}</label></td>
                    <td><input id="b1szempont5Edit" type="number" name="b1szempont5" value="{$egyed.b1szempont5}"></td>
                </tr>
                <tr>
                    <td><label for="b1szovegesEdit">{at('Szöveges értékelés')}</label></td>
                    <td><textarea id="b1szovegesEdit" name="b1szovegesertekeles" rows="10" cols="80">{$egyed.b1szovegesertekeles}</textarea></td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="biralo2Edit">{at('Bíráló')}:</label></td>
                    <td>
                        <select id="biralo2Edit" name="biralo2">
                            <option value="">{at('válasszon')}</option>
                            {foreach $biralo2list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="b2biralatkeszEdit">{at('Bírálat kész')}:</label></td>
                    <td><input id="b2biralatkeszEdit" type="checkbox" name="b2biralatkesz"{if ($egyed.b2biralatkesz)} checked{/if}></td>
                </tr>
                <tr>
                    <td><label for="b2szempont1Edit">{$szempont1nev}</label></td>
                    <td><input id="b2szempont1Edit" type="number" name="b2szempont1" value="{$egyed.b2szempont1}"></td>
                </tr>
                <tr>
                    <td><label for="b2szempont2Edit">{$szempont2nev}</label></td>
                    <td><input id="b2szempont2Edit" type="number" name="b2szempont2" value="{$egyed.b2szempont2}"></td>
                </tr>
                <tr>
                    <td><label for="b2szempont3Edit">{$szempont3nev}</label></td>
                    <td><input id="b2szempont3Edit" type="number" name="b2szempont3" value="{$egyed.b2szempont3}"></td>
                </tr>
                <tr>
                    <td><label for="b2szempont4Edit">{$szempont4nev}</label></td>
                    <td><input id="b2szempont4Edit" type="number" name="b2szempont4" value="{$egyed.b2szempont4}"></td>
                </tr>
                <tr>
                    <td><label for="b2szempont5Edit">{$szempont5nev}</label></td>
                    <td><input id="b2szempont5Edit" type="number" name="b2szempont5" value="{$egyed.b2szempont5}"></td>
                </tr>
                <tr>
                    <td><label for="b2szovegesEdit">{at('Szöveges értékelés')}</label></td>
                    <td><textarea id="b2szovegesEdit" name="b2szovegesertekeles" rows="10" cols="80">{$egyed.b2szovegesertekeles}</textarea></td>
                </tr>
                <tr style="height: 1em;"></tr>
                <tr>
                    <td><label for="biralo3Edit">{at('Bíráló')}:</label></td>
                    <td>
                        <select id="biralo3Edit" name="biralo3">
                            <option value="">{at('válasszon')}</option>
                            {foreach $biralo3list as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-email="{$_mk.email}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="b3biralatkeszEdit">{at('Bírálat kész')}:</label></td>
                    <td><input id="b3biralatkeszEdit" type="checkbox" name="b3biralatkesz"{if ($egyed.b3biralatkesz)} checked{/if}></td>
                </tr>
                <tr>
                    <td><label for="b3szempont1Edit">{$szempont1nev}</label></td>
                    <td><input id="b3szempont1Edit" type="number" name="b3szempont1" value="{$egyed.b3szempont1}"></td>
                </tr>
                <tr>
                    <td><label for="b3szempont2Edit">{$szempont2nev}</label></td>
                    <td><input id="b3szempont2Edit" type="number" name="b3szempont2" value="{$egyed.b3szempont2}"></td>
                </tr>
                <tr>
                    <td><label for="b3szempont3Edit">{$szempont3nev}</label></td>
                    <td><input id="b3szempont3Edit" type="number" name="b3szempont3" value="{$egyed.b3szempont3}"></td>
                </tr>
                <tr>
                    <td><label for="b3szempont4Edit">{$szempont4nev}</label></td>
                    <td><input id="b3szempont4Edit" type="number" name="b3szempont4" value="{$egyed.b3szempont4}"></td>
                </tr>
                <tr>
                    <td><label for="b3szempont5Edit">{$szempont5nev}</label></td>
                    <td><input id="b3szempont5Edit" type="number" name="b3szempont5" value="{$egyed.b3szempont5}"></td>
                </tr>
                <tr>
                    <td><label for="b3szovegesEdit">{at('Szöveges értékelés')}</label></td>
                    <td><textarea id="b3szovegesEdit" name="b3szovegesertekeles" rows="10" cols="80">{$egyed.b3szovegesertekeles}</textarea></td>
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