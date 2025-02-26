{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/setupform.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Beállítások')}</h3>
        </div>
        <form id="mattkarb-form" action="/admin/setup/save" method="post">
            <input type="text" name="fakeusername" class="hidden">
            <input type="password" name="fakepassword" class="hidden">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#DefaTab">{at('Alapértelmezések')}</a></li>
                    <li><a href="#TulajTab">{at('Tulajdonos adatai')}</a></li>
                    {if ($setup.multishop)}
                        <li><a href="#WebTab">{$webshop1name} {at('beállítások')}</a></li>
                        <li><a href="#Web2Tab">{$webshop2name} {at('beállítások')}</a></li>
                        <li><a href="#Web3Tab">{$webshop3name} {at('beállítások')}</a></li>
                        <li><a href="#Web4Tab">{$webshop4name} {at('beállítások')}</a></li>
                        <li><a href="#Web5Tab">{$webshop5name} {at('beállítások')}</a></li>
                        <li><a href="#WebshopSetupTab">{at('Webshopok beállításai')}</a></li>
                    {else}
                        <li><a href="#WebTab">{at('Web beállítások')}</a></li>
                    {/if}
                    {if ($setup.mptngy)}
                        <li><a href="#MPTNGYTab">{at('MPT nagygyűlés')}</a></li>
                    {/if}
                    <li><a href="#SzallitasiKtgTab">{at('Szállítási költség')}</a></li>
                    {if ($maintheme == 'mkwcansas')}
                        <li><a href="#ImportTab">{at('Import')}</a></li>
                    {/if}
                    {if ($maintheme == 'superzoneb2b' || $maintheme == 'mugenrace' || $maintheme === 'mugenrace2021')}
                        <li><a href="#MugenraceTab">{at('Mugenrace')}</a></li>
                    {/if}
                    {if ($setup.barion)}
                        <li><a href="#BarionTab">{at('Barion')}</a></li>
                    {/if}
                    <li><a href="#RendezvenyTab">{at('Rendezvények')}</a></li>
                    <li><a href="#IdTab">{at('Azonosítók, kódok')}</a></li>
                    <li><a href="#MiniCRMTab">{at('MiniCRM')}</a></li>
                    <li><a href="#EmailTab">{at('Email')}</a></li>
                    <li><a href="#FeedTab">{at('Feed beállítások')}</a></li>
                    <li><a href="#SitemapTab">{at('Sitemap beállítások')}</a></li>
                </ul>
                <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="OrszagEdit">{at('Ország')}:</label></span>
                            <select id="OrszagEdit" name="orszag">
                                <option value="">{at('válasszon')}</option>
                                {foreach $orszaglist as $_o}
                                    <option
                                        value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MagyarOrszagEdit">{at('Magyarország')}:</label></span>
                            <select id="MagyarOrszagEdit" name="magyarorszag">
                                <option value="">{at('válasszon')}</option>
                                {foreach $magyarorszaglist as $_o}
                                    <option
                                        value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="FizmodEdit">{at('Fizetési mód')}:</label></span>
                            <select id="FizmodEdit" name="fizmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $fizmodlist as $_fizmod}
                                    <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="UtanvetFizmodEdit">{at('Utánvét fizetési mód')}:</label></span>
                            <select id="UtanvetFizmodEdit" name="utanvetfizmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $utanvetfizmodlist as $_fizmod}
                                    <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        {if ($setup.darshan)}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="SZEPFizmodEdit">{at('SZÉP kártya fiz.mód')}:</label></span>
                                <select id="SZEPFizmodEdit" name="szepkartyafizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $szepkartyafizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="SportkartyaFizmodEdit">{at('Sportkártya fiz.mód')}:</label></span>
                                <select id="SportkartyaFizmodEdit" name="sportkartyafizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $sportkartyafizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="AYCMFizmodEdit">{at('AYCM fiz.mód')}:</label></span>
                                <select id="AYCMFizmodEdit" name="aycmfizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $aycmfizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="setuprow">
                            <span class="setuplabel"><label for="SzallmodEdit">{at('Szállítási mód')}:</label></span>
                            <select id="SzallmodEdit" name="szallitasimod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $szallitasimodlist as $_fizmod}
                                    <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="FoxpostSzallmodEdit">{at('Foxpost száll.mód')}:</label></span>
                            <select id="FoxpostSzallmodEdit" name="foxpostszallmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $foxpostszallmodlist as $_foxpost}
                                    <option value="{$_foxpost.id}"{if ($_foxpost.selected)} selected="selected"{/if}>{$_foxpost.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="GLSSzallmodEdit">{at('GLS csomagpont száll.mód')}:</label></span>
                            <select id="GLSSzallmodEdit" name="glsszallmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $glsszallmodlist as $_foxpost}
                                    <option value="{$_foxpost.id}"{if ($_foxpost.selected)} selected="selected"{/if}>{$_foxpost.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="GLSFutarSzallmodEdit">{at('GLS futár száll.mód')}:</label></span>
                            <select id="GLSFutarSzallmodEdit" name="glsfutarszallmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $glsfutarszallmodlist as $_foxpost}
                                    <option value="{$_foxpost.id}"{if ($_foxpost.selected)} selected="selected"{/if}>{$_foxpost.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="GLSSM2Edit">{at('GLS SMS értesítő kell')}:</label></span>
                            <input id="GLSSM2Edit" name="glssm2" type="checkbox"{if ($glssm2)} checked="checked"{/if}>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="TOFSzallmodEdit">{at('TOF száll.mód')}:</label></span>
                            <select id="TOFSzallmodEdit" name="tofszallmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $tofszallmodlist as $_foxpost}
                                    <option value="{$_foxpost.id}"{if ($_foxpost.selected)} selected="selected"{/if}>{$_foxpost.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="ArukeresoExportSzallmodEdit">{at('Árukereső export száll.mód')}:</label></span>
                            <select id="ArukeresoExportSzallmodEdit" name="arukeresoexportszallmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $arukeresoexportszallmodlist as $_foxpost}
                                    <option value="{$_foxpost.id}"{if ($_foxpost.selected)} selected="selected"{/if}>{$_foxpost.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        {if ($setup.otpay)}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="OTPayFizmodEdit">{at('OTPay fizetési mód')}:</label></span>
                                <select id="OTPayFizmodEdit" name="otpayfizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $otpayfizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if ($setup.masterpass)}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="MasterPassFizmodEdit">{at('MasterPass fizetési mód')}:</label></span>
                                <select id="MasterPassFizmodEdit" name="masterpassfizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $masterpassfizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="setuprow">
                            <span class="setuplabel"><label for="DefaultTermekEdit">{at('Termék')}:</label></span>
                            <select id="defaultTermekEdit" name="defaulttermek">
                                <option value="">{at('válasszon')}</option>
                                {foreach $defaulttermeklist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="DefaultpartnerEdit">{at('Partner')}:</label></span>
                            <select id="DefaultpartnerEdit" name="defaultpartner">
                                <option value="">{at('válasszon')}</option>
                                {foreach $defaultpartnerlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RaktarEdit">{at('Raktár')}:</label></span>
                            <select id="RaktarEdit" name="raktar">
                                <option value="">{at('válasszon')}</option>
                                {foreach $raktarlist as $_raktar}
                                    <option value="{$_raktar.id}"{if ($_raktar.selected)} selected="selected"{/if}>{$_raktar.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="ValutanemEdit">{at('Valutanem')}:</label></span>
                            <select id="ValutanemEdit" name="valutanem">
                                <option value="">{at('válasszon')}</option>
                                {foreach $valutanemlist as $_valutanem}
                                    <option value="{$_valutanem.id}"{if ($_valutanem.selected)} selected="selected"{/if}>{$_valutanem.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        {if ($setup.arsavok)}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="ArsavEdit">{at('Ársáv')}:</label></span>
                                <select id="ArsavEdit" name="arsav">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $arsavlist as $_arsav}
                                        <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="ShowTermekArsavEdit">{at('Terméklista ársáv')}:</label></span>
                                <select id="ShowTermekArsavEdit" name="showtermekarsav">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $showtermekarsavlist as $_arsav}
                                        <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="ShowTermekArsavValutanemEdit">{at('Valutanem')}:</label>
                                <select id="ShowTermekArsavValutanemEdit" name="showtermekarsavvalutanem">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $showtermekarsavvalutanemlist as $_valutanem}
                                        <option value="{$_valutanem.id}"{if ($_valutanem.selected)} selected="selected"{/if}>{$_valutanem.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="setuprow">
                            <span class="setuplabel"><label for="SzamlaOrzesAlapEdit">{at('Számlák megőrzése')}:</label></span>
                            <select id="SzamlaOrzesAlapEdit" name="szamlaorzesalap">
                                <option value="1"{if ($szamlaorzesalap=='1')} selected="selected"{/if}>{at('kelt')}</option>
                                <option value="2"{if ($szamlaorzesalap=='2')} selected="selected"{/if}>{at('teljesítés')}</option>
                            </select>
                            <span> {at('évének utolsó napja +')} </span><input name="szamlaorzesev" type="number"
                                                                               value="{$szamlaorzesev}"><span> {at('év')}</span>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="EsedAlapEdit">{at('Esedékesség alapja')}:</label></span>
                            <select id="EsedAlapEdit" name="esedekessegalap">
                                <option value="1"{if ($esedekessegalap=='1')} selected="selected"{/if}>{at('kelt')}</option>
                                <option value="2"{if ($esedekessegalap=='2')} selected="selected"{/if}>{at('teljesítés')}</option>
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="NullasAfaEdit">{at('Nullás ÁFA')}:</label></span>
                            <select id="NullasAfaEdit" name="nullasafa">
                                <option value="">{at('válasszon')}</option>
                                {foreach $nullasafalist as $_loc}
                                    <option value="{$_loc.id}"{if ($_loc.selected)} selected="selected"{/if}>{$_loc.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        {if ($setup.multilang)}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="LocaleEdit">{at('Publikus felület nyelve')}:</label></span>
                                <select id="LocaleEdit" name="locale">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $localelist as $_loc}
                                        <option value="{$_loc.id}"{if ($_loc.selected)} selected="selected"{/if}>{$_loc.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="setuprow">
                            <span class="setuplabel"><label for="BizonylatMennyisegEdit">{at('Bizonylattétel alap mennyisége')}:</label></span>
                            <span><input id="BizonylatMennyisegEdit" name="bizonylatmennyiseg" type="text" value="{$bizonylatmennyiseg}">
                        </div>
                        {if ($maintheme === 'mkwcansas')}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="TeljesitmenyKezdoEvEdit">{at('Teljesítmény jelentés kezdő éve')}:</label></span>
                                <input id="TeljesitmenyKezdoEvEdit" name="teljesitmenykezdoev" type="text" value="{$teljesitmenykezdoev}">
                            </div>
                        {/if}
                        <div class="setuprow">
                            <span class="setuplabel"><label for="BelsoUKEdit">{at('Belső üzletkötő')}:</label></span>
                            <select id="BelsoUKEdit" name="belsouk">
                                <option value="">{at('válasszon')}</option>
                                {foreach $belsouklist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="SzallitasiFeltetelSablonEdit">{at('Szállítási felt. sablon')}:</label></span>
                            <select id="SzallitasiFeltetelSablonEdit" name="szallitasifeltetelsablon">
                                <option value="">{at('válasszon')}</option>
                                {foreach $szallitasifeltetelstatlaplist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="SzamlalevelSablonEdit">{at('Számlalevél sablon')}:</label></span>
                            <select id="SzamlalevelSablonEdit" name="szamlalevelsablon">
                                <option value="">{at('válasszon')}</option>
                                {foreach $szamlalevelsablonlist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="KonyvelolevelSablonEdit">{at('Könyvelő levél sablon')}:</label></span>
                            <select id="KonyvelolevelSablonEdit" name="konyvelolevelsablon">
                                <option value="">{at('válasszon')}</option>
                                {foreach $konyvelolevelsablonlist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="ErtekelesKeroSablonEdit">{at('Értékelés kérő sablon')}:</label></span>
                            <select id="ErtekelesKeroSablonEdit" name="ertekeleskerosablon">
                                <option value="">{at('válasszon')}</option>
                                {foreach $ertekeleskerosablonlist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="ErtekelesErtesitoSablonEdit">{at('Értékelés értesítő sablon')}:</label></span>
                            <select id="ErtekelesErtesitoSablonEdit" name="ertekelesertesitosablon">
                                <option value="">{at('válasszon')}</option>
                                {foreach $ertekelesertesitosablonlist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MunkaJelenletEdit">{at('Munkaidő')}:</label></span>
                            <select id="MunkaJelenletEdit" name="munkajelenlet">
                                <option value="">{at('válasszon')}</option>
                                {foreach $munkajelenletlist as $_fizmod}
                                    <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {if ($maintheme === 'darshan' || $setup.darshan == 1)}
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaJutalekEdit">{at('Jutalék százalék')}:</label></span>
                                <span><input id="JogaJutalekEdit" name="jogajutalek" type="text" value="{$jogajutalek}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaUresTeremJutalekEdit">{at('Üres terem jutalék')}:</label></span>
                                <span><input id="JogaUresTeremJutalekEdit" name="jogauresteremjutalek" type="text" value="{$jogauresteremjutalek}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaAYCMJutalekEdit">{at('AYCM jutalék')}:</label></span>
                                <span><input id="JogaAYCMJutalekEdit" name="jogaaycmjutalek" type="text" value="{$jogaaycmjutalek}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="TanarelszmolasSablonEdit">{at('Tanár elszámolás sablon')}:</label></span>
                                <select id="TanarelszmolasSablonEdit" name="tanarelszamolassablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $tanarelszamolassablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="NemjonsenkiSablonEdit">{at('Nem jön senki sablon (tanárnak)')}:</label></span>
                                <select id="NemjonsenkiSablonEdit" name="nemjonsenkisablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $nemjonsenkisablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="nemjelentkeztekelegentanarnakSablonEdit">{at('Nem jelentkeztek elegen sablon (tanárnak)')}:</label></span>
                                <select id="nemjelentkeztekelegentanarnakSablonEdit" name="nemjelentkeztekelegentanarnaksablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $nemjelentkeztekelegentanarnaksablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="nemjelentkeztekelegengyakorlonaksablonEdit">{at('Nem jelentkeztek elegen sablon (gyakorlónak)')}:</label></span>
                                <select id="nemjelentkeztekelegengyakorlonaksablonEdit" name="nemjelentkeztekelegengyakorlonaksablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $nemjelentkeztekelegengyakorlonaksablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="elegenjelentkeztektanarnaksablonEdit">{at('Elegen jelentkeztek sablon (tanárnak)')}:</label></span>
                                <select id="elegenjelentkeztektanarnaksablonEdit" name="elegenjelentkeztektanarnaksablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $elegenjelentkeztektanarnaksablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="jogabejelentkezeskoszonoSablonEdit">{at('Óra bejelentkezés köszönő sablon')}:</label></span>
                                <select id="jogabejelentkezeskoszonoSablonEdit" name="jogabejelentkezeskoszonosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogabejelentkezeskoszonosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogalemondaskoszonoSablonEdit">{at('Óra lemondás köszönő sablon')}:</label></span>
                                <select id="jogalemondaskoszonoSablonEdit" name="jogalemondaskoszonosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogalemondaskoszonosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaelmaradasertesitoSablonEdit">{at('Elmaradás értesítő')}:</label></span>
                                <select id="jogaelmaradasertesitoSablonEdit" name="jogaelmaradasertesitosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaelmaradasertesitosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="jogabejelentkezesertesitoSablonEdit">{at('Bejelentkezés értesítő (tanárnak)')}:</label></span>
                                <select id="jogabejelentkezesertesitoSablonEdit" name="jogabejelentkezesertesitosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogabejelentkezesertesitosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="jogalemondasertesitoSablonEdit">{at('Lemondás értesítő (tanárnak)')}:</label></span>
                                <select id="jogalemondasertesitoSablonEdit" name="jogalemondasertesitosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogalemondasertesitosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label
                                        for="jogaelmaradaskonyvelonekSablonEdit">{at('Óra elmaradás könyvelőnek sablon')}:</label></span>
                                <select id="jogaelmaradaskonyvelonekSablonEdit" name="jogaelmaradaskonyveloneksablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaelmaradaskonyveloneksablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaberletkoszonosablonEdit">{at('Jóga bérlet vásárlás köszönő')}:</label></span>
                                <select id="jogaberletkoszonosablonEdit" name="jogaberletkoszonosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberletkoszonosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaberletfelszolitosablonEdit">{at('Jóga bérlet fizetés emlékeztető')}:</label></span>
                                <select id="jogaberletfelszolitosablonEdit" name="jogaberletfelszolitosablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberletfelszolitosablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaberletlefogjarnisablonEdit">{at('Jóga bérlet le fog járni')}:</label></span>
                                <select id="jogaberletlefogjarnisablonEdit" name="jogaberletlefogjarnisablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberletlefogjarnisablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaberletlejartsablonEdit">{at('Jóga bérlet lejárt')}:</label></span>
                                <select id="jogaberletlejartsablonEdit" name="jogaberletlejartsablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberletlejartsablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="jogaberletdatumlejartsablonEdit">{at('Jóga bérlet dátum lejárt')}:</label></span>
                                <select id="jogaberletdatumlejartsablonEdit" name="jogaberletdatumlejartsablon">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberletdatumlejartsablonlist as $_belsouk}
                                        <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaOrajegyTermekEdit">{at('Jóga órajegy termék')}:</label></span>
                                <select id="JogaOrajegyTermekEdit" name="jogaorajegytermek">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaorajegytermeklist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaBerlet4TermekEdit">{at('Jóga bérlet 4 termék')}:</label></span>
                                <select id="Jogaberlet4TermekEdit" name="jogaberlet4termek">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberlet4termeklist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaBerlet10TermekEdit">{at('Jóga bérlet 10 termék')}:</label></span>
                                <select id="JogaBerlet10TermekEdit" name="jogaberlet10termek">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaberlet10termeklist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="setuprow">
                                <span class="setuplabel"><label for="JogaAllapotfelmeresTipusEdit">{at('Állapotfelmérés')}:</label></span>
                                <select id="JogaAllapotfelmeresTipusEdit" name="jogaallapotfelmerestipus">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogaallapotfelmerestipuslist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    {/if}
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        {if ($maintheme === 'mijsz')}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="FelvetelalattCimkeEdit">{at('Felvétel alatt címke')}:</label></span>
                                <select id="FelvetelalattCimkeEdit" name="felvetelalattcimke">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $felvetelalattcimkelist as $_felvetelalattcimke}
                                        <option
                                            value="{$_felvetelalattcimke.id}"{if ($_felvetelalattcimke.selected)} selected="selected"{/if}>{$_felvetelalattcimke.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="FelvetelalattTipusEdit">{at('Felvétel alatt típus')}:</label></span>
                                <select id="FelvetelalattTipusEdit" name="felvetelalattpartnertipus">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $felvetelalattpartnertipuslist as $_felvetelalattpartnertipus}
                                        <option
                                            value="{$_felvetelalattpartnertipus.id}"{if ($_felvetelalattpartnertipus.selected)} selected="selected"{/if}>{$_felvetelalattpartnertipus.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {else}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="MarkaCsEdit">{at('Márka csoport')}:</label></span>
                                <select id="MarkaCsEdit" name="markacs">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $markacslist as $_markacs}
                                        <option value="{$_markacs.id}"{if ($_markacs.selected)} selected="selected"{/if}>{$_markacs.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="KiskerCimkeEdit">{at('Kisker címke')}:</label></span>
                                <select id="KiskerCimkeEdit" name="kiskercimke">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $kiskercimkelist as $_kiskercimke}
                                        <option
                                            value="{$_kiskercimke.id}"{if ($_kiskercimke.selected)} selected="selected"{/if}>{$_kiskercimke.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="NagykerCimkeEdit">{at('Nagyker címke')}:</label></span>
                                <select id="NagykerCimkeEdit" name="nagykercimke">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $nagykercimkelist as $_nagykercimke}
                                        <option
                                            value="{$_nagykercimke.id}"{if ($_nagykercimke.selected)} selected="selected"{/if}>{$_nagykercimke.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="FelvetelalattCimkeEdit">{at('Felvétel alatt címke')}:</label></span>
                                <select id="FelvetelalattCimkeEdit" name="felvetelalattcimke">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $felvetelalattcimkelist as $_felvetelalattcimke}
                                        <option
                                            value="{$_felvetelalattcimke.id}"{if ($_felvetelalattcimke.selected)} selected="selected"{/if}>{$_felvetelalattcimke.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if ($maintheme === 'superzoneb2b' || $maintheme === 'mugenrace2021')}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="SpanyolCimkeEdit">{at('Spanyol címke')}:</label></span>
                                <select id="SpanyolCimkeEdit" name="spanyolcimke">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $spanyolcimkelist as $_spanyolcimke}
                                        <option
                                            value="{$_spanyolcimke.id}"{if ($_spanyolcimke.selected)} selected="selected"{/if}>{$_spanyolcimke.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="SpanyolOrszagEdit">{at('Spanyolország')}:</label></span>
                                <select id="SpanyolOrszagEdit" name="spanyolorszag">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $spanyolorszaglist as $_spanyolo}
                                        <option
                                            value="{$_spanyolo.id}"{if ($_spanyolo.selected)} selected="selected"{/if}>{$_spanyolo.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if ($maintheme === 'superzoneb2b' || $maintheme === "mkwcansas" || $maintheme === "mugenrace" || $maintheme === 'mugenrace2021')}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="SzinEdit">{at('Szín')}:</label></span>
                                <select id="SzinEdit" name="valtozattipusszin">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $valtozattipusszinlist as $_v}
                                        <option value="{$_v.id}"{if ($_v.selected)} selected="selected"{/if}>{$_v.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="MeretEdit">{at('Méret')}:</label></span>
                                <select id="MeretEdit" name="valtozattipusmeret">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $valtozattipusmeretlist as $_v}
                                        <option value="{$_v.id}"{if ($_v.selected)} selected="selected"{/if}>{$_v.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if ($maintheme !== 'mkwcansas')}
                            <div class="setuprow">
                                <span class="setuplabel"><label for="RendezendoValtozatEdit">{at('Rendezendő változat')}:</label></span>
                                <select id="RendezendoValtozatEdit" name="rendezendovaltozat">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $rendezendovaltozatlist as $_rendvalt}
                                        <option value="{$_rendvalt.id}"{if ($_rendvalt.selected)} selected="selected"{/if}>{$_rendvalt.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="ValtozatRendezesEdit">{at('Értékek sorrendben')}:</label></span>
                                <input id="ValtozatRendezesEdit" name="valtozatsorrend" type="text" size="75" value="{$valtozatsorrend}">
                            </div>
                        {/if}
                    </div>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="AdminroleEdit">{at('Admin szerepkör')}:</label></span>
                            <select id="AdminroleEdit" name="adminrole">
                                <option value="">{at('válasszon')}</option>
                                {foreach $adminrolelist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="TermekfeltoltoroleEdit">{at('Termékfeltöltő szerepkör')}:</label></span>
                            <select id="TermekfeltoltoroleEdit" name="termekfeltoltorole">
                                <option value="">{at('válasszon')}</option>
                                {foreach $termekfeltoltorolelist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="BizonylatStatuszFuggobenEdit">{at('"Függőben" biz.státusz')}:</label></span>
                            <select id="BizonylatStatuszFuggobenEdit" name="bizonylatstatuszfuggoben">
                                <option value="">{at('válasszon')}</option>
                                {foreach $bizonylatstatuszfuggobenlist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="BizonylatStatuszTeljesithetoEdit">{at('"Teljesíthető" biz.státusz')}:</label></span>
                            <select id="BizonylatStatuszTeljesithetoEdit" name="bizonylatstatuszteljesitheto">
                                <option value="">{at('válasszon')}</option>
                                {foreach $bizonylatstatuszteljesithetolist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="BizonylatStatuszBackorderEdit">{at('"Backorder" biz.státusz')}:</label></span>
                            <select id="BizonylatStatuszBackorderEdit" name="bizonylatstatuszbackorder">
                                <option value="">{at('válasszon')}</option>
                                {foreach $bizonylatstatuszbackorderlist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MegrendelesFilterStatuszCsoportEdit">{at('Megrendelés filter')}:</label></span>
                            <select id="MegrendelesFilterStatuszCsoportEdit" name="megrendelesfilterstatuszcsoport">
                                <option value="">{at('válasszon')}</option>
                                {foreach $megrendelesfilterstatuszcsoportlist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="KuponElotagEdit">{at('Kupon előtag')}:</label></span>
                            <input id="KuponElotagEdit" name="kuponelotag" type="text" value="{$kuponelotag}">
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="VasarlasiUtalvanyTermekEdit">{at('Vásárlási utalvány termék')}:</label></span>
                            <select id="VasarlasiUtalvanyTermekEdit" name="vasarlasiutalvanytermek">
                                <option value="">{at('válasszon')}</option>
                                {foreach $vasarlasiutalvanytermeklist as $_role}
                                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="nominkeszletEdit">{at('Min. bolti készlet NEM számít')}:</label></span>
                            <input id="nominkeszletEdit" name="nominkeszlet" type="checkbox"{if ($nominkeszlet)} checked="checked"{/if}>
                        </div>
                        <div class="setuprow">
                            <span class="js-nominkeszlettermekkat">{$nominkeszlettermekkat.caption|default:'nincs megadva'}</span>
                            <input name="nominkeszlettermekkat" type="hidden" value="{$nominkeszlettermekkat.id}">
                        </div>
                    </div>
                    <table>
                        <tbody>
                        <tr>
                            <td><label>{at('Az importerek ebbe a kategóriába tegyék az új termékeket')}:</label></td>
                            <td>
                                <span class="js-importnewkatid">{$importnewkat.caption|default:'nincs megadva'}</span>
                                <input name="importnewkatid" type="hidden" value="{$importnewkat.id}">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="TulajTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label for="TulajnevEdit">{at('Név')}:</label></td>
                            <td colspan="3"><input id="TulajnevEdit" name="tulajnev" type="text" size="75" maxlength="255" value="{$tulajnev}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajirszamEdit">{at('Cím')}:</label></td>
                            <td colspan="3"><input id="TulajirszamEdit" name="tulajirszam" type="text" size="6" maxlength="10" value="{$tulajirszam}"
                                                   placeholder="{at('ir.szám')}">
                                <input name="tulajvaros" type="text" size="20" maxlength="40" value="{$tulajvaros}" placeholder="{at('város')}">
                                <input name="tulajutca" type="text" size="40" maxlength="60" value="{$tulajutca}" placeholder="{at('utca, házszám')}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="TulajadoszamEdit">{at('Adószám')}:</label></td>
                            <td><input id="TulajadoszamEdit" name="tulajadoszam" type="text" value="{$tulajadoszam}"></td>
                            <td><label for="TulajeuadoszamEdit">{at('Közösségi adószám')}:</label></td>
                            <td><input id="TulajeuadoszamEdit" name="tulajeuadoszam" type="text" value="{$tulajeuadoszam}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajeorinrEdit">{at('EORI NR')}:</label></td>
                            <td><input id="TulajeorinrEdit" name="tulajeorinr" type="text" value="{$tulajeorinr}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajkisadozoEdit">{at('Kisadózó')}:</label></td>
                            <td><input id="TulajkisadozoEdit" name="tulajkisadozo" type="checkbox"{if ($tulajkisadozo)} checked="checked"{/if}></td>
                            <td><label for="TulajegyenivallalkozoEdit">{at('Egyéni vállalkozó')}:</label></td>
                            <td><input id="TulajegyenivallalkozoEdit" name="tulajegyenivallalkozo"
                                       type="checkbox"{if ($tulajegyenivallalkozo)} checked="checked"{/if}></td>
                        </tr>
                        <tr>
                            <td><label for="TulajevnevEdit">{at('Egyéni váll.neve')}:</label></td>
                            <td><input id="TulajevnevEdit" type="text" name="tulajevnev" value="{$tulajevnev}"></td>
                            <td><label for="TulajevnyilvszamEdit">{at('Egyéni váll.nyilv.száma')}:</label></td>
                            <td><input id="TulajevnyilvszamEdit" type="text" name="tulajevnyilvszam" value="{$tulajevnyilvszam}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajjovengszamEdit">{at('Jövedéki engedély száma')}:</label></td>
                            <td><input id="TulajjovengszamEdit" type="text" name="tulajjovengszam" value="{$tulajjovengszam}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajkontaktnevEdit">{at('Kontakt név')}:</label></td>
                            <td><input id="TulajkontaktnevEdit" type="text" name="tulajkontaktnev" value="{$tulajkontaktnev}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajKontaktEmailEdit">{at('Kontakt email')}:</label></td>
                            <td><input id="TulajKontaktEmailEdit" type="text" name="tulajkontaktemail" value="{$tulajkontaktemail}"></td>
                            <td><label for="TulajKontaktTelefonEdit">{at('Kontakt telefon')}:</label></td>
                            <td><input id="TulajKontaktTelefonEdit" type="text" name="tulajkontakttelefon" value="{$tulajkontakttelefon}"></td>
                        </tr>
                        <tr>
                            <td><label for="TulajpartnerEdit">Partner:</label></td>
                            <td><select id="TulajpartnerEdit" name="tulajpartner">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $tulajpartnerlist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                        <tr>
                            <td><label for="TulajcrcEdit">{at('CRC')}:</label></td>
                            <td><input id="TulajcrcEdit" name="tulajcrc" type="password" value=""></td>
                        </tr>
                        <tr>
                            <td><label for="TulajprogramnevEdit">{at('Szoftver hivatalos neve')}:</label></td>
                            <td><input id="TulajprogramnevEdit" name="programnev" type="text" value="{$programnev}"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="WebTab" class="mattkarb-page" data-visible="visible">
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="OffEdit">{at('Publikus felület kikapcsolva')}:</label></span>
                            <input id="OffEdit" name="off" type="checkbox"{if ($off)} checked="checked"{/if}>
                        </div>
                    </div>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td><label>{at('Logo')}:</label></td>
                            <td><input name="logo" type="text" value="{$logo}"></td>
                            <td><a class="js-kepbrowsebutton" data-name="logo" href="#" title="{at('Browse')}">{at('...')}</a></td>
                        </tr>
                        <tr>
                            <td><label>{at('Új termék jelölő')}:</label></td>
                            <td><input name="ujtermekjelolo" type="text" value="{$ujtermekjelolo}"></td>
                            <td><a class="js-kepbrowsebutton" data-name="ujtermekjelolo" href="#" title="{at('Browse')}">{at('...')}</a></td>
                            <td><label>{at('Top 10 jelölő')}:</label></td>
                            <td><input name="top10jelolo" type="text" value="{$top10jelolo}"></td>
                            <td><a class="js-kepbrowsebutton" data-name="top10jelolo" href="#" title="{at('Browse')}">{at('...')}</a></td>
                        </tr>
                        <tr>
                            <td><label>{at('Akció jelölő')}:</label></td>
                            <td><input name="akciojelolo" type="text" value="{$akciojelolo}"></td>
                            <td><a class="js-kepbrowsebutton" data-name="akciojelolo" href="#" title="{at('Browse')}">{at('...')}</a></td>
                            <td><label>{at('Ingyen szállítás jelölő')}:</label></td>
                            <td><input name="ingyenszallitasjelolo" type="text" value="{$ingyenszallitasjelolo}"></td>
                            <td><a class="js-kepbrowsebutton" data-name="ingyenszallitasjelolo" href="#" title="{at('Browse')}">{at('...')}</a></td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td><label>{at('Hírek száma a főoldalon')}:</label></td>
                            <td><input name="fooldalhirdb" type="number" value="{$fooldalhirdb}"></td>
                            <td><label>{at('Ajánlott termékek száma a főoldalon')}:</label></td>
                            <td><input name="fooldalajanlotttermekdb" type="number" value="{$fooldalajanlotttermekdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Legnépszerűbb termékek száma a főoldalon')}:</label></td>
                            <td><input name="fooldalnepszerutermekdb" type="number" value="{$fooldalnepszerutermekdb}"></td>
                            <td><label>{at('Kiemelt termékek száma')}:</label></td>
                            <td><input name="kiemelttermekdb" type="number" value="{$kiemelttermekdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Akciós termékek száma a főoldalon')}:</label></td>
                            <td><input name="fooldalakciostermekdb" type="number" value="{$fooldalakciostermekdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Termékek száma a terméklistában')}:</label></td>
                            <td><input name="termeklistatermekdb" type="number" value="{$termeklistatermekdb}"></td>
                            <td><label>{at('Legnépszerűbb termékek száma a terméklapon')}:</label></td>
                            <td><input name="termeklapnepszerutermekdb" type="number" value="{$termeklapnepszerutermekdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Hasonló termékek száma a terméklapon')}:</label></td>
                            <td><input name="hasonlotermekdb" type="number" value="{$hasonlotermekdb}"></td>
                            <td><label>{at('Hasonló termék árkülönbség %')}:</label></td>
                            <td><input name="hasonlotermekarkulonbseg" type="number" value="{$hasonlotermekarkulonbseg}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Ár szűrő lépésköze')}:</label></td>
                            <td><input name="arfilterstep" type="number" value="{$arfilterstep}"></td>
                            <td><label>{at('Automatikus kiléptetés ideje (perc)')}:</label></td>
                            <td><input name="autologoutmin" type="number" value="{$autologoutmin}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Blogposztok száma a blogban')}:</label></td>
                            <td><input name="blogposztdb" type="number" value="{$blogposztdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Blogposztok száma a terméklapon')}:</label></td>
                            <td><input name="blogposzttermeklapdb" type="number" value="{$blogposzttermeklapdb}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Blogposztok száma a terméklistában')}:</label></td>
                            <td><input name="blogposztkategoriadb" type="number" value="{$blogposztkategoriadb}"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td><label>{at('Mini kép utótag')}:</label></td>
                            <td><input name="miniimgpost" type="text" value="{$miniimgpost}"></td>
                            <td><label>{at('Kis kép utótag')}:</label></td>
                            <td><input name="smallimgpost" type="text" value="{$smallimgpost}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Közepes kép utótag')}:</label></td>
                            <td><input name="mediumimgpost" type="text" value="{$mediumimgpost}"></td>
                            <td><label>{at('Nagy kép utótag')}:</label></td>
                            <td><input name="bigimgpost" type="text" value="{$bigimgpost}"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="OldalCimEdit" name="oldalcim" type="text" size="75" maxlength="255" value="{$oldalcim}"></td>
                        </tr>
                        <tr>
                            <td><label for="SeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="SeodescriptionEdit" name="seodescription" type="text" cols="75">{$seodescription}</textarea></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td colspan="3">{at('Kategória oldal')}</td>
                        </tr>
                        <tr>
                            <td colspan="3">[kategorianev] [global]</td>
                        </tr>
                        <tr>
                            <td><label for="KOldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="KOldalCimEdit" name="katoldalcim" type="text" size="75" maxlength="255" value="{$katoldalcim}"></td>
                        </tr>
                        <tr>
                            <td><label for="KSeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="KSeodescriptionEdit" name="katseodescription" type="text" cols="75">{$katseodescription}</textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td colspan="3">{at('Termék oldal')}</td>
                        </tr>
                        <tr>
                            <td colspan="3">[termeknev] [bruttoar] [kategorianev] [global] [rovidleiras]</td>
                        </tr>
                        <tr>
                            <td><label for="TOldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="TOldalCimEdit" name="termekoldalcim" type="text" size="75" maxlength="255" value="{$termekoldalcim}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="TSeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="TSeodescriptionEdit" name="termekseodescription"
                                                      cols="75">{$termekseodescription}</textarea></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td colspan="3">{at('Márka oldal')}</td>
                        </tr>
                        <tr>
                            <td colspan="3">[markanev] [global]</td>
                        </tr>
                        <tr>
                            <td><label for="MOldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="MOldalCimEdit" name="markaoldalcim" type="text" size="75" maxlength="255" value="{$markaoldalcim}"></td>
                        </tr>
                        <tr>
                            <td><label for="MSeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="MSeodescriptionEdit" name="markaseodescription"
                                                      cols="75">{$markaseodescription}</textarea></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td colspan="3">{at('Hírek')}</td>
                        </tr>
                        <tr>
                            <td colspan="3">[global]</td>
                        </tr>
                        <tr>
                            <td><label for="HirekOldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="HirekOldalCimEdit" name="hirekoldalcim" type="text" size="75" maxlength="255" value="{$hirekoldalcim}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="HirekSeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="HirekSeodescriptionEdit" name="hirekseodescription"
                                                      cols="75">{$hirekseodescription}</textarea></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td colspan="3">{at('Blog')}</td>
                        </tr>
                        <tr>
                            <td colspan="3">[global]</td>
                        </tr>
                        <tr>
                            <td><label for="BlogOldalCimEdit">{at('Lap címe')}:</label></td>
                            <td colspan="3"><input id="BlogOldalCimEdit" name="blogoldalcim" type="text" size="75" maxlength="255" value="{$blogoldalcim}">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="BlogSeodescriptionEdit">{at('META leírás')}:</label></td>
                            <td colspan="3"><textarea id="BlogSeodescriptionEdit" name="blogseodescription"
                                                      cols="75">{$blogseodescription}</textarea></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <tbody>
                        <tr>
                            <td><label for="ASZFUrlEdit">{at('ÁSZF URL')}:</label></td>
                            <td colspan="3"><input id="ASZFUrlEdit" name="aszfurl" type="text" size="75" maxlength="255" value="{$aszfurl}">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {if ($setup.mptngy)}
                    <div id="MPTNGYTab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <label for="mptngyszimpoziumtipusEdit" class="setuplabel">{at('Szimpózium')}:</label>
                                <select id="mptngyszimpoziumtipusEdit" name="mptngyszimpoziumtipus">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $mptngyszakmaianyagtipuslist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <label for="mptngyszimpoziumeloadastipusEdit" class="setuplabel">{at('Szimpózium előadás')}:</label>
                                <select id="mptngyszimpoziumeloadastipusEdit" name="mptngyszimpoziumeloadastipus">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $mptngyszatipuslist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <label for="mptngykonyvbemutatotipusEdit" class="setuplabel">{at('Könyvbemutató')}:</label>
                                <select id="mptngykonyvbemutatotipusEdit" name="mptngykonyvbemutatotipus">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $mptngykonyvbemutatotipuslist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <label for="mptngydatum1edit" class="setuplabel">{at('Első nap dátuma')}:</label>
                                <input id="mptngydatum1edit" name="mptngydatum1" data-datum="{$mptngydatum1}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngydatum2edit" class="setuplabel">{at('Második nap dátuma')}:</label>
                                <input id="mptngydatum2edit" name="mptngydatum2" data-datum="{$mptngydatum2}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngydatum3edit" class="setuplabel">{at('Harmadik nap dátuma')}:</label>
                                <input id="mptngydatum3edit" name="mptngydatum3" data-datum="{$mptngydatum3}">
                            </div>
                        </div>
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <label for="mptngyszempont1edit" class="setuplabel">{at('Szempont 1')}:</label>
                                <input id="mptngyszempont1edit" name="mptngyszempont1" value="{$mptngyszempont1}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngyszempont2edit" class="setuplabel">{at('Szempont 2')}:</label>
                                <input id="mptngyszempont2edit" name="mptngyszempont2" value="{$mptngyszempont2}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngyszempont3edit" class="setuplabel">{at('Szempont 3')}:</label>
                                <input id="mptngyszempont3edit" name="mptngyszempont3" value="{$mptngyszempont3}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngyszempont4edit" class="setuplabel">{at('Szempont 4')}:</label>
                                <input id="mptngyszempont4edit" name="mptngyszempont4" value="{$mptngyszempont4}">
                            </div>
                            <div class="setuprow">
                                <label for="mptngyszempont5edit" class="setuplabel">{at('Szempont 5')}:</label>
                                <input id="mptngyszempont5edit" name="mptngyszempont5" value="{$mptngyszempont5}">
                            </div>
                        </div>
                    </div>
                {/if}
                <div id="SzallitasiKtgTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label for="SzallitasiKtg1TolEdit">{at('Értékhatár 1')}:</label></td>
                            <td><input id="SzallitasiKtg1TolEdit" name="szallitasiktg1tol" type="text" value="{$szallitasiktg1tol}"> - <input
                                    name="szallitasiktg1ig" type="text" value="{$szallitasiktg1ig}">
                            </td>
                            <td><input name="szallitasiktg1ertek" value="{$szallitasiktg1ertek}"></td>
                        </tr>
                        <tr>
                            <td><label for="SzallitasiKtg2TolEdit">{at('Értékhatár 2')}:</label></td>
                            <td><input id="SzallitasiKtg2TolEdit" name="szallitasiktg2tol" type="text" value="{$szallitasiktg2tol}"> - <input
                                    name="szallitasiktg2ig" type="text" value="{$szallitasiktg2ig}">
                            </td>
                            <td><input name="szallitasiktg2ertek" value="{$szallitasiktg2ertek}"></td>
                        </tr>
                        <tr>
                            <td><label for="SzallitasiKtg3TolEdit">{at('Értékhatár 3')}:</label></td>
                            <td><input id="SzallitasiKtg3TolEdit" name="szallitasiktg3tol" type="text" value="{$szallitasiktg3tol}"> - <input
                                    name="szallitasiktg3ig" type="text" value="{$szallitasiktg3ig}">
                            </td>
                            <td><input name="szallitasiktg3ertek" value="{$szallitasiktg3ertek}"></td>
                        </tr>
                        <tr>
                            <td><label for="SzallitasiKtgTermekEdit">{at('Szállítási költség')}:</label></td>
                            <td colspan="2"><select id="SzallitasiKtgTermekEdit" name="szallitasiktgtermek">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $szallitasiktgtermeklist as $_szallitasiktgtermek}
                                        <option
                                            value="{$_szallitasiktgtermek.id}"{if ($_szallitasiktgtermek.selected)} selected="selected"{/if}>{$_szallitasiktgtermek.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                        <tr>
                            <td><label for="UtanvetKtgTermekEdit">{at('Utánvét költség')}:</label></td>
                            <td colspan="2"><select id="UtanvetKtgTermekEdit" name="utanvetktgtermek">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $utanvetktgtermeklist as $_utanvetktgtermek}
                                        <option
                                            value="{$_utanvetktgtermek.id}"{if ($_utanvetktgtermek.selected)} selected="selected"{/if}>{$_utanvetktgtermek.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {if ($setup.multishop)}
                    <div id="Web2Tab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="Off2Edit">{at('Publikus felület kikapcsolva')}:</label></span>
                                <input id="Off2Edit" name="off2" type="checkbox"{if ($off2)} checked="checked"{/if}>
                            </div>
                        </div>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr>
                                <td><label>{at('Logo')}:</label></td>
                                <td><input name="logo2" type="text" value="{$logo2}"></td>
                                <td><a class="js-kepbrowsebutton" data-name="logo2" href="#" title="{at('Browse')}">{at('...')}</a></td>
                            </tr>
                            <tr>
                                <td><label for="WS2PriceEdit">{at('Ársáv')}:</label></td>
                                <td><select id="WS2PriceEdit" name="arsav2">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $arsav2list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            <tr>
                                <td><label for="WS2DiscountPriceEdit">{at('Akciós ársáv')}:</label></td>
                                <td><select id="WS2DiscountPriceEdit" name="akciosarsav2">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $akciosarsav2list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="Web3Tab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="Off3Edit">{at('Publikus felület kikapcsolva')}:</label></span>
                                <input id="Off3Edit" name="off3" type="checkbox"{if ($off3)} checked="checked"{/if}>
                            </div>
                        </div>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr>
                                <td><label>{at('Logo')}:</label></td>
                                <td><input name="logo3" type="text" value="{$logo3}"></td>
                                <td><a class="js-kepbrowsebutton" data-name="logo3" href="#" title="{at('Browse')}">{at('...')}</a></td>
                            </tr>
                            <tr>
                                <td><label for="WS3PriceEdit">{at('Ársáv')}:</label></td>
                                <td><select id="WS3PriceEdit" name="arsav3">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $arsav3list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            <tr>
                                <td><label for="WS3DiscountPriceEdit">{at('Akciós ársáv')}:</label></td>
                                <td><select id="WS3DiscountPriceEdit" name="akciosarsav3">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $akciosarsav3list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="Web4Tab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="Off4Edit">{at('Publikus felület kikapcsolva')}:</label></span>
                                <input id="Off4Edit" name="off4" type="checkbox"{if ($off4)} checked="checked"{/if}>
                            </div>
                        </div>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr>
                                <td><label for="WS4PriceEdit">{at('Ársáv')}:</label></td>
                                <td><select id="WS4PriceEdit" name="arsav4">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $arsav4list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            <tr>
                                <td><label for="WS4DiscountPriceEdit">{at('Akciós ársáv')}:</label></td>
                                <td><select id="WS4DiscountPriceEdit" name="akciosarsav4">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $akciosarsav4list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            </tbody>
                        </table>
                        <table>
                            <tbody>
                            <tr>
                                <td><label>{at('Alapértelmezett kategória')}:</label></td>
                                <td>
                                    <span class="js-web4defakatid">{$web4defakat.caption|default:'nincs megadva'}</span>
                                    <input name="web4defakatid" type="hidden" value="{$web4defakat.id}">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="Web5Tab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="Off5Edit">{at('Publikus felület kikapcsolva')}:</label></span>
                                <input id="Off5Edit" name="off5" type="checkbox"{if ($off2)} checked="checked"{/if}>
                            </div>
                        </div>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr>
                                <td><label>{at('Logo')}:</label></td>
                                <td><input name="logo5" type="text" value="{$logo5}"></td>
                                <td><a class="js-kepbrowsebutton" data-name="logo5" href="#" title="{at('Browse')}">{at('...')}</a></td>
                            </tr>
                            <tr>
                                <td><label for="WS5PriceEdit">{at('Ársáv')}:</label></td>
                                <td><select id="WS5PriceEdit" name="arsav5">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $arsav5list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            <tr>
                                <td><label for="WS5DiscountPriceEdit">{at('Akciós ársáv')}:</label></td>
                                <td><select id="WS5DiscountPriceEdit" name="akciosarsav5">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $akciosarsav5list as $_arsav}
                                            <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                                        {/foreach}
                                    </select></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="WebshopSetupTab" class="mattkarb-page" data-visible="visible">
                        <div>
                            <label for="WCPartnerTipusEdit">Woocommerce partner típus:</label>
                            <select id="WCPartnerTipusEdit" name="wcpartnertipus">
                                <option value="">{at('válasszon')}</option>
                                {foreach $wcpartnertipuslist as $_wcpartnertipus}
                                    <option
                                        value="{$_wcpartnertipus.id}"{if ($_wcpartnertipus.selected)} selected="selected"{/if}>{$_wcpartnertipus.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="matt-hseparator"></div>
                        {for $cikl = 1 to $enabledwebshops}
                            <div>
                                <label>{at('Webshop név')} {$cikl}:</label>
                                <input name="webshop{$cikl}name" type="text" value="{$webshop{$cikl}name}">
                            </div>
                            <div class="matt-hseparator"></div>
                        {/for}
                    </div>
                {/if}
                {if ($maintheme == 'mkwcansas')}
                    <div id="ImportTab" class="mattkarb-page" data-visible="visible">
                        <div>
                            <label>{at('Vízjel')}:</label>
                            <input name="watermark" type="text" value="{$watermark}">
                            <a class="js-kepbrowsebutton" data-name="watermark" href="#" title="{at('Browse')}">{at('...')}</a>
                        </div>
                        <div>
                            <label for="KreativEdit">Kreatív puzzle:</label>
                            <select id="KreativEdit" name="gyartokreativ">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartokreativlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathKreativEdit">Képek mappája:</label>
                            <input id="PathKreativEdit" name="pathkreativ" value="{$pathkreativ}">
                            <label for="UrlKreativEdit">URL:</label>
                            <input id="UrlKreativEdit" name="urlkreativ" value="{$urlkreativ}">
                            <label for="UrlKreativImagesEdit">Képek URL:</label>
                            <input id="UrlKreativImagesEdit" name="urlkreativimages" value="{$urlkreativimages}">
                            <a href="#" class="js-stopimport" data-href="{$stopkreativimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairkreativimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="DeltonEdit">Delton:</label>
                            <select id="DeltonEdit" name="gyartodelton">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartodeltonlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathDeltonEdit">Képek mappája:</label>
                            <input id="PathDeltonEdit" name="pathdelton" value="{$pathdelton}">
                            <label for="UrlDeltonEdit">URL:</label>
                            <input id="UrlDeltonEdit" name="urldelton" value="{$urldelton}">
                            <a href="#" class="js-stopimport" data-href="{$stopdeltonimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairdeltonimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="ReintexEdit">Reintex:</label>
                                <select id="ReintexEdit" name="gyartoreintex">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartoreintexlist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="PathReintexEdit">Képek mappája:</label>
                                <input id="PathReintexEdit" name="pathreintex" value="{$pathreintex}">
                                <label for="UrlReintexEdit">URL:</label>
                                <input id="UrlReintexEdit" name="urlreintex" value="{$urlreintex}">
                                <a href="#" class="js-stopimport" data-href="{$stopreinteximporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repairreinteximporturl}">Javít</a>
                            </div>
                            <div>
                                <label for="ExcludeReintex">Kihagyandó cikkszámok:</label>
                                <input id="'ExcludeReintex" name="excludereintex" value="{$excludereintex}" size="80" title="Vesszővel elválasztva">
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="TutisportEdit">Tutisport:</label>
                            <select id="TutisportEdit" name="gyartotutisport">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartotutisportlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathTutisportEdit">Képek mappája:</label>
                            <input id="PathTutisportEdit" name="pathtutisport" value="{$pathtutisport}">
                            <a href="#" class="js-stopimport" data-href="{$stoptutisportimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairtutisportimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="MaxutovEdit">Makszutov:</label>
                            <select id="MaxutovEdit" name="gyartomaxutov">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartomaxutovlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathMaxutovEdit">Képek mappája:</label>
                            <input id="PathMaxutovEdit" name="pathmaxutov" value="{$pathmaxutov}">
                            <label for="UrlMaxutovEdit">URL:</label>
                            <input id="UrlMaxutovEdit" name="urlmaxutov" value="{$urlmaxutov}">
                            <a href="#" class="js-stopimport" data-href="{$stopmaxutovimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairmaxutovimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="LegavenueEdit">Leg Avenue:</label>
                            <select id="LegavenueEdit" name="gyartolegavenue">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartolegavenuelist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathLegavenueEdit">Képek mappája:</label>
                            <input id="PathLegavenueEdit" name="pathlegavenue" value="{$pathlegavenue}">
                            <label for="UrlLegavenueEdit">URL:</label>
                            <input id="UrlLegavenueEdit" name="urllegavenue" value="{$urllegavenue}">
                            <a href="#" class="js-stopimport" data-href="{$stoplegavenueimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairlegavenueimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="NomadEdit">Nomád:</label>
                            <select id="NomadEdit" name="gyartonomad">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartonomadlist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathNomadEdit">Képek mappája:</label>
                            <input id="PathNomadEdit" name="pathnomad" value="{$pathnomad}">
                            <label for="UrlNomadEdit">URL:</label>
                            <input id="UrlNomadEdit" name="urlnomad" value="{$urlnomad}">
                            <a href="#" class="js-stopimport" data-href="{$stopnomadimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairnomadimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="NikaEdit">Nika:</label>
                            <select id="NikaEdit" name="gyartonika">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartonikalist as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathNikaEdit">Képek mappája:</label>
                            <input id="PathNikaEdit" name="pathnika" value="{$pathnika}">
                            <label for="UrlNikaEdit">URL:</label>
                            <input id="UrlNikaEdit" name="urlnika" value="{$urlnika}">
                            <a href="#" class="js-stopimport" data-href="{$stopnikaimporturl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairnikaimporturl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="Haffner24Edit">Haffner24:</label>
                            <select id="Haffner24Edit" name="gyartohaffner24">
                                <option value="">{at('válasszon')}</option>
                                {foreach $gyartohaffner24list as $_gyarto}
                                    <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                {/foreach}
                            </select>
                            <label for="PathHaffner24Edit">Képek mappája:</label>
                            <input id="PathHaffner24Edit" name="pathhaffner24" value="{$pathhaffner24}">
                            <label for="UrlHaffner24Edit">URL:</label>
                            <input id="UrlHaffner24Edit" name="urlhaffner24" value="{$urlhaffner24}">
                            <a href="#" class="js-stopimport" data-href="{$stophaffner24importurl}">Stop import</a>
                            <a href="#" class="js-repairimport" data-href="{$repairhaffner24importurl}">Javít</a>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="EvonaEdit">Evona:</label>
                                <select id="EvonaEdit" name="gyartoevona">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartoevonalist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="PathEvonaEdit">Képek mappája:</label>
                                <input id="PathEvonaEdit" name="pathevona" value="{$pathevona}">
                                <label for="KepUrlEvonaEdit">Képek eredeti url-je:</label>
                                <input id="KepUrlEvonaEdit" name="kepurlevona" value="{$kepurlevona}">
                                <a href="#" class="js-stopimport" data-href="{$stopevonaimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repairevonaimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="DENCsEdit">{at('Szálvastagság csoport')}:</label></span>
                            <select id="DENCsEdit" name="dencs">
                                <option value="">{at('válasszon')}</option>
                                {foreach $dencslist as $_dencs}
                                    <option value="{$_dencs.id}"{if ($_dencs.selected)} selected="selected"{/if}>{$_dencs.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="EvonaXMLEdit">Evona XML:</label>
                                <select id="EvonaXMLEdit" name="gyartoevonaxml">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartoevonaxmllist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="UrlEvonaXMLEdit">XML URL:</label>
                                <input id="UrlEvonaXMLEdit" name="urlevonaxml" value="{$urlevonaxml}">
                                <a href="#" class="js-stopimport" data-href="{$stopevonaxmlimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repairevonaxmlimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="GulfEdit">Gulf:</label>
                                <select id="GulfEdit" name="gyartogulf">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartogulflist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <a href="#" class="js-stopimport" data-href="{$stopgulfimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repairgulfimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="SmileebikeEdit">Smile ebike:</label>
                                <select id="SmileebikeEdit" name="gyartosmileebike">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartosmileebikelist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="PathSmileebikeEdit">Képek mappája:</label>
                                <input id="PathSmileebikeEdit" name="pathsmileebike" value="{$pathsmileebike}">
                                <label for="UrlSmileebikeEdit">URL:</label>
                                <input id="UrlSmileebikeEdit" name="urlsmileebike" value="{$urlsmileebike}">
                                <a href="#" class="js-stopimport" data-href="{$stopsmileebikeimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repairsmileebikeimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="CopydepoTEdit">Copy Depo termék:</label>
                                <select id="CopydepoTEdit" name="gyartocopydepo">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $gyartocopydepolist as $_gyarto}
                                        <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                                    {/foreach}
                                </select>
                                <label for="PathCopydepotermekEdit">Képek mappája:</label>
                                <input id="PathCopydepotermekEdit" name="pathcopydepo" value="{$pathcopydepo}">
                                <label for="UrlCopydepotermekEdit">URL:</label>
                                <input id="UrlCopydepotermekEdit" name="urlcopydepotermek" value="{$urlcopydepotermek}">
                                <a href="#" class="js-stopimport" data-href="{$stopcopydepotermekimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repaircopydepotermekimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <div>
                                <label for="UrlCopydepokeszletEdit">Copy Depo készlet URL:</label>
                                <input id="UrlCopydepokeszletEdit" name="urlcopydepokeszlet" value="{$urlcopydepokeszlet}">
                                <a href="#" class="js-stopimport" data-href="{$stopcopydepokeszletimporturl}">Stop import</a>
                                <a href="#" class="js-repairimport" data-href="{$repaircopydepokeszletimporturl}">Javít</a>
                            </div>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="EpitoelemszamCsEdit">{at('Építőelemek száma csoport')}:</label></span>
                            <select id="EpitoelemszamCsEdit" name="epitoelemszamcs">
                                <option value="">{at('válasszon')}</option>
                                {foreach $epitoelemszamcslist as $_dencs}
                                    <option value="{$_dencs.id}"{if ($_dencs.selected)} selected="selected"{/if}>{$_dencs.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="CsomagoltmeretCsEdit">{at('Csomagolt méret csoport')}:</label></span>
                            <select id="CsomagoltmeretCsEdit" name="csomagoltmeretcs">
                                <option value="">{at('válasszon')}</option>
                                {foreach $csomagoltmeretcslist as $_dencs}
                                    <option value="{$_dencs.id}"{if ($_dencs.selected)} selected="selected"{/if}>{$_dencs.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="AjanlottkorosztalyCsEdit">{at('Ajánlott korosztály csoport')}:</label></span>
                            <select id="AjanlottkorosztalyCsEdit" name="ajanlottkorosztalycs">
                                <option value="">{at('válasszon')}</option>
                                {foreach $ajanlottkorosztalycslist as $_dencs}
                                    <option value="{$_dencs.id}"{if ($_dencs.selected)} selected="selected"{/if}>{$_dencs.caption}</option>
                                {/foreach}
                            </select>
                        </div>

                    </div>
                {/if}
                {if ($maintheme == 'superzoneb2b' || $maintheme == 'mugenrace' || $maintheme == 'mugenrace2021')}
                    <div id="MugenraceTab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <label>{at('Mugenrace logo')}:</label>
                                <input name="mugenracelogo" type="text" value="{$mugenracelogo}">
                                <a class="js-kepbrowsebutton" data-name="mugenracelogo" href="#" title="{at('Browse')}">{at('...')}</a>
                            </div>
                            <div class="setuprow">
                                <label>{at('Mugenrace lábléc logo')}:</label>
                                <input name="mugenracefooterlogo" type="text" value="{$mugenracefooterlogo}">
                                <a class="js-kepbrowsebutton" data-name="mugenracefooterlogo" href="#" title="{at('Browse')}">{at('...')}</a>
                            </div>
                            <div class="setuprow">
                                <label>{at('Főoldali kép')}:</label>
                                <input name="mugenracefooldalkep" type="text" value="{$mugenracefooldalkep}">
                                <a class="js-kepbrowsebutton" data-name="mugenracefooldalkep" href="#" title="{at('Browse')}">{at('...')}</a>
                            </div>
                            <div class="setuprow">
                                <label>{at('Fejléc kép')}:</label>
                                <input name="mugenracefejleckep" type="text" value="{$mugenracefejleckep}">
                                <a class="js-kepbrowsebutton" data-name="mugenracefejleckep" href="#" title="{at('Browse')}">{at('...')}</a>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="MugenraceFooldalSzovegEdit">{at('Főoldali szöveg')}:</label></span>
                                <span><textarea id="MugenraceFooldalSzovegEdit" name="mugenracefooldalszoveg"
                                                cols="75">{$mugenracefooldalszoveg}</textarea></span>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label>{at('Mugenrace kategória')}:</label></span>
                                <span class="js-mugenracekatid">{$mugenracekat.caption|default:'nincs megadva'}</span>
                                <input name="mugenracekatid" type="hidden" value="{$mugenracekat.id}">
                            </div>

                        </div>
                    </div>
                {/if}
                {if ($setup.barion)}
                    <div id="BarionTab" class="mattkarb-page" data-visible="visible">
                        <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionPOSkeyEdit">{at('POS key')}:</label></span>
                                <input id="BarionPOSkeyEdit" name="barionposkey" value="{$barionposkey}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionAPIVersionEdit">{at('API verzió')}:</label></span>
                                <input id="BarionAPIVersionEdit" name="barionapiversion" value="{$barionapiversion}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionPayeeEmailEdit">{at('Barionos fogadó email')}:</label></span>
                                <input id="BarionPayeeEmailEdit" name="barionpayeeemail" value="{$barionpayeeemail}">
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionEnvironmentEdit">{at('Barion környezet')}:</label></span>
                                <select id="BarionEnvironmentEdit" name="barionenvironment">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $barionenvironmentlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionFizmodEdit">{at('Barion fizetési mód')}:</label></span>
                                <select id="BarionFizmodEdit" name="barionfizmod">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $barionfizmodlist as $_fizmod}
                                        <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionStatuszFizetesrevarEdit">{at('"Fizetésre vár" biz.státusz')}:</label></span>
                                <select id="BarionStatuszFizetesrevarEdit" name="barionfizetesrevarstatusz">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $barionfizetesrevarstatuszlist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionStatuszFizetveEdit">{at('"Fizetve" biz.státusz')}:</label></span>
                                <select id="BarionStatuszFizetveEdit" name="barionfizetvestatusz">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $barionfizetvestatuszlist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="setuprow">
                                <span class="setuplabel"><label for="BarionStatuszRefundedEdit">{at('"Refunded" biz.státusz')}:</label></span>
                                <select id="BarionStatuszRefundedEdit" name="barionrefundedstatusz">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $barionrefundedstatuszlist as $_role}
                                        <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                {/if}

                <div id="RendezvenyTab" class="mattkarb-page" data-visible="visible">
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RRegErtesitoSablonEdit">{at('Jelentkezés értesítő levél sablon')}:</label></span>
                            <select id="RRegErtesitoSablonEdit" name="rendezvenysablonregertesito">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablonregertesitolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RendezvenyRegErtesitoEmailEdit">{at('Jelentkezés értesítés email')}:</label></span>
                            <input id="RendezvenyRegErtesitoEmailEdit" name="rendezvenyregertesitoemail" value="{$rendezvenyregertesitoemail}"
                                   title="Vesszővel elválasztva">
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RRegKoszonoSablonEdit">{at('Jelentkezés köszönő levél sablon')}:</label></span>
                            <select id="RRegKoszonoSablonEdit" name="rendezvenysablonregkoszono">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablonregkoszonolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RRegFelszabadultHelyEdit">{at('Felszabadult hely levél sablon')}:</label></span>
                            <select id="RRegFelszabadultHelyEdit" name="rendezvenysablonfelszabadulthelyertesito">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablonfelszabadulthelyertesitolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RDijbekeroSablonEdit">{at('Díjbekérő levél sablon')}:</label></span>
                            <select id="RDijbekeroSablonEdit" name="rendezvenysablondijbekero">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablondijbekerolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RFizetesKoszonoSablonEdit">{at('Fizetés köszönő levél sablon')}:</label></span>
                            <select id="RFizetesKoszonoSablonEdit" name="rendezvenysablonfizeteskoszono">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablonfizeteskoszonolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="RKezdesEmlekeztetoSablonEdit">{at('Kezdés emlékeztető levél sablon')}:</label></span>
                            <select id="RKezdesEmlekeztetoSablonEdit" name="rendezvenysablonkezdesemlekezteto">
                                <option value="">{at('válasszon')}</option>
                                {foreach $rendezvenysablonkezdesemlekeztetolist as $_belsouk}
                                    <option value="{$_belsouk.id}"{if ($_belsouk.selected)} selected="selected"{/if}>{$_belsouk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>

                <div id="IdTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label>{at('Google analytics kód')}:</label></td>
                            <td><input name="gafollow" type="text" value="{$gafollow}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Google Maps API kulcs')}:</label></td>
                            <td><input name="gmapsapikey" type="text" value="{$gmapsapikey}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Facebook app-id')}:</label></td>
                            <td><input name="fbappid" type="text" value="{$fbappid}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Árukereső TrustedShop webapi key')}:</label></td>
                            <td><input name="aktrustedshopapikey" type="text" value="{$aktrustedshopapikey}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Foxpost API verzió')}:</label></td>
                            <td><select name="foxpostapiversion">
                                    <option value="">válasszon</option>
                                    {foreach $foxpostapiversionlist as $_fpapiversion}
                                        <option
                                            value="{$_fpapiversion.id}"{if ($_fpapiversion.selected)} selected="selected"{/if}>{$_fpapiversion.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                        <tr>
                            <td><label>{at('Foxpost API URL')}:</label></td>
                            <td><input name="foxpostapiurl" type="text" value="{$foxpostapiurl}"></td>
                            <td><label>{at('Username')}:</label></td>
                            <td><input name="foxpostusername" type="text" value="{$foxpostusername}" autocomplete="off"></td>
                            <td><label>{at('Password')}:</label></td>
                            <td><input name="foxpostpassword" type="password" value="{$foxpostpassword}" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Foxpost v2 API URL')}:</label></td>
                            <td><input name="foxpostv2apiurl" type="text" value="{$foxpostv2apiurl}"></td>
                            <td><label>{at('v2 Username')}:</label></td>
                            <td><input name="foxpostv2username" type="text" value="{$foxpostv2username}" autocomplete="off"></td>
                            <td><label>{at('v2 Password')}:</label></td>
                            <td><input name="foxpostv2password" type="password" value="{$foxpostv2password}" autocomplete="off"></td>
                            <td><label>{at('v2 API key')}:</label></td>
                            <td><input name="foxpostv2apikey" type="text" value="{$foxpostv2apikey}" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td><label>{at('GLS API URL')}:</label></td>
                            <td><input name="glsapiurl" type="text" value="{$glsapiurl}"></td>
                            <td><label>{at('Client number')}:</label></td>
                            <td><input name="glsclientnumber" type="text" value="{$glsclientnumber}" autocomplete="off"></td>
                            <td><label>{at('Username')}:</label></td>
                            <td><input name="glsusername" type="text" value="{$glsusername}" autocomplete="off"></td>
                            <td><label>{at('Password')}:</label></td>
                            <td><input name="glspassword" type="password" value="{$glspassword}" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td><label>{at('GLS PDF mappa')}:</label></td>
                            <td><input name="glsparcellabeldir" type="text" value="{$glsparcellabeldir}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('eMAG API URL')}:</label></td>
                            <td><input name="emagapiurl" type="text" value="{$emagapiurl}"></td>
                            <td><label>{at('Username')}:</label></td>
                            <td><input name="emagusername" type="text" value="{$emagusername}" autocomplete="off"></td>
                            <td><label>{at('Usercode')}:</label></td>
                            <td><input name="emagusercode" type="text" value="{$emagusercode}" autocomplete="off"></td>
                            <td><label>{at('Password')}:</label></td>
                            <td><input name="emagpassword" type="password" value="{$emagpassword}" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td><label>{at('GLS csomagpont URL')}:</label></td>
                            <td><input name="glsterminalurl" type="text" value="{$glsterminalurl}"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="MiniCRMTab" class="mattkarb-page" data-visible="visible">
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MiniCRMHasznalatbanEdit"><label>{at('MiniCRM bekapcsolva')}:</label></span>
                            <input id="MiniCRMHasznalatbanEdit" name="minicrmhasznalatban" type="checkbox"{if ($minicrmhasznalatban)} checked="checked"{/if}">
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MiniCRMSystemIdEdit">{at('MiniCRM System Id')}:</label></span>
                            <input id="MiniCRMSystemIdEdit" name="minicrmsystemid" type="text" value="{$minicrmsystemid}" autocomplete="off">
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MiniCRMAPIKeyEdit">{at('MiniCRM API key')}:</label></span>
                            <input id="MiniCRMAPIKeyEdit" name="minicrmapikey" type="text" value="{$minicrmapikey}" autocomplete="off">
                        </div>
                    </div>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MiniCRMPartnertorzsEdit"><label>{at('Partnertörzs modul')}:</label></span>
                            <input id="MiniCRMPartnertorzsEdit" name="minicrmpartnertorzs" type="text" value="{$minicrmpartnertorzs}">
                        </div>
                        <div class="setuprow">
                            <span class="setuplabel"><label for="MiniCRMRendezvenyJelentkezesEdit">{at('Rendezvény modul')}:</label></span>
                            <input id="MiniCRMRendezvenyJelentkezesEdit" name="minicrmrendezvenyjelentkezes" type="text"
                                   value="{$minicrmrendezvenyjelentkezes}">
                        </div>
                    </div>
                </div>
                <div id="EmailTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label>{at('Email feladója')}:</label></td>
                            <td><input name="emailfrom" type="text" value="{$emailfrom}" title="Az email és a név pontosvesszővel elválasztva" size="60"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Válasz cím')}:</label></td>
                            <td><input name="emailreplyto" type="text" value="{$emailreplyto}" size="60"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Bcc')}:</label></td>
                            <td><input name="emailbcc" type="text" value="{$emailbcc}" title="Vesszővel elválasztva" size="60"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Bizonylat státuszváltás értesítő')}:</label></td>
                            <td><input name="emailstatuszvaltas" type="text" value="{$emailstatuszvaltas}" title="Vesszővel elválasztva" size="60"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Könyvelő email')}:</label></td>
                            <td><input name="konyveloemail" type="text" value="{$konyveloemail}" size="60"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="FeedTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label>{at('Hírek száma a feed-ben')}:</label></td>
                            <td><input name="feedhirdb" type="number" value="{$feedhirdb}"></td>
                            <td><label>{at('Hír feed címe')}:</label></td>
                            <td><input name="feedhirtitle" type="text" value="{$feedhirtitle}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Hír feed leírása')}:</label></td>
                            <td colspan="3"><input name="feedhirdescription" type="text" value="{$feedhirdescription}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Termékek száma a feed-ben')}:</label></td>
                            <td><input name="feedtermekdb" type="number" value="{$feedtermekdb}"></td>
                            <td><label>{at('Termék feed címe')}:</label></td>
                            <td><input name="feedtermektitle" type="text" value="{$feedtermektitle}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Termék feed leírása')}:</label></td>
                            <td colspan="3"><input name="feedtermekdescription" type="text" value="{$feedtermekdescription}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Posztok száma a feed-ben')}:</label></td>
                            <td><input name="feedblogdb" type="number" value="{$feedblogdb}"></td>
                            <td><label>{at('Blog feed címe')}:</label></td>
                            <td><input name="feedblogtitle" type="text" value="{$feedblogtitle}"></td>
                        </tr>
                        <tr>
                            <td><label>{at('Blog feed leírása')}:</label></td>
                            <td colspan="3"><input name="feedblogdescription" type="text" value="{$feedblogdescription}"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="SitemapTab" class="mattkarb-page" data-visible="visible">
                    <table>
                        <tbody>
                        <tr>
                            <td><label>{at('Statikus lap prioritás')}:</label></td>
                            <td><input name="statlapprior" type="text" value="{$statlapprior}"></td>
                            <td><label>{at('changefreq')}:</label></td>
                            <td><select name="statlapchangefreq" value="{$statlapchangefreq}">
                                    <option value="always"{if ($statlapchangefreq=='always')} selected="selected"{/if}>always</option>
                                    <option value="hourly"{if ($statlapchangefreq=='hourly')} selected="selected"{/if}>hourly</option>
                                    <option value="daily"{if ($statlapchangefreq=='daily')} selected="selected"{/if}>daily</option>
                                    <option value="weekly"{if ($statlapchangefreq=='weekly')} selected="selected"{/if}>weekly</option>
                                    <option value="monthly"{if ($statlapchangefreq=='monthly')} selected="selected"{/if}>monthly</option>
                                    <option value="yearly"{if ($statlapchangefreq=='yearly')} selected="selected"{/if}>yearly</option>
                                    <option value="never"{if ($statlapchangefreq=='never')} selected="selected"{/if}>never</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>{at('Blogposzt prioritás')}:</label></td>
                            <td><input name="blogposztprior" type="text" value="{$blogposztprior}"></td>
                            <td><label>{at('changefreq')}:</label></td>
                            <td><select name="blogposztchangefreq" value="{$blogposztchangefreq}">
                                    <option value="always"{if ($blogposztchangefreq=='always')} selected="selected"{/if}>always</option>
                                    <option value="hourly"{if ($blogposztchangefreq=='hourly')} selected="selected"{/if}>hourly</option>
                                    <option value="daily"{if ($blogposztchangefreq=='daily')} selected="selected"{/if}>daily</option>
                                    <option value="weekly"{if ($blogposztchangefreq=='weekly')} selected="selected"{/if}>weekly</option>
                                    <option value="monthly"{if ($blogposztchangefreq=='monthly')} selected="selected"{/if}>monthly</option>
                                    <option value="yearly"{if ($blogposztchangefreq=='yearly')} selected="selected"{/if}>yearly</option>
                                    <option value="never"{if ($blogposztchangefreq=='never')} selected="selected"{/if}>never</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>{at('Terméklap prioritás')}:</label></td>
                            <td><input name="termekprior" type="text" value="{$termekprior}"></td>
                            <td><label>{at('changefreq')}:</label></td>
                            <td><select name="termekchangefreq" value="{$termekchangefreq}">
                                    <option value="always"{if ($termekchangefreq=='always')} selected="selected"{/if}>always</option>
                                    <option value="hourly"{if ($termekchangefreq=='hourly')} selected="selected"{/if}>hourly</option>
                                    <option value="daily"{if ($termekchangefreq=='daily')} selected="selected"{/if}>daily</option>
                                    <option value="weekly"{if ($termekchangefreq=='weekly')} selected="selected"{/if}>weekly</option>
                                    <option value="monthly"{if ($termekchangefreq=='monthly')} selected="selected"{/if}>monthly</option>
                                    <option value="yearly"{if ($termekchangefreq=='yearly')} selected="selected"{/if}>yearly</option>
                                    <option value="never"{if ($termekchangefreq=='never')} selected="selected"{/if}>never</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>{at('Kategória oldal prioritás')}:</label></td>
                            <td><input name="kategoriaprior" type="text" value="{$kategoriaprior}"></td>
                            <td><label>{at('changefreq')}:</label></td>
                            <td><select name="kategoriachangefreq" value="{$kategoriachangefreq}">
                                    <option value="always"{if ($kategoriachangefreq=='always')} selected="selected"{/if}>always</option>
                                    <option value="hourly"{if ($kategoriachangefreq=='hourly')} selected="selected"{/if}>hourly</option>
                                    <option value="daily"{if ($kategoriachangefreq=='daily')} selected="selected"{/if}>daily</option>
                                    <option value="weekly"{if ($kategoriachangefreq=='weekly')} selected="selected"{/if}>weekly</option>
                                    <option value="monthly"{if ($kategoriachangefreq=='monthly')} selected="selected"{/if}>monthly</option>
                                    <option value="yearly"{if ($kategoriachangefreq=='yearly')} selected="selected"{/if}>yearly</option>
                                    <option value="never"{if ($kategoriachangefreq=='never')} selected="selected"{/if}>never</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>{at('Főoldal prioritás')}:</label></td>
                            <td><input name="fooldalprior" type="text" value="{$fooldalprior}"></td>
                            <td><label>{at('changefreq')}:</label></td>
                            <td><select name="fooldalchangefreq" value="{$fooldalchangefreq}">
                                    <option value="always"{if ($fooldalchangefreq=='always')} selected="selected"{/if}>always</option>
                                    <option value="hourly"{if ($fooldalchangefreq=='hourly')} selected="selected"{/if}>hourly</option>
                                    <option value="daily"{if ($fooldalchangefreq=='daily')} selected="selected"{/if}>daily</option>
                                    <option value="weekly"{if ($fooldalchangefreq=='weekly')} selected="selected"{/if}>weekly</option>
                                    <option value="monthly"{if ($fooldalchangefreq=='monthly')} selected="selected"{/if}>monthly</option>
                                    <option value="yearly"{if ($fooldalchangefreq=='yearly')} selected="selected"{/if}>yearly</option>
                                    <option value="never"{if ($fooldalchangefreq=='never')} selected="selected"{/if}>never</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="admin-form-footer">
                <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
                <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
            </div>
        </form>
    </div>
{/block}