{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/osslista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('OSS kimutatás')}</h3>
        </div>
        <form id="osslista" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EvEdit">{at('Időszak')}:</label>
                    <select id="EvEdit" name="ev">
                        {foreach $evek as $_ev}
                            <option value="{$_ev}"{if ($_ev == $ev)} selected="selected"{/if}>{$_ev}</option>
                        {/foreach}
                    </select>
                    <select id="NegyedevEdit" name="negyedev">
                        {for $_q = 1 to 4}
                            <option value="{$_q}"{if ($_q == $negyedev)} selected="selected"{/if}>{$_q}. {at('negyedév')}</option>
                        {/for}
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EkbhufEdit">{at('EKB árfolyam kézzel')} (1 EUR = ? HUF):</label>
                    <input id="EkbhufEdit" name="ekbhuf" type="text" size="10">
                    <span class="mattkarb-hint">{at('Üresen az EKB-tól kéri le a negyedév utolsó napjára (ha aznap nem volt jegyzés, a következő jegyzett napra). Csak a forintos számlákat érinti, a kiválasztott negyedévben.')}</span>
                </div>
                <div class="matt-hseparator"></div>
                <div class="mattkarb-hint">{at('Az EU-s tagállamba (szállítási cím, ha nincs, a vevő országa) közösségi adószám nélküli vevőnek kiállított számlák, előleg- és kézi számlák, a teljesítés negyedévében. A stornó a kelte negyedévében szerepel; ha az eredeti számla korábbi negyedévbe esett, a korrekciók között, annak az időszaknak az árfolyamán.')}</div>
                <div class="matt-hseparator"></div>
                <a href="/admin/osslista/get" class="js-okbutton">{at('OK')}</a>
                <a href="/admin/osslista/export" class="js-exportbutton">{at('Export')}</a>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
