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
            {mezocsoport}
                {mezo cimke="Időpont" for="CreatedEdit"}
                    <input id="CreatedEdit" type="text" size="30" value="{$egyed.createdstr}" disabled>
                {/mezo}
                {mezo cimke="Lekért időszak" for="IdoszakEdit"}
                    <input id="IdoszakEdit" type="text" size="30"
                        value="{$egyed.idoszaktolstr} - {$egyed.idoszakigstr}" disabled>
                {/mezo}
                {mezo cimke="Számlaszám" for="SzamlaszamEdit"}
                    <input id="SzamlaszamEdit" type="text" size="40" value="{$egyed.szamlaszam}" disabled>
                {/mezo}
                {mezo cimke="Szállító" for="SzallitoEdit"}
                    <input id="SzallitoEdit" type="text" size="60" value="{$egyed.szallito}" disabled>
                {/mezo}
                {mezo cimke="Státusz" for="StatuszEdit"}
                    <input id="StatuszEdit" type="text" size="20" value="{$egyed.statusz}" disabled>
                {/mezo}
                {mezo cimke="Bizonylatszám" for="BizonylatszamEdit"}
                    <input id="BizonylatszamEdit" type="text" size="40" value="{$egyed.bizonylatszam}" disabled>
                {/mezo}
                {mezo cimke="Probléma a fej adatokkal" for="FejhibaEdit"}
                    <textarea id="FejhibaEdit" rows="5" cols="80" disabled>{$egyed.fejhiba}</textarea>
                {/mezo}
                {mezo cimke="Probléma a tétel adatokkal" for="TetelhibaEdit"}
                    <textarea id="TetelhibaEdit" rows="8" cols="80" disabled>{$egyed.tetelhiba}</textarea>
                {/mezo}
            {/mezocsoport}
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
