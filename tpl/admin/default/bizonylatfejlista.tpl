{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylathelper.js?v=2"></script>
    <script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
    <form id="exportform" method="POST"><input type="hidden" name="ids"></form>
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{$pagetitle}"></div>
        <div id="mattable-filterwrapper">
            <label for="idfilter">{at('Sorszám')}:</label>
            <input id="idfilter" name="idfilter" type="text" size="20" maxlength="20">
            <label for="hashidfilter">{at('Hash')}:</label>
            <input id="hashidfilter" name="hashidfilter" type="text" size="20" maxlength="10">
            <div class="matt-hseparator"></div>
            <div>
                <label for="vevonevfilter">{at('Vevő név')}:</label>
                <input id="vevonevfilter" name="vevonevfilter" type="text">
                <label for="vevoemailfilter">{at('Vevő email')}:</label>
                <input id="vevoemailfilter" name="vevoemailfilter" type="text">
                <label for="vevotelefonfilter">{at('Vevő telefon')}:</label>
                <input id="vevotelefonfilter" name="vevotelefonfilter" type="text">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="szallitasiirszamfilter">{at('Szállítási cím')}:</label>
                <input id="szallitasiirszamfilter" name="szallitasiirszamfilter" type="text" size="8">
                <input id="szallitasivarosfilter" name="szallitasivarosfilter" type="text">
                <input id="szallitasiutcafilter" name="szallitasiutcafilter" type="text">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="szamlazasiirszamfilter">{at('Számlázási cím')}:</label>
                <input id="szamlazasiirszamfilter" name="szamlazasiirszamfilter" type="text" size="8">
                <input id="szamlazasivarosfilter" name="szamlazasivarosfilter" type="text">
                <input id="szamlazasiutcafilter" name="szamlazasiutcafilter" type="text">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="datumtipusfilter">{at('Dátum')}:</label>
                <select id="datumtipusfilter" name="datumtipusfilter"{if (!haveJog(20))} disabled="disabled"{/if}>
                    <option value="1">{at('kelt')}</option>
                    <option value="2">{at('teljesítés')}</option>
                    {if ($showesedekesseg)}
                        <option value="3">{at('esedékesség')}</option>
                    {/if}
                </select>
                <input id="datumtolfilter" name="datumtolfilter" type="text" size="12"
                       data-datum="{$datumtolfilter|default}"{if (!haveJog(20))} disabled="disabled"{/if}>
                <input id="datumigfilter" name="datumigfilter" type="text" size="12"{if (!haveJog(20))} disabled="disabled"{/if}>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="osszegtolfilter">{at('Összeg')}:</label>
                <input id="osszegtolfilter" name="osszegtolfilter" type="number">
                <input id="osszegigfilter" name="osszegigfilter" type="number">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                {if ($showbizonylatstatuszeditor)}
                    <label for="bizonylatstatuszfilter">{at('Státusz')}:</label>
                    <select id="bizonylatstatuszfilter" name="bizonylatstatuszfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $bizonylatstatuszlist as $_role}
                            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                        {/foreach}
                    </select>
                    <label for="bizonylatstatuszcsoportfilter">{at('Státusz csoport')}:</label>
                    <select id="bizonylatstatuszcsoportfilter" name="bizonylatstatuszcsoportfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $bizonylatstatuszcsoportlist as $_role}
                            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                        {/foreach}
                    </select>
                {/if}
                <label for="bizonylatrontottfilter">{at('Rontott')}:</label>
                <select id="bizonylatrontottfilter" name="bizonylatrontottfilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($bizonylatrontottfilter === 1)} selected="selected"{/if}>{at('nem rontott')}</option>
                    <option value="2"{if ($bizonylatrontottfilter === 2)} selected="selected"{/if}>{at('rontott')}</option>
                </select>
                <label for="bizonylatstornofilter">{at('Stornó')}:</label>
                <select id="bizonylatstornofilter" name="bizonylatstornofilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($bizonylatstornofilter === 1)} selected="selected"{/if}>{at('nem stornózott')}</option>
                    <option value="2"{if ($bizonylatstornofilter === 2)} selected="selected"{/if}>{at('stornózott')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="fizmodfilter">{at('Fiz.mód')}:</label>
                <select id="fizmodfilter" name="fizmodfilter">
                    <option value="">{at('Mindegy')}</option>
                    {foreach $fizmodlist as $_role}
                        <option value="{$_role.id}">{$_role.caption}</option>
                    {/foreach}
                </select>
                <label for="szallitasimodfilter">{at('Szállítási mód')}:</label>
                <select id="szallitasimodfilter" name="szallitasimodfilter">
                    <option value="">{at('Mindegy')}</option>
                    {foreach $szallitasimodlist as $_role}
                        <option value="{$_role.id}">{$_role.caption}</option>
                    {/foreach}
                </select>
                <label for="uzletkotofilter">{at('Üzletkötő')}:</label>
                <select id="uzletkotofilter" name="uzletkotofilter">
                    <option value="">{at('Mindegy')}</option>
                    {foreach $uzletkotolist as $_role}
                        <option value="{$_role.id}">{$_role.caption}</option>
                    {/foreach}
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="raktarfilter">{at('Raktár')}:</label>
                <select id="raktarfilter" name="raktarfilter">
                    <option value="">{at('Mindegy')}</option>
                    {foreach $raktarlist as $_role}
                        <option value="{$_role.id}">{$_role.caption}</option>
                    {/foreach}
                </select>
                <label for="valutanemfilter">{at('Valutanem')}:</label>
                <select id="valutanemfilter" name="valutanemfilter">
                    <option value="">{at('Mindegy')}</option>
                    {foreach $valutanemlist as $_role}
                        <option value="{$_role.id}">{$_role.caption}</option>
                    {/foreach}
                </select>
                <label for="feketelistafilter">{at('Feketelistás')}:</label>
                <select id="feketelistafilter" name="feketelistafilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($bizonylatfeketelistafilter === 1)} selected="selected"{/if}>{at('nem feketelistás')}</option>
                    <option value="2"{if ($bizonylatfeketelistafilter === 2)} selected="selected"{/if}>{at('feketelistás')}</option>
                </select>
            </div>
            {if ($showfuvarlevelszam)}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="fuvarlevelszamfilter">{at('Fuvarlevélszám')}:</label>
                    <input id="fuvarlevelszamfilter" name="fuvarlevelszamfilter" type="text" size="20">
                    <label for="referrerfilter">{at('Referrer')}:</label>
                    <input id="referrerfilter" name="referrerfilter" type="text" size="20">
                </div>
            {/if}
            {if ($showerbizonylatszam)}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="erbizonylatszamfilter">{at('Er.biz.szám')}:</label>
                    <input id="erbizonylatszamfilter" name="erbizonylatszamfilter" type="text" size="20">
                </div>
            {/if}
            <div class="matt-hseparator"></div>
            <div>
                <label for="megjegyzesfilter">{at('Megjegyzés')}:</label>
                <input id="megjegyzesfilter" name="megjegyzesfilter" type="text">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="naveredmenyfilter">{at('NAV eredmény')}:</label>
                <select id="naveredmenyfilter" name="naveredmenyfilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($bizonylatnaveredmenyfilter === 1)} selected="selected"{/if}>{at('ABORTED')}</option>
                    <option value="2"{if ($bizonylatnaveredmenyfilter === 2)} selected="selected"{/if}>{at('WAITING')}</option>
                    <option value="3"{if ($bizonylatnaveredmenyfilter === 3)} selected="selected"{/if}>{at('nincs beküldve')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="termekertekeleskikuldvefilter">{at('Termék értékelés kérő kiküldve')}:</label>
                <select id="termekertekeleskikuldvefilter" name="termekertekeleskikuldvefilter">
                    <option value="0">{at('Mindegy')}</option>
                    <option value="1"{if ($termekertekeleskikuldvefilter === 1)} selected="selected"{/if}>{at('nem')}</option>
                    <option value="2"{if ($termekertekeleskikuldvefilter === 2)} selected="selected"{/if}>{at('igen')}</option>
                </select>
            </div>
            <div class="matt-hseparator"></div>
            {include "comp_partnercimkefilter.tpl"}
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
        <table id="mattable-table" data-noversion="{$noversion}">
            <thead>
            <tr>
                <th><input id="maincheckbox" type="checkbox"></th>
                {if ($shownavallapot)}
                    <th>NAV állapot</th>
                {/if}
                {if ($showbizonylatstatuszeditor)}
                    <th></th>
                {/if}
                <th></th>
                <th></th>
                <th class="js-sumcol"></th>
                {if ($setup.osztottfizmod)}
                    <th></th>
                {/if}
                {if ($setup.woocommerce)}
                    <th>Számlázz.hu</th>
                {/if}
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
    <div id="feketelistaokdialog" class="hidden">
        <label>{at('Oka')}:</label>
        <textarea name="feketelistaok"></textarea>
    </div>
    <div id="emailpdfdialog" class="hidden">
        {at('A bizonylat ezután nyomtatott státuszú lesz. Biztos, hogy elküldi emailben?')}
    </div>
    <div id="navdialog" class="hidden">
        {at('Biztos, hogy beküldi a bizonylatot a NAV-nak?')}
    </div>
    <div id="naverrordialog" class="hidden">
    </div>
    <div id="emailsablondialog" class="hidden">
        <label>{at('Sablon')}:</label>
        <select name="emailsablon">
            <option value="">válasszon</option>
            {foreach $emailsablonlist as $emailsablon}
                <option value="{$emailsablon.id}">{$emailsablon.caption}</option>
            {/foreach}
        </select>
    </div>
    <div id="mattkarb">
    </div>
{/block}