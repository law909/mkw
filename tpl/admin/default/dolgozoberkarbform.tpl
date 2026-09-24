<div id="mattkarb-header">
    <h3>{at('Bér')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {if ($readonly)}
                <p class="rontott">{at('Rontott sor, nem módosítható.')} {at('Rontotta')}: {$egyed.rontottby|escape} {$egyed.rontottonstr}</p>
            {/if}
            <fieldset{if ($readonly)} disabled="disabled"{/if}>
            <table>
                <tbody>
                <tr>
                    <td><label for="DolgozoEdit">{at('Dolgozó')}:</label></td>
                    <td><select id="DolgozoEdit" name="dolgozo" required autofocus>
                            <option value="">{at('válasszon')}</option>
                            {foreach $dolgozolist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="DatumEdit">{at('Dátum')}:</label></td>
                    <td><input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required></td>
                </tr>
                <tr>
                    <td><label for="BerjogcimEdit">{at('Jogcím')}:</label></td>
                    <td><select id="BerjogcimEdit" name="berjogcim" required>
                            <option value="">{at('válasszon')}</option>
                            {foreach $berjogcimlist as $_jc}
                                <option value="{$_jc.id}"{if ($_jc.selected)} selected="selected"{/if}>{$_jc.caption|escape}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="OsszegEdit">{at('Összeg')}:</label></td>
                    <td><input id="OsszegEdit" name="osszeg" type="number" step="any" value="{$egyed.osszeg}" required> Ft</td>
                </tr>
                <tr>
                    <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                    <td><input id="MegjegyzesEdit" name="megjegyzes" type="text" size="60" maxlength="255" value="{$egyed.megjegyzes|escape}"></td>
                </tr>
                {if ($egyed.id)}
                <tr>
                    <td>{at('Rögzítette')}:</td>
                    <td>{$egyed.createdby|escape} {$egyed.createdstr}</td>
                </tr>
                <tr>
                    <td>{at('Módosította')}:</td>
                    <td>{$egyed.updatedby|escape} {$egyed.lastmodstr}</td>
                </tr>
                {/if}
                </tbody>
            </table>
            </fieldset>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        {if (!$readonly)}<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">{/if}
        <a id="mattkarb-cancelbutton" href="#">{if ($readonly)}{at('Vissza')}{else}{at('Mégsem')}{/if}</a>
    </div>
</form>
