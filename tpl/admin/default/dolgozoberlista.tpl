{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/dolgozober.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Bérek')}"></div>
        <div id="mattable-filterwrapper">
            <table>
                <tbody>
                <tr>
                    <td>
                        <label for="tolfilter">{at('Időszak')}:</label>
                        <input id="tolfilter" name="tolfilter" type="text" size="12">
                        <input id="igfilter" name="igfilter" type="text" size="12">
                        <label for="rontottfilter">{at('Rontott')}:</label>
                        <select id="rontottfilter" name="rontottfilter">
                            <option value="0">{at('Mindegy')}</option>
                            <option value="1" selected="selected">{at('nem rontott')}</option>
                            <option value="2">{at('rontott')}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="dolgozofilter">{at('Dolgozó')}:</label>
                        <select id="dolgozofilter" name="dolgozofilter">
                            <option value="">{at('válasszon')}</option>
                            {foreach $dolgozolist as $_mk}
                                <option value="{$_mk.id}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                        <label for="berjogcimfilter">{at('Jogcím')}:</label>
                        <select id="berjogcimfilter" name="berjogcimfilter">
                            <option value="">{at('válasszon')}</option>
                            {foreach $berjogcimlist as $_jc}
                                <option value="{$_jc.id}">{$_jc.caption|escape}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
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
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input class="js-maincheckbox" type="checkbox"></th>
                <th>{at('Dolgozó')}</th>
                <th>{at('Dátum')}</th>
                <th>{at('Jogcím')}</th>
                <th class="textalignright">{at('Összeg')}</th>
                <th>{at('Megjegyzés')}</th>
                <th>{at('Rögzítés')}</th>
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
