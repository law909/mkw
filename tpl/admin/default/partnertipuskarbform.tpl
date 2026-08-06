<div id="mattkarb-header">
    <h3>{at('Partner típus')}</h3>
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
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="BelephetEdit">{at('Beléphet')}:</label></td>
                    <td><input id="BelephetEdit" name="belephet" type="checkbox"{if ($egyed.belephet)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet2Edit">{at('Beléphet 2')}:</label></td>
                    <td><input id="Belephet2Edit" name="belephet2" type="checkbox"{if ($egyed.belephet2)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet3Edit">{at('Beléphet 3')}:</label></td>
                    <td><input id="Belephet3Edit" name="belephet3" type="checkbox"{if ($egyed.belephet3)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet4Edit">{at('Beléphet 4')}:</label></td>
                    <td><input id="Belephet4Edit" name="belephet4" type="checkbox"{if ($egyed.belephet4)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet5Edit">{at('Beléphet 5')}:</label></td>
                    <td><input id="Belephet5Edit" name="belephet5" type="checkbox"{if ($egyed.belephet5)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet6Edit">{at('Beléphet 6')}:</label></td>
                    <td><input id="Belephet6Edit" name="belephet6" type="checkbox"{if ($egyed.belephet6)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet7Edit">{at('Beléphet 7')}:</label></td>
                    <td><input id="Belephet7Edit" name="belephet7" type="checkbox"{if ($egyed.belephet7)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet8Edit">{at('Beléphet 8')}:</label></td>
                    <td><input id="Belephet8Edit" name="belephet8" type="checkbox"{if ($egyed.belephet8)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet9Edit">{at('Beléphet 9')}:</label></td>
                    <td><input id="Belephet9Edit" name="belephet9" type="checkbox"{if ($egyed.belephet9)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet10Edit">{at('Beléphet 10')}:</label></td>
                    <td><input id="Belephet10Edit" name="belephet10" type="checkbox"{if ($egyed.belephet10)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet11Edit">{at('Beléphet 11')}:</label></td>
                    <td><input id="Belephet11Edit" name="belephet11" type="checkbox"{if ($egyed.belephet11)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet12Edit">{at('Beléphet 12')}:</label></td>
                    <td><input id="Belephet12Edit" name="belephet12" type="checkbox"{if ($egyed.belephet12)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet13Edit">{at('Beléphet 13')}:</label></td>
                    <td><input id="Belephet13Edit" name="belephet13" type="checkbox"{if ($egyed.belephet13)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet14Edit">{at('Beléphet 14')}:</label></td>
                    <td><input id="Belephet14Edit" name="belephet14" type="checkbox"{if ($egyed.belephet14)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="Belephet15Edit">{at('Beléphet 15')}:</label></td>
                    <td><input id="Belephet15Edit" name="belephet15" type="checkbox"{if ($egyed.belephet15)} checked="checked"{/if}></td>
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
