{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/folyoszamlaellenorzes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Folyószámla ellenőrzés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Folyószámla ellenőrzés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="folyoszamlaellenorzes" action="" target="_blank">
                    <div class="matt-row">
                        <p>{at('A riport a bizonylatok és a hozzájuk könyvelt pénzmozgások folyószámla sorait veti össze, és azokat mutatja, ahol a két oldal elcsúszott egymástól.')}</p>
                        <ul>
                            <li>{at('holt (rontott, stornó, stornózott) vagy nem létező bizonylatra könyvelt pénzmozgás')}</li>
                            <li>{at('pénzmozgás, aminek a bizonylat oldalon nincs párja')}</li>
                            <li>{at('nem párosuló esedékesség, eltérő partner, eltérő valutanem')}</li>
                            <li>{at('elavult folyószámla sor a bizonylat vagy a pénzmozgás oldalon')}</li>
                        </ul>
                        <p>{at('Ellenőrzésenként legfeljebb')} {$rowlimit} {at('sort mutat, a talált darabszám ettől függetlenül a teljes szám.')}</p>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/folyoszamlaellenorzes/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/folyoszamlaellenorzes/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
