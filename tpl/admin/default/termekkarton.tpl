{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekkarton.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Termék karton')}<span class="js-termekfejlec"> - {$cikkszam} {$termeknev}</span></h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Termék"}
                    {if ($termekvalaszto)}
                        {mezo cimke="Termék" for="TermekEdit" szeles=true}
                            <input id="TermekEdit" class="js-termekselect" type="text" size="60" autocomplete="off">
                        {/mezo}
                        {mezo cimke="Egyedi azonosító alapján" for="EgyediazonositoKeresoEdit" szeles=true}
                            <div class="mattkarb-mezogomb">
                                <input id="EgyediazonositoKeresoEdit" class="js-egyediazonositokereso" type="text" size="30"
                                       autocomplete="off">
                                <a href="#" class="js-egyediazonositokeres">{at('Keres')}</a>
                                <span class="js-egyediazonositouzenet"></span>
                            </div>
                        {/mezo}
                    {/if}
                    {mezo cimke="Változat" for="ValtozatEdit" ujsor=true}
                        <select id="ValtozatEdit" name="valtozat">
                            <option value="0">{at('válasszon')}</option>
                            {foreach $valtozatlista as $valtozat}
                                <option value="{$valtozat.id}">{$valtozat.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {if ($kellegyediazonosito)}
                        {mezo cimke="Egyedi azonosító" for="EgyediazonositoEdit"}
                            <input id="EgyediazonositoEdit" class="js-egyediazonositoszuro" name="egyediazonosito" type="text"
                                   autocomplete="off">
                        {/mezo}
                    {/if}
                    <div class="mattkarb-szakaszcim">{at('Időszak')}</div>
                    {include "comp_idoszak.tpl" comptype="szamla" mezo=true}
                    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                    {include "comp_partnerselect.tpl" mezo=true}
                    {mezo cimke="Raktár" for="RaktarEdit" ujsor=true}
                        <select id="RaktarEdit" name="raktar">
                            <option value="0">{at('válasszon')}</option>
                            {foreach $raktarlista as $raktar}
                                <option value="{$raktar.id}">{$raktar.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Készletmozgás" for="MozgatEdit"}
                        <select id="MozgatEdit" name="mozgat">
                            <option value="0">{at('minden')}</option>
                            <option value="1"{if ($keszletetmozgat)} selected="selected"{/if}>{at('csak ami mozgat')}</option>
                            <option value="2"{if (!$keszletetmozgat)} selected="selected"{/if}>{at('csak ami NEM mozgat')}</option>
                        </select>
                    {/mezo}
                    {mezo cimke="Rontottak" for="RontottEdit" ujsor=true}
                        <select id="RontottEdit" name="rontott">
                            <option value="1">{at('rontottak látszanak')}</option>
                            <option value="2" selected="selected">{at('rontottak NEM látszanak')}</option>
                        </select>
                    {/mezo}
                    {include "comp_partnercimkefilter.tpl" mezo=true}
                {/mezocsoport}
                <input name="termekid" type="hidden" value="{$termekid}">
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}