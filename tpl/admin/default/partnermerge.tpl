{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/partnermerge.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Partner összefűzés')}</h3>
        </div>
        <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#DefaTab">{at('Partner összefűzés')}</a></li>
                </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="partnermerge" action="" target="_blank">
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="PartnerRolEdit">{at('PartnerRŐL')}:</label>
                        <select id="PartnerRolEdit" name="partnerrol" class="mattable-important">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.id}) {$_mk.email} ({$_mk.cim})</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="PartnerReEdit">{at('PartnerRE')}:</label>
                        <select id="PartnerReEdit" name="partnerre" class="mattable-important">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.id}) {$_mk.email} ({$_mk.cim})</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>RŐL partner törlése</label><input type="checkbox" name="roldel" checked="checked">
                    </div>
                    <div>
                        <label>Partner adat módosítás nem szigorú bizonylatokon (superzone)</label><input type="checkbox" name="bizupdate">
                    </div>
                    <div>
                        <label>Név csere</label><input type="checkbox" name="nevcsere">
                    </div>
                    <div>
                        <label>Cím csere</label><input type="checkbox" name="cimcsere">
                    </div>
                    <div>
                        <label>Email csere</label><input type="checkbox" name="emailcsere">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/partnermerge" class="js-okbutton">{at('OK')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}