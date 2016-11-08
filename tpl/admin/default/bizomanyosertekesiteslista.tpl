{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizomanyosertekesiteslista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Bizományos értékesítés lista')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="szamla"}
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="ErtekEdit">Érték:</label>
                    <select id="ErtekEdit" name="ertektipus">
                        <option value="0">nincs</option>
                        <option value="1">bizonylaton szereplő nettó</option>
                        <option value="2">bizonylaton szereplő bruttó</option>
                        <option value="3">bizonylaton szereplő nettó HUF</option>
                        <option value="4">bizonylaton szereplő bruttó HUF</option>
                        {if ($setup.arsavok)}
                        <option value="5">választott ársáv nettó</option>
                        <option value="6">választott ársáv bruttó</option>
                        {else}
                        <option value="7">eladási ár nettó</option>
                        <option value="8">eladási ár bruttó</option>
                        {/if}
                    </select>
                </div>
                {if ($setup.arsavok)}
                <div class="matt-hseparator"></div>
                {include "comp_arsavselect.tpl"}
                {/if}
                <div class="matt-hseparator"></div>
                {include "comp_partnercimkefilter.tpl"}
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">Frissít</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}