{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/rontasvisszavetel.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Rontás visszavétele')}</h3>
        </div>
        <form id="rontasvisszavetel" action="">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <label for="RvBizonylatEdit">{at('Bizonylatszám')}:</label>
                    <input id="RvBizonylatEdit" name="id" type="text" size="25" autocomplete="off">
                    <a href="/admin/rontasvisszavetel/info" class="js-keresbutton">{at('Keresés')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div class="js-adatok"></div>
                <div class="js-gombsor" style="display:none">
                    <div class="matt-hseparator"></div>
                    <a href="/admin/rontasvisszavetel/restore" class="js-okbutton">{at('Rontás visszavétele')}</a>
                </div>
                <p class="js-uzenet"></p>
                <p class="mattkarb-hint">
                    {at('A bizonylat és a tételei újra élők lesznek, a folyószámla sorai a mentéskor újraképződnek. A rontással együtt lerontott pénztár- és bankbizonylat nem áll vissza magától: a bizonylatra hivatkozó rontott pénzmozgások közül jelöld be, melyik álljon vissza. A pénzmozgásnak csak a bizonylatra szóló tételei állnak vissza (és a fej, ha rontott), a más bizonylatra szóló tételeihez nem nyúl. Ha az automatikus pénztárbizonylatos típusnál a pénztárbizonylatot nem állítod vissza, a mentés újat képez.')}
                </p>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
