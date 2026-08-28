{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/clipboard.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/idopont.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Időpontok')}"></div>
        <div id="mattable-filterwrapper">
            <div>
                <label for="tipusfilter">{at('Típus')}: </label>
                <select id="tipusfilter" name="tipusfilter">
                    <option value="">{at('mindegy')}</option>
                    <option value="rendezveny">{at('Rendezvény')}</option>
                    <option value="idopont">{at('Időpont')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="nevfilter">{at('Név')}: </label>
                <input id="nevfilter" name="nevfilter" type="text" size="20">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="datumtolfilter">{at('Dátum -tól')}: </label>
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
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="idoponttemafilter">{at('Téma')}: </label>
                <select id="idoponttemafilter" name="idoponttemafilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $idoponttemalist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="jogahelyszinfilter">{at('Helyszín')}: </label>
                <select id="jogahelyszinfilter" name="jogahelyszinfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $jogahelyszinlist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="jogateremfilter">{at('Terem')}: </label>
                <select id="jogateremfilter" name="jogateremfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $jogateremlist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="idopontallapotfilter">{at('Állapot')}: </label>
                <select id="idopontallapotfilter" name="idopontallapotfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $idopontallapotlist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <select id="inaktivfilter" name="inaktivfilter">
                    <option value="0">{at('Aktív')}</option>
                    <option value="1">{at('Inaktív')}</option>
                    <option value="9">{at('Mindegy')}</option>
                </select>
                <select id="ismetlodofilter" name="ismetlodofilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Egyszeri')}</option>
                    <option value="1">{at('Ismétlődő')}</option>
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
                <th>{at('Időpont')}</th>
                <th>{at('Adatok')}</th>
                <th>{at('Állapot')}</th>
                <th>{at('Jellemzők')}</th>
                <th>{at('Teendők')}</th>
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
