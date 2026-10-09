{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jelenletiivgen.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Munkaidő nyilvántartás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Munkaidő nyilvántartás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="jelenletiivgen" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {mezo cimke="Munkakörök" szeles=true}
                            <div class="mattkarb-pipak js-munkakorok">
                                {foreach $munkakorlist as $_mk}
                                    <span class="mattkarb-pipacimke">
                                        <input id="Munkakor{$_mk.id}Edit" type="checkbox" name="munkakor[]" value="{$_mk.id}">
                                        <label for="Munkakor{$_mk.id}Edit">{$_mk.caption}</label>
                                    </span>
                                {/foreach}
                            </div>
                            <div class="mattkarb-megjegyzes">{at('Ha nincs munkakör kipipálva, minden munkakör dolgozója látszik.')}</div>
                        {/mezo}
                        {include "comp_dolgozoselect.tpl" mezo=true ujsor=true}
                        {mezo szeles=true}
                            <span class="mattkarb-megjegyzes">{at('Dolgozó nélkül a kiválasztott munkakörök minden aktív dolgozójának nyilvántartása elkészül.')}</span>
                        {/mezo}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/jelenletiivgen/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/jelenletiivgen/matrix" class="js-okbutton">{at('Mátrix')}</a>
                        <a href="/admin/jelenletiivgen/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
