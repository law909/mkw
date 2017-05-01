{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/idoszakipenztarjelenteslista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Időszaki pénztárjelentés')}</h3>
        </div>
        <div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
            {if ($setup.editstyle=='tab')}
                <ul>
                    <li><a href="#DefaTab">{at('Időszaki pénztárjelentés')}</a></li>
                </ul>
            {/if}
            {if ($setup.editstyle=='dropdown')}
                <div class="mattkarb-titlebar" data-caption="{at('Időszaki pénztárjelentés')}" data-refcontrol="#DefaTab"></div>
            {/if}
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="idoszakipenztarjelentes" action="" target="_blank">
                    {include "comp_idoszak.tpl" comptype="datum"}
                    <div class="matt-hseparator"></div>
                    {include "comp_penztarselect.tpl"}
                    <div class="matt-hseparator"></div>

                    <div>
                        <a href="/admin/idoszakipenztarjelenteslista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/idoszakipenztarjelenteslista/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}