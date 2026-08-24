<div id="mattkarb-header">
    <h3>{at('NAV import napló')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#NavadatTab">{at('Amit a NAV-tól kaptunk')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="CreatedEdit">{at('Időpont')}:</label></td>
                    <td><input id="CreatedEdit" type="text" size="30" value="{$egyed.createdstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="IdoszakEdit">{at('Lekért időszak')}:</label></td>
                    <td><input id="IdoszakEdit" type="text" size="30"
                               value="{$egyed.idoszaktolstr} - {$egyed.idoszakigstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="SzamlaszamEdit">{at('Számlaszám')}:</label></td>
                    <td><input id="SzamlaszamEdit" type="text" size="40" value="{$egyed.szamlaszam}" disabled></td>
                </tr>
                <tr>
                    <td><label for="SzallitoEdit">{at('Szállító')}:</label></td>
                    <td><input id="SzallitoEdit" type="text" size="60" value="{$egyed.szallito}" disabled></td>
                </tr>
                <tr>
                    <td><label for="StatuszEdit">{at('Státusz')}:</label></td>
                    <td><input id="StatuszEdit" type="text" size="20" value="{$egyed.statusz}" disabled></td>
                </tr>
                <tr>
                    <td><label for="BizonylatszamEdit">{at('Bizonylatszám')}:</label></td>
                    <td><input id="BizonylatszamEdit" type="text" size="40" value="{$egyed.bizonylatszam}" disabled></td>
                </tr>
                <tr>
                    <td><label for="FejhibaEdit">{at('Probléma a fej adatokkal')}:</label></td>
                    <td><textarea id="FejhibaEdit" rows="5" cols="80" disabled>{$egyed.fejhiba}</textarea></td>
                </tr>
                <tr>
                    <td><label for="TetelhibaEdit">{at('Probléma a tétel adatokkal')}:</label></td>
                    <td><textarea id="TetelhibaEdit" rows="8" cols="80" disabled>{$egyed.tetelhiba}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="NavadatTab" class="mattkarb-page">
            <textarea id="NavadatEdit" rows="30" cols="120" disabled>{$egyed.navadat}</textarea>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <a id="mattkarb-cancelbutton" href="#">{at('Bezár')}</a>
    </div>
</form>
