<tr id="mattable-row_{$_termek.id}" data-egyedid="{$_termek.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{if ($_termek.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_termek.kepurl}" target="_blank"><img src="{$mainurl}{$_termek.kepurlsmall}"/></a>{/if}</td>
                    <td>
                        <a class="mattable-editlink" href="#" data-termekid="{$_termek.id}" data-oper="edit" title="{at('Szerkeszt')}">{if ($maintheme == 'superzoneb2b')}{$_termek.cikkszam}&nbsp;{/if}{$_termek.nev}</a>
                        {if (haveJog(20))}
                        <a class="js-karton" href="#" data-termekid="{$_termek.id}" title="{at('Karton')}" target="_blank"><span class="ui-icon ui-icon-folder-collapsed"></span></a>
                        {/if}
                        <a class="mattable-dellink" href="#" data-termekid="{$_termek.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="3">{$_termek.termekfa1nev} | {$_termek.termekfa2nev} | {$_termek.termekfa3nev}{if ($_termek.termekcsoportnev)} ({$_termek.termekcsoportnev}){/if}</td>
                                </tr>
                                <tr>
                                    <td>{at('Link')}:</td>
                                    <td colspan="3"><a href="{$mainurl}/termek/{$_termek.slug}" target="_blank">/termek/{$_termek.slug}</a></td>
                                </tr>
                                <tr>
                                    <td>{at('Gyártó')}:</td><td colspan="3">{$_termek.gyartonev}</td>
                                </tr>
                                <tr>
                                    <td>{at('Megtekintve')}:</td><td>{$_termek.megtekintesdb}</td>
                                    <td>{at('Megvásárolva')}:</td><td>{$_termek.megvasarlasdb}</td>
                                </tr>
                                <tr>
                                    <td>{at('Cikkszám')}:</td><td colspan="3">{$_termek.cikkszam}</td>
                                </tr>
                                <tr>
                                    <td>{at('ME')}:</td><td colspan="3">{$_termek.me}</td>
                                </tr>
                                <tr>
                                    <td>{at('Min. bolti készlet')}:</td><td colspan="3">{number_format($_termek.minboltikeszlet, 2, '.', ' ')}</td>
                                </tr>
                                {if (!$setup.arsavok)}
                                <tr>
                                    <td>{at('Nettó ár')}:</td><td>{number_format($_termek.netto,4,'.',' ')}</td>
                                    <td>{at('Bruttó ár')}:</td><td>{number_format($_termek.brutto,4,'.',' ')}</td>
                                </tr>
                                <tr>
                                    <td>{at('Akciós n.ár')}:</td><td>{number_format($_termek.akciosnetto,4,'.',' ')}</td>
                                    <td>{at('Akciós b.ár')}:</td><td>{number_format($_termek.akciosbrutto,4,'.',' ')}</td>
                                </tr>
                                {else}
                                <tr>
                                    <td>{at('Nettó ár')}:</td><td>{number_format($_termek.netto,2,'.',' ')}</td>
                                </tr>
                                <tr>
                                    <td>{at('Bruttó ár')}:</td><td>{number_format($_termek.brutto,2,'.',' ')}</td>
                                </tr>
                                {/if}
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_termek.cimkek}
    </td>
    <td class="cell">
        <table>
            <tbody>
            {foreach $_termek.valtozatkeszlet as $vk}
                <tr>
                    <td><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.ertek1}</a></td>
                    <td><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.ertek2}</a></td>
                    <td class="keszletoszlop"><a href="#" data-id="{$vk.id}" class="js-valtozatkeszletreszletezobutton">{$vk.keszlet}</a></td>
                    <td class="keszletoszlop">{$vk.foglaltmennyiseg}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="inaktiv" class="js-flagcheckbox{if ($_termek.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="lathato" class="js-flagcheckbox{if ($_termek.lathato)} ui-state-hover{/if}">{at('Látható')} {$webshop1name}</a></td></tr>
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                    <tr><td><a href="#" data-id="{$_termek.id}" data-flag="lathato{$cikl}" class="js-flagcheckbox{if ($_termek["lathato$cikl"])} ui-state-hover{/if}">{at('Látható')} {$webshop{$cikl}name}</a></td></tr>
                    {/for}
                {/if}
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="ajanlott" class="js-flagcheckbox{if ($_termek.ajanlott)} ui-state-hover{/if}">{at('Ajánlott')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="kiemelt" class="js-flagcheckbox{if ($_termek.kiemelt)} ui-state-hover{/if}">{at('Kiemelt')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="hozzaszolas" class="js-flagcheckbox{if ($_termek.hozzaszolas)} ui-state-hover{/if}">{at('Hozzá lehet szólni')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="mozgat" class="js-flagcheckbox{if ($_termek.mozgat)} ui-state-hover{/if}">{at('Készletet mozgat')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="nemkaphato" class="js-flagcheckbox{if ($_termek.nemkaphato)} ui-state-hover{/if}">{at('Nem kapható')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="fuggoben" class="js-flagcheckbox{if ($_termek.fuggoben)} ui-state-hover{/if}">{at('Függőben')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="termekexportbanszerepel" class="js-flagcheckbox{if ($_termek.termekexportbanszerepel)} ui-state-hover{/if}">{at('Exportokban szerepel')}</a></td></tr>
                {if ($setup.emag)}
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="emagtiltva" class="js-flagcheckbox{if ($_termek.emagtiltva)} ui-state-hover{/if}">{at('eMAG tiltva')}</a></td></tr>
                {/if}
                <tr><td>{at('Hűségpont arány')}: {$_termek.hparany}</td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" class="js-keszletreszletezobutton">{at('Készlet')}: {$_termek.keszlet}</a></td></tr>
            </tbody>
        </table>
    </td>
</tr>