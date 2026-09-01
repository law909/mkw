{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/idopontfoglalas.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Időpont jelentkezések')}"></div>
        <div id="mattable-filterwrapper">
            <div>
                <label for="tipusfilter">{at('Típus')}: </label>
                <select id="tipusfilter" name="tipusfilter">
                    <option value="">{at('mindegy')}</option>
                    <option value="rendezveny">{at('Rendezvény')}</option>
                    <option value="idopont">{at('Időpont')}</option>
                </select>
                <label for="idfilter">{at('Azonosító')}: </label>
                <input id="idfilter" name="idfilter" type="text" size="8">
            </div>
            <div class="matt-hseparator"></div>
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
            <div class="matt-hseparator"></div>
            <div>
                <label for="fizmodfilter">{at('Fizetési mód')}: </label>
                <select id="fizmodfilter" name="fizmodfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $fizmodlist as $_d}
                        <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                    {/foreach}
                </select>
                <label for="varolistasfilter">{at('Várólista')}: </label>
                <select id="varolistasfilter" name="varolistasfilter">
                    <option value="9">{at('mindegy')}</option>
                    <option value="0">{at('nem')}</option>
                    <option value="1">{at('igen')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="idopontfilter">{at('Időpont')}: </label>
                <select id="idopontfilter" name="idopontfilter">
                    <option value="">{at('válasszon')}</option>
                    {foreach $idopontlist as $_d}
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
                <th>{at('Állapot')}</th>
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
    <form id="fizetform" class="hidden">
        <div>
            <label for="afizetfizmodedit">{at('Fizetési mód')}:</label>
            <select id="afizetfizmodedit" name="afizetfizmod">
                <option value="0">{at('válasszon')}</option>
                {foreach $fizmodlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetjogcimedit">{at('Jogcím')}:</label>
            <select id="afizetjogcimedit" name="afizetjogcim">
                <option value="0">{at('válasszon')}</option>
                {foreach $jogcimlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetpenztaredit">{at('Pénztár')}:</label>
            <select id="afizetpenztaredit" name="afizetpenztar">
                <option value="0">{at('válasszon')}</option>
                {foreach $penztarlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetbankszamlaedit">{at('Bankszámla')}:</label>
            <select id="afizetbankszamlaedit" name="afizetbankszamla">
                <option value="0">{at('válasszon')}</option>
                {foreach $bankszamlalist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetdatumedit">{at('Dátum')}:</label>
            <input id="afizetdatumedit" name="afizetdatum" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetosszegedit">{at('Összeg')}:</label>
            <input id="afizetosszegedit" name="afizetosszeg" type="text">
        </div>
    </form>
    <form id="szamlazform" class="hidden">
        <div>
            <label for="aszamlazbiztipusedit">{at('Bizonylattípus')}:</label>
            <input id="aszamlazbiztipusedit" name="aszamlazbiztipus" type="radio" value="szamla" checked="checked">{at('Számla')}
            <input name="aszamlazbiztipus" type="radio" value="egyeb">{at('Egyéb mozgás')}
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazkeltedit">{at('Kelt')}:</label>
            <input id="aszamlazkeltedit" name="aszamlazkelt" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazteljesitesedit">{at('Teljesítés')}:</label>
            <input id="aszamlazteljesitesedit" name="aszamlazteljesites" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazosszegedit">{at('Összeg')}:</label>
            <input id="aszamlazosszegedit" name="aszamlazosszeg" type="text">
        </div>
    </form>
    <form id="lemondform" class="hidden">
        <div>
            <label for="alemondasokaedit">{at('Lemondás oka')}:</label>
            <textarea id="alemondasokaedit" name="alemondasoka"></textarea>
        </div>
    </form>
{/block}
