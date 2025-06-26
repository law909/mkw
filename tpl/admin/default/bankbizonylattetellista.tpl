{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/bankbizonylattetel.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{$pagetitle}"></div>
        <div id="mattable-filterwrapper">
            <label for="idfilter">{at('Sorszám')}:</label>
            <input id="idfilter" name="idfilter" type="text" size="20" maxlength="20">
            <div class="matt-hseparator"></div>
            <div>
                <label for="datumtipusfilter">{at('Dátum')}:</label>
                <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
                <input id="datumigfilter" name="datumigfilter" type="text" size="12">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="bizonylatrontottfilter">{at('Rontott')}:</label>
                <select id="bizonylatrontottfilter" name="bizonylatrontottfilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($bizonylatrontottfilter === 1)} selected="selected"{/if}>{at('nem rontott')}</option>
                    <option value="2"{if ($bizonylatrontottfilter === 2)} selected="selected"{/if}>{at('rontott')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="erbizonylatszamfilter">{at('Er.biz.szám')}:</label>
                <input id="erbizonylatszamfilter" name="erbizonylatszamfilter" type="text" size="20">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="valutanemfilter">{at('Valutanem')}:</label>
                <select id="valutanemfilter" name="valutanemfilter">
                    <option value="0">{at('válasszon')}</option>
                    {foreach $valutanemlist as $valutanem}
                        <option value="{$valutanem.id}"{if ($valutanem.selected)} selected="selected"{/if}>{$valutanem.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="partnerfilter">{at('Partner')}:</label>
                <select id="partnerfilter" name="partnerfilter">
                    <option value="0">{at('válasszon')}</option>
                    {foreach $partnerlist as $partner}
                        <option value="{$partner.id}"{if ($partner.selected)} selected="selected"{/if}>{$partner.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="hivatkozottbizonylatfilter">{at('Hivatkozott bizonylat')}:</label>
                <input id="hivatkozottbizonylatfilter" name="hivatkozottbizonylatfilter" type="text" size="20">
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
        <div class="mattable-batch">
            {at('Csoportos művelet')} <select class="mattable-batchselect">
                <option value="">{at('válasszon')}</option>
                {foreach $batchesselect as $_batch}
                    <option value="{$_batch.id}">{$_batch.caption}</option>
                {/foreach}
            </select>
            <a href="#" class="mattable-batchbtn">{at('Futtat')}</a>
        </div>
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input id="maincheckbox" type="checkbox"></th>
                <th>{at('Bizonylat')}</th>
                <th>{at('Partner')}</th>
                <th>{at('Dátum')}</th>
                <th>{at('Jogcím')}</th>
                <th>{at('Hivatkozott bizonylat')}</th>
                <th>{at('Összeg')}</th>
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
