{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/cronlog.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Cron napló')}"></div>
        <div id="mattable-filterwrapper">
            <label for="feladatfilter">{at('Feladat')}</label>
            <select id="feladatfilter" name="feladatfilter">
                <option value="">{at('mind')}</option>
                {foreach $feladatselect as $_feladat}
                    <option value="{$_feladat}">{$_feladat}</option>
                {/foreach}
            </select>
            <label for="allapotfilter">{at('Állapot')}</label>
            <select id="allapotfilter" name="allapotfilter">
                <option value="">{at('mind')}</option>
                {foreach $allapotselect as $_allapot}
                    <option value="{$_allapot}">{$_allapot}</option>
                {/foreach}
            </select>
            <label for="nevfilter">{at('Üzenet')}</label>
            <input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
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
                <th>{at('Kezdet')}</th>
                <th>{at('Feladat')}</th>
                <th>{at('Állapot')}</th>
                <th>{at('Időtartam')}</th>
                <th>{at('Üzenet')}</th>
            </tr>
            </thead>
            <tbody id="mattable-body"></tbody>
        </table>
        <div class="mattable-pagerwrapper ui-corner-bottom">
            <div class="mattable-order">
                <label for="cos1">{at('Rendezés')}</label>
                <select id="cos1" class="mattable-orderselect">
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
