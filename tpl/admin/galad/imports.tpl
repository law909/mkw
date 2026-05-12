{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/superzoneb2b/importsform.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Termék importok')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#DefaTab">{t('Importok')}</a></li>
                </ul>
                <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                    <div>
                        <span id="TermekKategoria1" class="js-termekfabutton" data-text="{t('válasszon')}" data-name="termekfa1" data-value="">Ebbe a kategóriába kerüljenek a termékek</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Importálandó fájl:</label>
                        <input name="toimport" type="file">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Termékek tól-ig:</label>
                        <input name="dbtol"> - <input name="dbig">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Nem található termék felvitele újként:</label>
                        <input name="createuj" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szimport" class="js-szinvarimport">Termék adatok</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szcimkeimport" class="js-szcimkeimport">Termék adatok cimkékkel</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szeanimport" class="js-szeanimport">Termékváltozat vonalkódok</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szmeretimport" class="js-szmeretimport">Termék méretek</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szcolorimport" class="js-szcolorimport">Szín kódok</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/fcmotoorderimport" class="js-fcmotoorderimport">FCMoto rendelés</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/szin" class="js-szinimport">Színek</a>
                        <a href="/admin/import/meret" class="js-meretimport">Méretek</a>
                        <a href="/admin/import/orszag" class="js-orszagimport">Országok</a>
                    </div>
                    <p>Az excel táblában használható fejlécek:</p>
                    <ul>
                        <li>kod - termék kódja</li>
                        <li>vonalkod - termék vonalkódja</li>
                        <li>cikkszam - termék cikkszáma</li>
                        <li>vtsz - termék vtsz</li>
                        <li>nev_hu - termék magyar neve</li>
                        <li>nev_en - termék angol neve</li>
                        <li>netto_huf_vonalkodos - nettó HUF ár "vonalkodos" ársávba</li>
                        <li>brutto_huf_vonalkodos - bruttó HUF ár "vonalkodos" ársávba</li>
                    </ul>
                    <p>A termék azonosítás "kod", "vonalkod" vagy "cikkszam" oszlop alapján történik.<br>
                        Ha egy oszlopnak nincs fejléce, a program nem foglalkozik a tartalmával.<br>
                        Ha ugyanolyan valutanemben és ársávban nettó és bruttó ár is szerepel, a program a bruttót fogja betölteni.</p>
                </div>
            </div>
        </form>
    </div>
{/block}