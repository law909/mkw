<div id="mattkarb-header">
    <h3>{at('GLS utánvét')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <table>
                <tbody>
                <tr>
                    <td><label for="CsomagszamEdit">{at('Csomagszám')}:</label></td>
                    <td><input id="CsomagszamEdit" type="text" size="30" value="{$egyed.csomagszam}" disabled></td>
                </tr>
                <tr>
                    <td><label for="StatuszEdit">{at('Státusz')}:</label></td>
                    <td><input id="StatuszEdit" type="text" size="30" value="{$egyed.statusz}" disabled></td>
                </tr>
                <tr>
                    <td><label for="FelvetelEdit">{at('Felvétel dátuma')}:</label></td>
                    <td><input id="FelvetelEdit" type="text" value="{$egyed.felvetelstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="StatuszdatumEdit">{at('Státusz dátuma')}:</label></td>
                    <td><input id="StatuszdatumEdit" type="text" value="{$egyed.statuszdatumstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="RegisztraltosszegEdit">{at('Regisztrált utánvét')}:</label></td>
                    <td><input id="RegisztraltosszegEdit" type="text" value="{$egyed.regisztraltosszeg}" disabled></td>
                </tr>
                <tr>
                    <td><label for="OsszegEdit">{at('Beszedett utánvét')}:</label></td>
                    <td><input id="OsszegEdit" type="text" value="{$egyed.osszeg}" disabled></td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Címzett neve')}:</label></td>
                    <td><input id="NevEdit" type="text" size="60" value="{$egyed.nev}" disabled></td>
                </tr>
                <tr>
                    <td><label for="AtvevoEdit">{at('Átvevő neve')}:</label></td>
                    <td><input id="AtvevoEdit" type="text" size="60" value="{$egyed.atvevo}" disabled></td>
                </tr>
                <tr>
                    <td><label for="CimEdit">{at('Cím')}:</label></td>
                    <td><input id="CimEdit" type="text" size="60" value="{$egyed.cim}" disabled></td>
                </tr>
                <tr>
                    <td><label for="UgyfelhivatkozasEdit">{at('Ügyfél hivatkozás')}:</label></td>
                    <td><input id="UgyfelhivatkozasEdit" type="text" size="60" value="{$egyed.ugyfelhivatkozas}" disabled></td>
                </tr>
                <tr>
                    <td><label for="UtanvethivatkozasEdit">{at('Utánvét hivatkozás')}:</label></td>
                    <td><input id="UtanvethivatkozasEdit" type="text" size="60" value="{$egyed.utanvethivatkozas}" disabled></td>
                </tr>
                <tr>
                    <td><label for="BizonylatszamokEdit">{at('Bizonylatszámok')}:</label></td>
                    <td><input id="BizonylatszamokEdit" name="bizonylatszamok" type="text" size="60" value="{$egyed.bizonylatszamok}"></td>
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
