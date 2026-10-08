<div id="mattkarb-header">
    <h3>{at('Változatból termék')}</h3>
</div>
<form id="mattkarb-form" method="post" action="">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TermekTab">{at('Termék előtte')}</a></li>
            <li><a href="#ValtozatTab">{at('Változat előtte')}</a></li>
            <li><a href="#UjtermekTab">{at('Új termék')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Időpont"}{$egyed.createdstr}{/mezo}
                {mezo cimke="Dolgozó"}{$egyed.createdbynev}{/mezo}
                {mezo cimke="Termék" szeles=true}
                    {if ($egyed.termekid)}<a href="/admin/termek/viewkarb?id={$egyed.termekid}&oper=edit" target="_blank">{$egyed.termeknev}</a>{else}{$egyed.termeknev} ({at('törölve')}){/if}
                {/mezo}
                {mezo cimke="Változat"}{$egyed.valtozatnev} (#{$egyed.termekvaltozatid}){/mezo}
                {mezo cimke="Új termék" szeles=true}
                    {if ($egyed.ujtermekid)}<a href="/admin/termek/viewkarb?id={$egyed.ujtermekid}&oper=edit" target="_blank">{$egyed.ujtermeknev}</a>{else}{$egyed.ujtermeknev} ({at('törölve')}){/if}
                {/mezo}
                {mezo cimke="Képek másolása"}{if ($egyed.kepekmasolasa)}{at('igen')}{else}{at('nem')}{/if}{/mezo}
                {mezo cimke="Dokumentumok másolása"}{if ($egyed.dokumentumokmasolasa)}{at('igen')}{else}{at('nem')}{/if}{/mezo}
                {mezo cimke="Árak másolása"}{if ($egyed.arakmasolasa)}{at('igen')}{else}{at('nem')}{/if}{/mezo}
            {/mezocsoport}
            {mezocsoport cim="Átírt és törölt sorok"}
                {foreach $egyed.atiras as $_sor}
                    {mezo cimke=$_sor.tabla nyers=true szeles=true}{$_sor.db}{if ($_sor.db)}: {$_sor.idk}{/if}{/mezo}
                {/foreach}
            {/mezocsoport}
        </div>
        <div id="TermekTab" class="mattkarb-page" data-visible="visible">
            <pre class="naplo-json">{$egyed.termekjson}</pre>
        </div>
        <div id="ValtozatTab" class="mattkarb-page" data-visible="visible">
            <pre class="naplo-json">{$egyed.valtozatjson}</pre>
        </div>
        <div id="UjtermekTab" class="mattkarb-page" data-visible="visible">
            <pre class="naplo-json">{$egyed.ujtermekjson}</pre>
        </div>
    </div>
    <div class="mattkarb-footer">
        <a id="mattkarb-cancelbutton" href="#">{at('Bezár')}</a>
    </div>
</form>
