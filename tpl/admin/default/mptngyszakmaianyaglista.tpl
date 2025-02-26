{extends "..\base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/mptngyszakmaianyag.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Szakmai anyagok')}"></div>
        <div id="mattable-filterwrapper">
            <div class="matt-hseparator"></div>
            <div>
                <label for="idfilter">{at('Azonosító')}</label>
                <input id="idfilter" name="idfilter" type="number">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="cimfilter">{at('Cím')}</label>
                <input id="cimfilter" name="cimfilter" type="text" size="30" maxlength="255">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="tulajfilter">{at('Tulajdonos')}: </label>
                <select id="tulajfilter" name="tulajdonosfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $tulajdonoslist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="elsoszerzofilter">{at('Első szerző')}: </label>
                <select id="elsoszerzofilter" name="elsoszerzofilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $elsoszerzolist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="szerzofilter">{at('Szerző')}: </label>
                <select id="szerzofilter" name="szerzofilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $szerzolist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="opponensfilter">{at('Opponens')}: </label>
                <select id="opponensfilter" name="opponensfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $opponenslist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="temafilter">{at('Téma')}: </label>
                <select id="temafilter" name="temafilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $temalist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="temakor1filter">{at('Témakör 1')}: </label>
                <select id="temakor1filter" name="temakor1filter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $temakor1list as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="tipusfilter">{at('Típus')}: </label>
                <select id="tipusfilter" name="tipusfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $tipuslist as $_gyarto}
                        <option
                            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="bekuldvefilter">{at('Beküldve')}:</label>
                <select id="bekuldvefilter" name="bekuldvefilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem')}</option>
                    <option value="1">{at('Igen')}</option>
                </select>
                <label for="biralatkeszfilter">{at('Bírálat')}:</label>
                <select id="biralatkeszfilter" name="biralatkeszfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nincs kész')}</option>
                    <option value="1">{at('Kész')}</option>
                </select>
                <label for="konferencianszerepelhetfilter">{at('Konferencián')}:</label>
                <select id="konferencianszerepelhetfilter" name="konferencianszerepelhetfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem szerepelhet')}</option>
                    <option value="1">{at('Szerepelhet')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="elsobiralokellfilter">{at('Első bíráló')}:</label>
                <select id=elsobiralokellfilter" name="elsobiralokellfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem kell')}</option>
                    <option value="1">{at('Kell')}</option>
                </select>
                <label for="masodikbiralokellfilter">{at('Második bíráló')}:</label>
                <select id="masodikbiralokellfilter" name="masodikbiralokellfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem kell')}</option>
                    <option value="1">{at('Kell')}</option>
                </select>
                <label for="pluszbiralokellfilter">{at('Harmadik bíráló')}:</label>
                <select id="pluszbiralokellfilter" name="pluszbiralokellfilter">
                    <option value="9">{at('Mindegy')}</option>
                    <option value="0">{at('Nem kell')}</option>
                    <option value="1">{at('Kell')}</option>
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
        <div class="mattable-batch">
            {at('Csoportos művelet')} <select class="mattable-batchselect">
                <option value="">{at('válasszon')}</option>
                {foreach $batchesselect as $_batch}
                    <option value="{$_batch.id}">{$_batch.caption}</option>
                {/foreach}
            </select>
        </div>
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input id="maincheckbox" type="checkbox"></th>
                <th>{at('Cím')}</th>
                <th>{at('Szerzők')}</th>
                <th>{at('Tartalom')}</th>
                <th>{at('Bírálók')}</th>
                <th>{at('Témakörök')}</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="mattable-body"></tbody>
        </table>
        <div class="mattable-batch">
            {at('Csoportos művelet')} <select class="mattable-batchselect">
                <option value="">{at('válasszon')}</option>
                {foreach $batchesselect as $_batch}
                    <option value="{$_batch.id}">{$_batch.caption}</option>
                {/foreach}
            </select>
        </div>
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