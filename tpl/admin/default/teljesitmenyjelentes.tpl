{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/teljesitmenyjelentes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Teljesítmény jelentés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Teljesítmény jelentés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="teljesitmenyjelentes" action="" target="_blank">
                    {include "comp_idoszak.tpl" comptype="datum"}
                    <div class="matt-hseparator"></div>

                    <div>
                        <a class="js-refresh">{at('Frissít')}</a>
                    </div>
                </form>
            </div>
            <div id="eredmeny"></div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}