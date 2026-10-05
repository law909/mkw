{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/konyvelo.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}" data-tipus="{$tipus}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{$pagetitle}"></div>
        <div id="mattable-filterwrapper">
            <label for="idfilter">{at('Sorszám')}:</label>
            <input id="idfilter" name="idfilter" type="text" size="20" maxlength="30">
            <div class="matt-hseparator"></div>
            <div>
                <label for="vevonevfilter">{at('Vevő neve')}:</label>
                <input id="vevonevfilter" name="vevonevfilter" type="text" size="30">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="datumtipusfilter">{at('Dátum')}:</label>
                <select id="datumtipusfilter" name="datumtipusfilter">
                    <option value="1">{at('Kelt')}</option>
                    <option value="2">{at('Teljesítés')}</option>
                    <option value="3">{at('Esedékesség')}</option>
                </select>
                <input id="datumtolfilter" name="datumtolfilter" type="text" size="12">
                <input id="datumigfilter" name="datumigfilter" type="text" size="12">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="bizonylatstornofilter">{at('Stornó')}:</label>
                <select id="bizonylatstornofilter" name="bizonylatstornofilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1">{at('nem stornózott')}</option>
                    <option value="2">{at('stornózott')}</option>
                </select>
            </div>
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
                <th>{at('Bizonylat')}</th>
                <th>{at('Vevő')}</th>
                <th>{at('Dátumok')}</th>
                <th>{at('Összegek')}</th>
                <th>{at('Bank, pénztár')}</th>
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
{/block}
