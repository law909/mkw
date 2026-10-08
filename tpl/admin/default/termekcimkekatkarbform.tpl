<div id="mattkarb-header">
    <h3>{at('Termékcímke csoport')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="100" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" step="1" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="Látható" for="LathatoEdit"}
                    <input id="LathatoEdit" name="lathato" type="checkbox"{if ($egyed.lathato)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Terméklapon látható" for="TermeklaponlathatoEdit"}
                    <input id="TermeklaponlathatoEdit" name="termeklaponlathato" type="checkbox"{if ($egyed.termeklaponlathato)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Termékszűrőben látható" for="TermekszurobenlathatoEdit"}
                    <input id="TermekszurobenlathatoEdit" name="termekszurobenlathato" type="checkbox"{if ($egyed.termekszurobenlathato)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Terméklistában látható" for="TermeklistabanlathatoEdit"}
                    <input id="TermeklistabanlathatoEdit" name="termeklistabanlathato" type="checkbox"{if ($egyed.termeklistabanlathato)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Termék akciódobozban látható" for="TermekakciodobozbanlathatoEdit"}
                    <input id="TermekakciodobozbanlathatoEdit" name="termekakciodobozbanlathato" type="checkbox"{if ($egyed.termekakciodobozbanlathato)} checked="checked"{/if}>
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
