{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylathelper.js?v=5"></script>
    <script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
    <form id="exportform" method="POST"><input type="hidden" name="ids"></form>
    <div id="mattable-select" data-theme="{$theme}" data-szamlazhat="{$csinalhatujbizonylatot}"
         data-vonalkod="{if ($setup.vonalkod)}1{else}0{/if}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{$pagetitle}"></div>
        <div id="mattable-filterwrapper" class="listaszuro">
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo">
                    <label for="idfilter">{at('Sorszám')}</label>
                    <input id="idfilter" name="idfilter" type="text" size="16">
                </div>
                <div class="listaszuro-mezo listaszuro-szeles">
                    <label for="vevonevfilter">{at('Vevő név')}</label>
                    <input id="vevonevfilter" name="vevonevfilter" type="text">
                </div>
                <div class="listaszuro-mezo">
                    <label for="vevoemailfilter">{at('Vevő email')}</label>
                    <input id="vevoemailfilter" name="vevoemailfilter" type="text" size="20">
                </div>
                <div class="listaszuro-mezo">
                    <label for="vevotelefonfilter">{at('Vevő telefon')}</label>
                    <input id="vevotelefonfilter" name="vevotelefonfilter" type="text" size="14">
                </div>
                <div class="listaszuro-mezo">
                    <label for="megjegyzesfilter">{at('Megjegyzés')}</label>
                    <input id="megjegyzesfilter" name="megjegyzesfilter" type="text" size="20">
                </div>
            </div>
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo">
                    <label for="datumtipusfilter">{at('Dátum')}</label>
                    <span class="listaszuro-csoport">
                        <select id="datumtipusfilter" name="datumtipusfilter"{if (!haveJog(20))} disabled="disabled"{/if}>
                            <option value="1">{at('kelt')}</option>
                            <option value="2">{at('teljesítés')}</option>
                            {if ($showesedekesseg)}
                                <option value="3">{at('esedékesség')}</option>
                            {/if}
                            {if ($showbeerkezes)}
                                <option value="4">{at('beérkezés')}</option>
                            {/if}
                        </select>
                        <label for="datumtolfilter" class="listaszuro-rejtettcimke">{at('Dátum')} {at('-tól')}</label>
                        <input id="datumtolfilter" name="datumtolfilter" type="text" size="10"
                               data-datum="{$datumtolfilter|default}"{if (!haveJog(20))} disabled="disabled"{/if}>
                        <label for="datumigfilter" class="listaszuro-rejtettcimke">{at('Dátum')} {at('-ig')}</label>
                        <input id="datumigfilter" name="datumigfilter" type="text" size="10"{if (!haveJog(20))} disabled="disabled"{/if}>
                    </span>
                </div>
                <div class="listaszuro-mezo">
                    <label for="osszegtolfilter">{at('Összeg')}</label>
                    <span class="listaszuro-csoport">
                        <input id="osszegtolfilter" name="osszegtolfilter" type="number" class="mezo-rovid" placeholder="{at('-tól')}">
                        <label for="osszegigfilter" class="listaszuro-rejtettcimke">{at('Összeg')} {at('-ig')}</label>
                        <input id="osszegigfilter" name="osszegigfilter" type="number" class="mezo-rovid" placeholder="{at('-ig')}">
                    </span>
                </div>
                {if ($showbizonylatstatuszeditor)}
                <div class="listaszuro-mezo">
                    <label for="bizonylatstatuszfilter">{at('Státusz')}</label>
                    <select id="bizonylatstatuszfilter" name="bizonylatstatuszfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $bizonylatstatuszlist as $_role}
                            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="bizonylatstatuszcsoportfilter">{at('Státusz csoport')}</label>
                    <select id="bizonylatstatuszcsoportfilter" name="bizonylatstatuszcsoportfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $bizonylatstatuszcsoportlist as $_role}
                            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                {/if}
                <div class="listaszuro-mezo">
                    <label for="bizonylatrontottfilter">{at('Rontott')}</label>
                    <select id="bizonylatrontottfilter" name="bizonylatrontottfilter">
                        <option value="0">{at('Mindegy')}</option>
                        <option value="1"{if ($bizonylatrontottfilter === 1)} selected="selected"{/if}>{at('nem rontott')}</option>
                        <option value="2"{if ($bizonylatrontottfilter === 2)} selected="selected"{/if}>{at('rontott')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="bizonylatstornofilter">{at('Stornó')}</label>
                    <select id="bizonylatstornofilter" name="bizonylatstornofilter">
                        <option value="0">{at('Mindegy')}</option>
                        <option value="1"{if ($bizonylatstornofilter === 1)} selected="selected"{/if}>{at('nem stornózott')}</option>
                        <option value="2"{if ($bizonylatstornofilter === 2)} selected="selected"{/if}>{at('stornózott')}</option>
                    </select>
                </div>
                {if ($setup.bankpenztar)}
                    <div class="listaszuro-mezo">
                        <label for="egyenlegfilter">{at('Egyenleg')}</label>
                        <select id="egyenlegfilter" name="egyenlegfilter">
                            <option value="0">{at('Mindegy')}</option>
                            <option value="1">{at('kiegyenlített')}</option>
                            <option value="2">{at('kiegyenlítetlen')}</option>
                        </select>
                    </div>
                    <div class="listaszuro-mezo">
                        <label for="lejartfilter">{at('Lejárat')}</label>
                        <select id="lejartfilter" name="lejartfilter">
                            <option value="0">{at('Mindegy')}</option>
                            <option value="1">{at('lejárt')}</option>
                            <option value="2">{at('nem járt le')}</option>
                        </select>
                    </div>
                {/if}
                <div class="listaszuro-mezo">
                    <label for="naveredmenyfilter">{at('NAV eredmény')}</label>
                    <select id="naveredmenyfilter" name="naveredmenyfilter">
                        <option value="0">{at('Mindegy')}</option>
                        <option value="1"{if ($bizonylatnaveredmenyfilter === 1)} selected="selected"{/if}>{at('ABORTED')}</option>
                        <option value="2"{if ($bizonylatnaveredmenyfilter === 2)} selected="selected"{/if}>{at('WAITING')}</option>
                        <option value="3"{if ($bizonylatnaveredmenyfilter === 3)} selected="selected"{/if}>{at('nincs beküldve')}</option>
                    </select>
                </div>
            </div>
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo">
                    <label for="fizmodfilter">{at('Fiz.mód')}</label>
                    <select id="fizmodfilter" name="fizmodfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $fizmodlist as $_role}
                            <option value="{$_role.id}">{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="szallitasimodfilter">{at('Szállítási mód')}</label>
                    <select id="szallitasimodfilter" name="szallitasimodfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $szallitasimodlist as $_role}
                            <option value="{$_role.id}">{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="uzletkotofilter">{at('Üzletkötő')}</label>
                    <select id="uzletkotofilter" name="uzletkotofilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $uzletkotolist as $_role}
                            <option value="{$_role.id}">{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="raktarfilter">{at('Raktár')}</label>
                    <select id="raktarfilter" name="raktarfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $raktarlist as $_role}
                            <option value="{$_role.id}">{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="valutanemfilter">{at('Valutanem')}</label>
                    <select id="valutanemfilter" name="valutanemfilter">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $valutanemlist as $_role}
                            <option value="{$_role.id}">{$_role.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="feketelistafilter">{at('Feketelistás')}</label>
                    <select id="feketelistafilter" name="feketelistafilter">
                        <option value="0">{at('Mindegy')}</option>
                        <option value="1"{if ($bizonylatfeketelistafilter === 1)} selected="selected"{/if}>{at('nem feketelistás')}</option>
                        <option value="2"{if ($bizonylatfeketelistafilter === 2)} selected="selected"{/if}>{at('feketelistás')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="termekertekeleskikuldvefilter">{at('Termék értékelés kérő kiküldve')}</label>
                    <select id="termekertekeleskikuldvefilter" name="termekertekeleskikuldvefilter">
                        <option value="0">{at('Mindegy')}</option>
                        <option value="1"{if ($termekertekeleskikuldvefilter === 1)} selected="selected"{/if}>{at('nem')}</option>
                        <option value="2"{if ($termekertekeleskikuldvefilter === 2)} selected="selected"{/if}>{at('igen')}</option>
                    </select>
                </div>
            </div>
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo">
                    <label for="szallitasiirszamfilter">{at('Szállítási cím')}</label>
                    <span class="listaszuro-csoport">
                        <input id="szallitasiirszamfilter" name="szallitasiirszamfilter" type="text" size="6" placeholder="{at('irsz.')}">
                        <label for="szallitasivarosfilter" class="listaszuro-rejtettcimke">{at('Szállítási város')}</label>
                        <input id="szallitasivarosfilter" name="szallitasivarosfilter" type="text" size="14" placeholder="{at('város')}">
                        <label for="szallitasiutcafilter" class="listaszuro-rejtettcimke">{at('Szállítási utca')}</label>
                        <input id="szallitasiutcafilter" name="szallitasiutcafilter" type="text" size="16" placeholder="{at('utca')}">
                    </span>
                </div>
                <div class="listaszuro-mezo">
                    <label for="szamlazasiirszamfilter">{at('Számlázási cím')}</label>
                    <span class="listaszuro-csoport">
                        <input id="szamlazasiirszamfilter" name="szamlazasiirszamfilter" type="text" size="6" placeholder="{at('irsz.')}">
                        <label for="szamlazasivarosfilter" class="listaszuro-rejtettcimke">{at('Számlázási város')}</label>
                        <input id="szamlazasivarosfilter" name="szamlazasivarosfilter" type="text" size="14" placeholder="{at('város')}">
                        <label for="szamlazasiutcafilter" class="listaszuro-rejtettcimke">{at('Számlázási utca')}</label>
                        <input id="szamlazasiutcafilter" name="szamlazasiutcafilter" type="text" size="16" placeholder="{at('utca')}">
                    </span>
                </div>
                {if ($showmunkalapadatok)}
                <div class="listaszuro-mezo">
                    <label for="munkalapegyediazonositofilter">{at('Egyedi azonosító')}</label>
                    <input id="munkalapegyediazonositofilter" name="munkalapegyediazonositofilter" type="text" size="16">
                </div>
                {/if}
                {if ($showfuvarlevelszam)}
                <div class="listaszuro-mezo">
                    <label for="fuvarlevelszamfilter">{at('Fuvarlevélszám')}</label>
                    <input id="fuvarlevelszamfilter" name="fuvarlevelszamfilter" type="text" size="16">
                </div>
                <div class="listaszuro-mezo">
                    <label for="referrerfilter">{at('Referrer')}</label>
                    <input id="referrerfilter" name="referrerfilter" type="text" size="16">
                </div>
                {/if}
                {if ($showerbizonylatszam)}
                <div class="listaszuro-mezo">
                    <label for="erbizonylatszamfilter">{at('Er.biz.szám')}</label>
                    <input id="erbizonylatszamfilter" name="erbizonylatszamfilter" type="text" size="16">
                </div>
                {/if}
            </div>
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
        <table id="mattable-table" class="bizlista-tabla" data-noversion="{$noversion}">
            <thead>
            <tr>
                <th><input id="maincheckbox" type="checkbox" autocomplete="off"></th>
                {if ($shownavallapot)}
                    <th>NAV állapot</th>
                {/if}
                {if ($showbizonylatstatuszeditor)}
                    <th>{at('Státusz')}</th>
                {/if}
                {if ($showmunkalapadatok)}
                    <th>{at('Munkalap')}</th>
                {/if}
                <th>{at('Bizonylat')}</th>
                <th>{at('Adatok')}</th>
                <th>{at('Kapcsolódó bizonylatok')}</th>
                {* a fejlécbe a lista az összesítést írja, a kártyás nézet felirata ezért a data-oszlop *}
                <th class="js-sumcol" data-oszlop="{at('Összegek')}"></th>
                {if ($setup.osztottfizmod)}
                    <th></th>
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
    {if ($showrendszeres)}
        <div id="szamlazasdialog" class="hidden">
            <div>{at('Kijelölés nélkül a rendszeres sablonokból készül számla.')}</div>
            <div>
                <label for="SzamlazasTetelnevtoldatEdit">{at('Tételnév kiegészítés')}:</label>
                <input id="SzamlazasTetelnevtoldatEdit" name="tetelnevtoldat" type="text">
            </div>
            <div>
                <label for="SzamlazasMennyisegEdit">{at('Mennyiség')}:</label>
                <input id="SzamlazasMennyisegEdit" name="mennyiseg" type="text" size="8">
            </div>
            <div>
                <label for="SzamlazasTeljesitesEdit">{at('Teljesítés legyen az esedékesség')}:</label>
                <input id="SzamlazasTeljesitesEdit" name="teljesitesazesedekesseg" type="checkbox" checked="checked">
            </div>
            <div>
                <label for="SzamlazasSendemailEdit">{at('Küldés emailben')}:</label>
                <input id="SzamlazasSendemailEdit" name="sendemail" type="checkbox">
            </div>
        </div>
    {/if}
    <div id="mattkarb">
    </div>
{/block}