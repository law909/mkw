<div id="mattkarb-header">
    <h3>{at('Partner típus')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Beléphet" for="BelephetEdit"}
                    <input id="BelephetEdit" name="belephet" type="checkbox"{if ($egyed.belephet)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 2" for="Belephet2Edit"}
                    <input id="Belephet2Edit" name="belephet2" type="checkbox"{if ($egyed.belephet2)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 3" for="Belephet3Edit"}
                    <input id="Belephet3Edit" name="belephet3" type="checkbox"{if ($egyed.belephet3)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 4" for="Belephet4Edit"}
                    <input id="Belephet4Edit" name="belephet4" type="checkbox"{if ($egyed.belephet4)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 5" for="Belephet5Edit"}
                    <input id="Belephet5Edit" name="belephet5" type="checkbox"{if ($egyed.belephet5)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 6" for="Belephet6Edit"}
                    <input id="Belephet6Edit" name="belephet6" type="checkbox"{if ($egyed.belephet6)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 7" for="Belephet7Edit"}
                    <input id="Belephet7Edit" name="belephet7" type="checkbox"{if ($egyed.belephet7)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 8" for="Belephet8Edit"}
                    <input id="Belephet8Edit" name="belephet8" type="checkbox"{if ($egyed.belephet8)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 9" for="Belephet9Edit"}
                    <input id="Belephet9Edit" name="belephet9" type="checkbox"{if ($egyed.belephet9)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 10" for="Belephet10Edit"}
                    <input id="Belephet10Edit" name="belephet10" type="checkbox"{if ($egyed.belephet10)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 11" for="Belephet11Edit"}
                    <input id="Belephet11Edit" name="belephet11" type="checkbox"{if ($egyed.belephet11)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 12" for="Belephet12Edit"}
                    <input id="Belephet12Edit" name="belephet12" type="checkbox"{if ($egyed.belephet12)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 13" for="Belephet13Edit"}
                    <input id="Belephet13Edit" name="belephet13" type="checkbox"{if ($egyed.belephet13)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 14" for="Belephet14Edit"}
                    <input id="Belephet14Edit" name="belephet14" type="checkbox"{if ($egyed.belephet14)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Beléphet 15" for="Belephet15Edit"}
                    <input id="Belephet15Edit" name="belephet15" type="checkbox"{if ($egyed.belephet15)} checked="checked"{/if}>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
