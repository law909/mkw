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
            {mezocsoport}
                {mezo cimke="Csomagszám" for="CsomagszamEdit"}
                    <input id="CsomagszamEdit" type="text" size="30" value="{$egyed.csomagszam}" disabled>
                {/mezo}
                {mezo cimke="Státusz" for="StatuszEdit"}
                    <input id="StatuszEdit" type="text" size="30" value="{$egyed.statusz}" disabled>
                {/mezo}
                {mezo cimke="Felvétel dátuma" for="FelvetelEdit"}
                    <input id="FelvetelEdit" type="text" value="{$egyed.felvetelstr}" disabled>
                {/mezo}
                {mezo cimke="Státusz dátuma" for="StatuszdatumEdit"}
                    <input id="StatuszdatumEdit" type="text" value="{$egyed.statuszdatumstr}" disabled>
                {/mezo}
                {mezo cimke="Regisztrált utánvét" for="RegisztraltosszegEdit"}
                    <input id="RegisztraltosszegEdit" type="text" value="{$egyed.regisztraltosszeg}" disabled>
                {/mezo}
                {mezo cimke="Beszedett utánvét" for="OsszegEdit"}
                    <input id="OsszegEdit" type="text" value="{$egyed.osszeg}" disabled>
                {/mezo}
                {mezo cimke="Címzett neve" for="NevEdit"}
                    <input id="NevEdit" type="text" size="60" value="{$egyed.nev}" disabled>
                {/mezo}
                {mezo cimke="Átvevő neve" for="AtvevoEdit"}
                    <input id="AtvevoEdit" type="text" size="60" value="{$egyed.atvevo}" disabled>
                {/mezo}
                {mezo cimke="Cím" for="CimEdit"}
                    <input id="CimEdit" type="text" size="60" value="{$egyed.cim}" disabled>
                {/mezo}
                {mezo cimke="Ügyfél hivatkozás" for="UgyfelhivatkozasEdit"}
                    <input id="UgyfelhivatkozasEdit" type="text" size="60" value="{$egyed.ugyfelhivatkozas}" disabled>
                {/mezo}
                {mezo cimke="Utánvét hivatkozás" for="UtanvethivatkozasEdit"}
                    <input id="UtanvethivatkozasEdit" type="text" size="60" value="{$egyed.utanvethivatkozas}" disabled>
                {/mezo}
                {mezo cimke="Bizonylatszámok" for="BizonylatszamokEdit"}
                    <input id="BizonylatszamokEdit" name="bizonylatszamok" type="text" size="60" value="{$egyed.bizonylatszamok}">
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
