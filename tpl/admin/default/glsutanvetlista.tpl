{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/glsutanvet.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('GLS utánvétek')}"></div>
        <div id="mattable-filterwrapper">
            <label for="csomagszamfilter">{at('Csomagszám')}</label>
            <input id="csomagszamfilter" name="csomagszamfilter" type="text" size="20" maxlength="50">
            <label for="nevfilter">{at('Címzett neve')}</label>
            <input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
            {* select, nem checkbox: a mattable szűrőgyűjtője .val()-t olvas, ami a kipipálatlan
               checkboxra is a value attribútumot adja vissza, tehát az mindig bekapcsolva látszana *}
            <label for="parositatlanfilter">{at('Párosítás')}</label>
            <select id="parositatlanfilter" name="parositatlanfilter">
                <option value="">{at('mind')}</option>
                <option value="1">{at('csak a párosítatlanok')}</option>
            </select>
        </div>
        <div class="mattable-pagerwrapper">
            <div class="mattable-order">
                <label for="cos1">{at('Rendezés')}</label>
                <select id="cos1" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="mattable-batch">
            <a href="/admin/glsutanvet/viewupload" class="js-import">{at('Import')}</a>
            {* a bizonylatszám nélküli tételeken újra lefuttatja a keresést *}
            <a href="#" class="js-parosit">{at('Párosít')}</a>
        </div>
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input id="maincheckbox" type="checkbox"></th>
                <th>{at('Csomagszám')}</th>
                <th>{at('Státusz')}</th>
                <th>{at('Státusz dátuma')}</th>
                <th>{at('Beszedett összeg')}</th>
                <th>{at('Címzett')}</th>
                <th>{at('Cím')}</th>
                <th>{at('Bizonylatszámok')}</th>
                <th>{at('Hivatkozás')}</th>
            </tr>
            </thead>
            <tbody id="mattable-body"></tbody>
        </table>
        <div class="mattable-pagerwrapper ui-corner-bottom">
            <div class="mattable-order">
                <label for="cos2">{at('Rendezés')}</label>
                <select id="cos2" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div id="mattkarb">
    </div>
{/block}
