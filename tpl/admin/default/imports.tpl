{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/importsform.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Termék importok')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#DefaTab">{at('Importok')}</a></li>
                </ul>
                <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                    <div>
                        <span id="TermekKategoria1" class="js-termekfabutton" data-name="termekfa1" data-value="{$termekfa.id}">{$termekfa.caption}</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Termékek tól-ig:</label>
                        <input name="dbtol"> - <input name="dbig">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Batch size:</label>
                        <input name="batchsize" value="20">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Hosszú leírás módosítása létező terméknél is:</label>
                        <input name="editleiras" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Név módosítása létező terméknél is:</label>
                        <input name="editnev" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Nem található termék felvitele újként:</label>
                        <input name="createuj" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Importálandó fájl:</label>
                        <input name="toimport" type="file">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Az ár az importált ár ennyi százaléka legyen:</label>
                        <input name="arszaz" value="100">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Minimum ár:</label>
                        <input name="minimumar" value="490">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Delton letöltés kell:
                            <abel>
                                <input name="deltondownload" type="checkbox" checked="checked">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Smile ebike letöltés kell:
                            <abel>
                                <input name="smileebikedownload" type="checkbox" checked="checked">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/kreativ" class="js-kreativimport">Kreativ puzzle</a>
                        <a href="/admin/import/delton" class="js-deltonimport">Delton</a>
                        <a href="/admin/import/reintex" class="js-reinteximport">Reintex</a>
                        <a href="/admin/import/tutisport" class="js-tutisportimport">Tutisport</a>
                        <a href="/admin/import/makszutov" class="js-makszutovimport">Makszutov</a>
                        <a href="/admin/import/nomad" class="js-nomadimport">Nomád</a>
                        <a href="/admin/import/nika" class="js-nikaimport">Nika</a>
                        <a href="/admin/import/haffner24" class="js-haffner24import">Haffner24</a>
                        <a href="/admin/import/smileebike" class="js-smileebikeimport">Smile ebike</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/legavenueszotar" class="js-legavenueszotarimport">Legavenue szótár</a>
                        <a href="/admin/import/legavenue" class="js-legavenueimport">Legavenue</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/evona" class="js-evonaimport">Evona</a>
                        <a href="/admin/import/evonaxml" class="js-evonaxmlimport">Evona XML</a>
                        <a href="/admin/import/gulf" class="js-gulfimport">Gulf árazás</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/foxpostterminal" class="js-foxpostterminalimport">Foxpost terminálok</a>
                        <a href="/admin/import/glsterminal" class="js-glsterminalimport">GLS terminálok</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/aszfdownload" class="js-aszfdownload">ÁSZF letöltés</a>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <div>
                            <label>Vatera beérkezett rendelések:</label>
                            <input name="vaterarendeles" type="file">
                        </div>
                        <div>
                            <label>Vatera eladott termékek:</label>
                            <input name="vateratermek" type="file">
                        </div>
                        <a href="/admin/import/vatera" class="js-vateraimport">Vatera megrendelések</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
{/block}