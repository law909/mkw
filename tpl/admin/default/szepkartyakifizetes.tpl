{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/szepkartyakifizetes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('SZÉP kártya kifizetés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('SZÉP kártya kifizetés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {foreach $tomb as $_egyed}
                <table data-egyedid="{$_egyed.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <tbody>
                        <tr>
                            <td class="cell">
                                {$_egyed.id}
                                <a class="js-kiegyenlit" href="#" data-egyedid="{$_egyed.id}" title="{at('Kiegyenlít')}"><span class="ui-icon ui-icon-check"></span></a>
                                <table>
                                    <tbody>
                                        <tr><td  colspan="2" class="mattable-important">
                                            {$_egyed.szamlanev}
                                        </td></tr>
                                        <tr><td colspan="2">
                                            {$_egyed.szamlairszam} {$_egyed.szamlavaros}, {$_egyed.szamlautca} {$_egyed.szamlahazszam}
                                        </td></tr>
                                        <tr><td colspan="2">
                                            <a href="mailto:{$_egyed.partneremail}">{$_egyed.partneremail}</a>
                                        </td></tr>
                                        <tr><td colspan="2">
                                            {$_egyed.partnertelefon}
                                        </td></tr>
                                        <tr><td>{at('Létrehozva')}:</td><td>{$_egyed.createdby} {$_egyed.createdstr}</td></tr>
                                        <tr><td>{at('Módosítva')}:</td><td>{$_egyed.updatedby} {$_egyed.lastmodstr}</td></tr>
                                        {if ($_egyed.belsomegjegyzes)}
                                            <tr><td colspan="5" class="guestpartner">
                                                    {$_egyed.belsomegjegyzes}
                                                </td>
                                            </tr>
                                        {/if}
                                    </tbody>
                                </table>
                            </td>
                            <td class="cell">
                                <table><tbody>
                                    <tr><td class="mattable-important">{$_egyed.szepkartyatipus}</td></tr>
                                    <tr><td class="mattable-important">{$_egyed.szepkartyaszam}</td></tr>
                                    <tr><td class="mattable-important">{$_egyed.szepkartyanev}</td></tr>
                                    <tr><td class="mattable-important">{$_egyed.szepkartyaervenyesseg}</td></tr>
                                </tbody></table>
                            </td>
                            <td class="cell">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>{at('Nettó')}:</td><td class="mattable-rightaligned pricenowrap">{number_format($_egyed.netto, 2, '.', ' ')}</td>
                                    </tr>
                                    <tr>
                                        <td>{at('ÁFA')}:</td>
                                        <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.afa, 2, '.', ' ')}</td>
                                    </tr>
                                    <tr class="mattable-important">
                                        <td>{at('Bruttó')}:</td>
                                        <td class="mattable-rightaligned pricenowrap">{number_format($_egyed.brutto, 2, '.', ' ')}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                {/foreach}
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}