<div id="mattkarb-header">
    <h3>{at('Cron futás')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Feladat"}
                    {$egyed.feladat}
                {/mezo}
                {mezo cimke="Állapot"}
                    {$egyed.allapot}
                {/mezo}
                {mezo cimke="Kezdet"}
                    {$egyed.kezdet}
                {/mezo}
                {mezo cimke="Vég"}
                    {$egyed.veg}
                {/mezo}
                {mezo cimke="Időtartam"}
                    {$egyed.idotartam}
                {/mezo}
                {mezo cimke="Gép"}
                    {$egyed.host} (pid {$egyed.pid})
                {/mezo}
                {mezo cimke="Üzenet" for="UzenetEdit"}
                    <textarea id="UzenetEdit" rows="8" cols="80" readonly="readonly">{$egyed.uzenet}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <div class="mattkarb-footer">
        <a id="mattkarb-cancelbutton" href="#">{at('Bezár')}</a>
    </div>
</form>
