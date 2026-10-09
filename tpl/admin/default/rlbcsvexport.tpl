{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/rlbcsvexport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('RLB CSV export')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('RLB CSV export')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="rlbcsvexport" action="" target="_blank">
                    {mezocsoport cim="Export beállítások" egyoszlop=true}
                        {mezo cimke="Utolsó feladott számla" for="UtolsoszamlaEdit"}
                            <input id="UtolsoszamlaEdit" name="utolsoszamla" value="{$utolsoszamla}">
                        {/mezo}
                        {mezo cimke="Elválasztó" for="ElvalasztoEdit"}
                            <select id="ElvalasztoEdit" name="elvalaszto">
                                <option value="1" selected="selected">Pontosvessző</option>
                                <option value="2">Vessző</option>
                                <option value="3">Tab</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Mező körbe" for="SzovegkorulEdit"}
                            <select id="SzovegkorulEdit" name="szovegkorul">
                                <option value="1" selected="selected">Nincs</option>
                                <option value="2">Idézőjel a szövegek körül</option>
                                <option value="3">Idézőjel minden mező körül</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Dátum" for="DatumformaEdit"}
                            <select id="DatumformaEdit" name="datum">
                                <option value="1" selected="selected">Ponttal elválasztva</option>
                                <option value="2">Kötőjellel elválasztva</option>
                                <option value="3">Perrel elválasztva</option>
                                <option value="4">Nincs elválasztva</option>
                            </select>
                        {/mezo}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/rlbcsvexport/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}