{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/partnertermekkategoriakedvezmenynaplo.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{$pagetitle}"></div>
        <div id="mattable-filterwrapper">
            <label for="partnerfilter">{at('Partner')}:</label>
            <input id="partnerfilter" name="partnerfilter" type="text" size="30" maxlength="255">
            <label for="termekfafilter">{at('Termékkategória')}:</label>
            <input id="termekfafilter" name="termekfafilter" type="text" size="30" maxlength="255">
            <label for="datumtolfilter">{at('Dátum')}:</label>
            <input id="datumtolfilter" name="datumtolfilter" type="text" size="12">
            <input id="datumigfilter" name="datumigfilter" type="text" size="12">
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
                <th><input id="maincheckbox" type="checkbox"></th>
                <th>{at('Időpont')}</th>
                <th>{at('Partner')}</th>
                <th>{at('Termékkategória')}</th>
                <th>{at('Esemény')}</th>
                <th>{at('Régi kedvezmény')}</th>
                <th>{at('Új kedvezmény')}</th>
                <th>{at('Módosította')}</th>
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
