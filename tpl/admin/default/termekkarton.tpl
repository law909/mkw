{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekkarton.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Termék karton')} - {$cikkszam} {$termeknev}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <label for="ValtozatEdit">Változat:</label>
                    <select id="ValtozatEdit" name="valtozat">
                        <option value="0">válasszon</option>
                        {foreach $valtozatlista as $valtozat}
                            <option value="{$valtozat.id}">{$valtozat.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="szamla"}
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="RaktarEdit">Raktár:</label>
                    <select id="RaktarEdit" name="raktar">
                        <option value="0">válasszon</option>
                        {foreach $raktarlista as $raktar}
                            <option value="{$raktar.id}">{$raktar.caption}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <select name="mozgat">
                        <option value="0">minden</option>
                        <option value="1" selected="selected">csak ami mozgat</option>
                        <option value="2">csak ami NEM mozgat</option>
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">Frissít</a>
                <input name="termekid" type="hidden" value="{$termekid}">
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}