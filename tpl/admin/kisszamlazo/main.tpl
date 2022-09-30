{extends "../base.tpl"}

{block "kozep"}
    <div class="component-container">
    {include "../default/comp_noallapot.tpl"}
    </div>
    {if (haveJog(20))}
        {if ($setup.bankpenztar)}
            <div class="component-container">
                <div class="ui-widget ui-widget-content ui-corner-all">
                    <div class="ui-widget-header ui-corner-top">
                        <div class="mainboxinner ui-corner-top">Kintlevőségek</div>
                    </div>
                    <div class="mainboxinner">
                        <div class="mainboxinner">
                            <table>
                                <thead>
                                <tr>
                                    <th>Lejárt kintlevőség</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $lejartkintlevoseg as $lk}
                                    <tr>
                                        <td>{$lk.nev}</td>
                                        <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                            <table>
                                <thead>
                                <tr>
                                    <th>Össz. kintlevőség</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $kintlevoseg as $lk}
                                    <tr>
                                        <td>{$lk.nev}</td>
                                        <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    {/if}
{/block}