<tr id="mattable-row_{$_termek.id}" data-egyedid="{$_termek.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr>
                    <td>
                        <a class="mattable-editlink" href="#" data-termekid="{$_termek.id}" data-oper="edit" title="{at('Szerkeszt')}">{if ($maintheme == 'superzoneb2b')}{$_termek.cikkszam}&nbsp;{/if}{$_termek.nev}</a>
                        <a class="js-karton" href="#" data-termekid="{$_termek.id}" title="{at('Karton')}" target="_blank"><span class="ui-icon ui-icon-folder-collapsed"></span></a>
                        <a class="mattable-dellink" href="#" data-termekid="{$_termek.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="3">{$_termek.termekfa1nev} | {$_termek.termekfa2nev} | {$_termek.termekfa3nev}{if ($_termek.termekcsoportnev)} ({$_termek.termekcsoportnev}){/if}</td>
                                </tr>
                                <tr>
                                    <td>{at('Cikkszám')}:</td><td colspan="3">{$_termek.cikkszam}</td>
                                </tr>
                                <tr>
                                    <td>{at('ME')}:</td><td colspan="3">{$_termek.me}</td>
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
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="inaktiv" class="js-flagcheckbox{if ($_termek.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="mozgat" class="js-flagcheckbox{if ($_termek.mozgat)} ui-state-hover{/if}">{at('Készletet mozgat')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" data-flag="eladhato" class="js-flagcheckbox{if ($_termek.eladhato)} ui-state-hover{/if}">{at('Eladható')}</a></td></tr>
                <tr><td><a href="#" data-id="{$_termek.id}" class="js-keszletreszletezobutton">{at('Készlet')}: {$_termek.keszlet}</a></td></tr>
            </tbody>
        </table>
    </td>
</tr>