{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/glsutanvetupload.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('GLS utánvét import')}</h3>
        </div>
        <form id="mattkarb-form" method="post" action="/admin/glsutanvet/upload">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
                </ul>
                <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
                    <div>
                        <label for="fileedit">{at('GLS kimutatás')}:</label>
                        <input id="fileedit" name="toimport" type="file" accept=".xlsx,.xls">
                    </div>
                    <p class="mattkarb-hint">
                        {at('A GLS csomag státusz listáját ("Actual pcl statuses") és a napi utalási jelentését ("Daily") is fogadja – magától felismeri, melyikről van szó. Csak azok a sorok kerülnek be, amelyeken van beszedett utánvét összeg; a már beimportált csomagszámok kimaradnak, akkor is, ha a másik kimutatásból jöttek. Importáláskor a program megpróbálja megkeresni a befizetéshez tartozó bizonylatot – először a fuvarlevélszám, utána a név, az összeg és a cím alapján.')}
                    </p>
                </div>
            </div>
            <div class="mattkarb-footer">
                <a id="mattkarb-okbutton" href="#" class="js-upload">{at('OK')}</a>
            </div>
        </form>
    </div>
{/block}
