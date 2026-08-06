<div id="mattkarb-header">
    <h3>{at('Termékcímke csoport')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="100" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" step="1" value="{$egyed.sorrend}"></td>
                </tr>
                <tr>
                    <td><label for="LathatoEdit">{at('Látható')}:</label></td>
                    <td><input id="LathatoEdit" name="lathato" type="checkbox"{if ($egyed.lathato)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TermeklaponlathatoEdit">{at('Terméklapon látható')}:</label></td>
                    <td><input id="TermeklaponlathatoEdit" name="termeklaponlathato" type="checkbox"{if ($egyed.termeklaponlathato)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TermekszurobenlathatoEdit">{at('Termékszűrőben látható')}:</label></td>
                    <td><input id="TermekszurobenlathatoEdit" name="termekszurobenlathato" type="checkbox"{if ($egyed.termekszurobenlathato)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TermeklistabanlathatoEdit">{at('Terméklistában látható')}:</label></td>
                    <td><input id="TermeklistabanlathatoEdit" name="termeklistabanlathato" type="checkbox"{if ($egyed.termeklistabanlathato)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TermekakciodobozbanlathatoEdit">{at('Termék akciódobozban látható')}:</label></td>
                    <td><input id="TermekakciodobozbanlathatoEdit" name="termekakciodobozbanlathato" type="checkbox"{if ($egyed.termekakciodobozbanlathato)} checked="checked"{/if}></td>
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
