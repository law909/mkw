<tr id="mattable-row_{$_orarend.id}" data-egyedid="{$_orarend.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>
                    <a class="mattable-editlink" href="#" data-orarendid="{$_orarend.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_orarend.nev}</a>
                    <a class="mattable-dellink" href="#" data-orarendid="{$_orarend.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
                    <table>
                        <tbody>
                        <tr>
                            <td>{$_orarend.napnev}</td>
                        </tr>
                        <tr>
                            <td>{$_orarend.dolgozonev}</td>
                        </tr>
                        <tr>
                            <td>{$_orarend.jogateremnev}</td>
                        </tr>
                        <tr>
                            <td>{$_orarend.kezdet} - {$_orarend.veg}</td>
                        </tr>
                        <tr>
                            <td>Max.férőhely: {$_orarend.maxferohely}</td>
                        </tr>
                        <tr>
                            <td>Min.bejelentkezés: {$_orarend.minbejelentkezes}</td>
                        </tr>
                        <tr>
                            <td>Jutalék: {$_orarend.jutalekszazalek} %</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_orarend.id}" data-flag="inaktiv"
                       class="js-flagcheckbox{if ($_orarend.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_orarend.id}" data-flag="multilang"
                       class="js-flagcheckbox{if ($_orarend.multilang)} ui-state-hover{/if}">{at('Több nyelvű')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_orarend.id}" data-flag="bejelentkezeskell"
                       class="js-flagcheckbox{if ($_orarend.bejelentkezeskell)} ui-state-hover{/if}">{at('Bejelentkezés kell')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_orarend.id}" data-flag="lemondhato"
                       class="js-flagcheckbox{if ($_orarend.lemondhato)} ui-state-hover{/if}">{at('Lemondható')}</a></td>
            </tr>
            <tr>
                <td><a href="#" data-id="{$_orarend.id}" data-flag="orarendbennincs"
                       class="js-flagcheckbox{if ($_orarend.orarendbennincs)} ui-state-hover{/if}">{at('Órarendben nem látszik')}</a></td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>