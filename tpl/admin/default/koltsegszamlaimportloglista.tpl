{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/koltsegszamlaimportlog.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('NAV import napló')}"></div>
        <div id="mattable-filterwrapper">
            <label for="szamlaszamfilter">{at('Számlaszám')}</label>
            <input id="szamlaszamfilter" name="szamlaszamfilter" type="text" size="20" maxlength="255">
            <label for="szallitofilter">{at('Szállító')}</label>
            <input id="szallitofilter" name="szallitofilter" type="text" size="30" maxlength="255">
            <label for="statuszfilter">{at('Státusz')}</label>
            <select id="statuszfilter" name="statuszfilter">
                <option value="">{at('mind')}</option>
                <option value="uj">{at('új')}</option>
                <option value="letezo">{at('már megvolt')}</option>
                <option value="hiba">{at('hiba')}</option>
            </select>
            <label for="hibasfilter">{at('Probléma')}</label>
            <select id="hibasfilter" name="hibasfilter">
                <option value="">{at('mind')}</option>
                <option value="1">{at('csak amiben volt probléma')}</option>
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
        <table id="mattable-table">
            <thead>
            <tr>
                <th>{at('Időpont')}</th>
                <th>{at('Számlaszám')}</th>
                <th>{at('Szállító')}</th>
                <th>{at('Státusz')}</th>
                <th>{at('Bizonylatszám')}</th>
                <th>{at('Probléma a fej adatokkal')}</th>
                <th>{at('Probléma a tétel adatokkal')}</th>
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
