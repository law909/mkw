<div id="mattkarb-header">
    <h3>{at('Hír')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo szeles=true}
                    <input id="LathatoCheck" name="lathato" type="checkbox"
                    {if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}</input>
                {/mezo}
                {mezo cimke="Hír dátuma" for="DatumEdit"}
                    <input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required>
                {/mezo}
                {mezo cimke="Első megjelenés" for="ElsoDatumEdit"}
                    <input id="ElsoDatumEdit" name="elsodatum" type="text" size="12" data-datum="{$egyed.elsodatumstr}" required>
                {/mezo}
                {mezo cimke="Utolsó megjelenés" for="UtolsoDatumEdit"}
                    <input id="UtolsoDatumEdit" name="utolsodatum" type="text" size="12" data-datum="{$egyed.utolsodatumstr}" required>
                {/mezo}
                {mezo cimke="Cím" for="CimEdit" szeles=true}
                    <input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim}">
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="Forrás" for="ForrasEdit" szeles=true}
                    <input id="ForrasEdit" name="forras" type="text" size="80" maxlength="255" value="{$egyed.forras}">
                {/mezo}
                {mezo cimke="Lead" for="LeadEdit" szeles=true}
                    <textarea id="LeadEdit" name="lead" cols="70">{$egyed.lead}</textarea>
                {/mezo}
                {mezo cimke="Szöveg" for="SzovegEdit" szeles=true}
                    <textarea id="SzovegEdit" name="szoveg" cols="70">{$egyed.szoveg}</textarea>
                {/mezo}
                {mezo cimke="META leírás" for="SeoDescriptionEdit" szeles=true}
                    <textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea>
                {/mezo}
            {/mezocsoport}
            <table>
                <tbody>
                <tr class="imageupload">
                    <td>{if ($egyed.kepurl)}<a class="js-toflyout" href="{$mainurl}{$egyed.kepurl}" target="_blank"><img
                                src="{$mainurl}{$egyed.kepurlsmall}" alt="{$egyed.kepleiras}" title="{$egyed.kepleiras}"/></a>{/if}</td>
                    <td>
                        <table>
                            <tbody>
                            <tr>
                                <td><label for="KepUrlEdit">{at('Kép')}:</label></td>
                                <td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$egyed.kepurl}"></td>
                                <td><a id="KepBrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$egyed.id}"
                                       title="{at('Browse')}">{at('...')}</a></td>
                            </tr>
                            <tr>
                                <td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
                                <td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$egyed.kepleiras}"></td>
                                <td><a id="KepDelButton" class="js-kepdelbutton" href="#" data-id="{$egyed.id}" title="{at('Töröl')}"><span
                                            class="ui-icon ui-icon-circle-minus"></span></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
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