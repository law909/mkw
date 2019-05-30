{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/ajaxupload.js"></script>
    <script type="text/javascript" src="/js/admin/default/orarendhelyettesites.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Helyettesítések')}"></div>
        <div id="mattable-filterwrapper">
            <div class="matt-hseparator"></div>
            <div>
                <select id="inaktivfilter" name="inaktivfilter">
                    <option value="0">{at('Aktív')}</option>
                    <option value="1">{at('Inaktív')}</option>
                    <option value="9">{at('Mindegy')}</option>
                </select>
                <select id="elmaradfilter" name="elmaradfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem marad el')}</option>
                    <option value="1">{at('Elmarad')}</option>
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
                <th><input class="js-maincheckbox" type="checkbox"></th>
                <th>{at('Név')}</th>
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