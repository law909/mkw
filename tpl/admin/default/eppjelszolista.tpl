{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/eppjelszo.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('WordPress oldal jelszavak')}"></div>
        <div id="mattable-filterwrapper">
            <div>
                <label for="oldalidfilter">{at('Oldal ID')}: </label>
                <input id="oldalidfilter" name="oldalidfilter" type="number" min="1" style="width: 8em">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="szovegfilter">{at('Név, email, megjegyzés')}: </label>
                <input id="szovegfilter" name="szovegfilter" type="text" size="30" maxlength="255">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <select id="allapotfilter" name="allapotfilter">
                    <option value="">{at('Mindegy')}</option>
                    <option value="aktiv" selected="selected">{at('Aktív')}</option>
                    <option value="lejart">{at('Lejárt')}</option>
                    <option value="visszavonva">{at('Visszavonva')}</option>
                </select>
            </div>
        </div>
        <div class="mattable-pagerwrapper">
            <div class="mattable-order">
                <label for="tos1">{at('Rendezés')}</label>
                <select id="tos1" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input class="js-maincheckbox" type="checkbox"></th>
                <th>{at('Oldal ID')}</th>
                <th>{at('Kinek')}</th>
                <th>{at('Megjegyzés')}</th>
                <th>{at('Lejárat')}</th>
                <th>{at('Állapot')}</th>
                <th>{at('Létrehozva')}</th>
                <th>{at('Azonosító')}</th>
            </tr>
            </thead>
            <tbody id="mattable-body"></tbody>
        </table>
        <div class="mattable-pagerwrapper ui-corner-bottom">
            <div class="mattable-order">
                <label for="tos2">{at('Rendezés')}</label>
                <select id="tos2" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div id="mattkarb"></div>
{/block}
