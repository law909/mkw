{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/idopontfoglalas.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Időpont foglalások')}"></div>
        <div id="mattable-filterwrapper">
            <div>
                <label for="partnernevfilter">{at('Név')}: </label>
                <input id="partnernevfilter" name="partnernevfilter" type="text" size="30" maxlength="255">
                <label for="partneremailfilter">{at('Email')}: </label>
                <input id="partneremailfilter" name="partneremailfilter" type="text" size="30" maxlength="255">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="datumtolfilter">{at('Alkalom -tól')}: </label>
                <input id="datumtolfilter" name="datumtolfilter" type="text" size="12">
                <label for="datumigfilter">{at('-ig')}: </label>
                <input id="datumigfilter" name="datumigfilter" type="text" size="12">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="dolgozofilter">{at('Tanár')}: </label>
                <select id="dolgozofilter" name="dolgozofilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $dolgozolist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
                <label for="idoponttemafilter">{at('Téma')}: </label>
                <select id="idoponttemafilter" name="idoponttemafilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $idoponttemalist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
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
                <th>{at('Alkalom')}</th>
                <th>{at('Foglaló')}</th>
                <th>{at('Foglalás ideje')}</th>
                <th>{at('Részvétel')}</th>
                <th>{at('Akciók')}</th>
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
