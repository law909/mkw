{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/navadatexport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Adóhatósági ellenőrzési adatszolgáltatás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="navadatexport" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        {mezo cimke="Számlaszám" for="SzamlaszamTolEdit" szeles=true}
                            <div class="mattkarb-mezogomb kimutatas-idoszak">
                                <input id="SzamlaszamTolEdit" name="szamlaszamtol" type="text">
                                <span>–</span>
                                <input id="SzamlaszamIgEdit" name="szamlaszamig" type="text">
                            </div>
                        {/mezo}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/navadatexport/get" class="js-okbutton">{at('OK')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}