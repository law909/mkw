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
    <script type="text/javascript" src="/js/admin/default/termek.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Termékek')}"></div>
        <div id="mattable-filterwrapper">
            <div class="matt-hseparator"></div>
            <div>
                <label for="nevfilter">{at('Név')}: </label>
                <input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
                <label for="kepurlfilter">{at('Főkép url')}: </label>
                <input id="kepurlfilter" name="kepurlfilter" type="text" size="30" maxlength="255">
                <label for="gyartofilter">{at('Gyártó')}: </label>
                <select id="gyartofilter" name="gyartofilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $gyartolist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <select id="lathatofilter" name="lathatofilter">
                    {if ($maintheme == 'mkwcansas')}
                        <option value="1">{at('Látható')}</option>
                        <option value="0">{at('Nem látható')}</option>
                        <option value="9">{at('Mindegy')}</option>
                    {else}
                        <option value="9">{at('Mindegy')}</option>
                        <option value="1">{at('Látható')}</option>
                        <option value="0">{at('Nem látható')}</option>
                    {/if}
                </select>
                <select id="nemkaphatofilter" name="nemkaphatofilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="1">{at('Nem kapható')}</option>
                    <option value="0">{at('Kapható')}</option>
                </select>
                <select id="fuggobenfilter" name="fuggobenfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="1">{at('Függőben')}</option>
                    <option value="0">{at('Nincs függőben')}</option>
                </select>
                <select id="inaktivfilter" name="inaktivfilter">
                    <option value="0">{at('Aktív')}</option>
                    <option value="1">{at('Inaktív')}</option>
                    <option value="9">{at('Mindegy')}</option>
                </select>
                <select id="ajanlottfilter" name="ajanlottfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="1">{at('Ajánlott')}</option>
                    <option value="0">{at('Nem ajánlott')}</option>
                </select>
                <select id="kiemeltfilter" name="kiemeltfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="1">{at('Kiemelt')}</option>
                    <option value="0">{at('Nem kiemelt')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div id="termekfa" class="mattable-filterwrapper ui-widget-content"></div>
            <div class="matt-hseparator"></div>
            <div id="cimkefiltercontainer">
                <div id="cimkefiltercontainerhead"><a id="cimkefiltercollapse" href="#"
                                                      data-visible="visible">{at('Kinyit/becsuk')}</a></div>
                {foreach $cimkekat as $_cimkekat}
                    <div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkefiltercloseupbutton"
                         data-refcontrol="#{$_cimkekat.sanitizedcaption}">
                        <a href="#" class="mattedit-titlebar-close">
                            <span class="ui-icon ui-icon-circle-triangle-n"></span>
                        </a>
                        <span>{$_cimkekat.caption}</span>
                    </div>
                    <div id="{$_cimkekat.sanitizedcaption}" class="accordpage cimkelista" data-visible="visible">
                        {foreach $_cimkekat.cimkek as $_cimke}
                            <a class="js-cimkefilter" href="#" data-id="{$_cimke.id}">{$_cimke.caption}</a>
                            &nbsp;&nbsp;
                        {/foreach}
                    </div>
                {/foreach}
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
                <th>{at('Címkék')}</th>
                <th>{at('Készlet')}</th>
                <th>{at('Jellemzők')}</th>
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
    <div id="termekfakarb"></div>
    <div id="tcsset" class="hidden">
        <label>Termékcsoport: </label>
        <select class="js-tcsset">
            <option value="">{at('válasszon')}</option>
        {foreach $termekcsoportlist as $_tcs}
            <option value="{$_tcs.id}">{$_tcs.caption}</option>
        {/foreach}
        </select>
    </div>
{/block}