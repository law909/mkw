<tr id="mattable-row_{$_popup.id}" data-egyedid="{$_popup.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{if ($_popup.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_popup.kepurl}" target="_blank"><img src="{$mainurl}{$_popup.kepurlsmall}"/>
                        </a>{/if}</td>
                <td>
                    <a class="mattable-editlink" href="#" data-popupid="{$_popup.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_popup.nev}</a>
                    <a class="mattable-dellink" href="#" data-popupid="{$_popup.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
                    <div>{$_popup.headertext}</div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Id: {$_popup.id}
                    <a class="js-regenerateid" href="#" data-popupid="{$_popup.id}" title="{at('Regenerál')}"><span
                            class="ui-icon ui-icon-refresh"></span></a>
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        <div>{at('Sorrend')}: {$_popup.popuporder}</div>
        <div>{at('Megjelenés késleltetés')}: {$_popup.displaytime} mp</div>
        <div>{at('Megjelenés')}: {if ($_popup.triggerafterprevious)}az előző popup után{else}oldal betöltéskor{/if}</div>
    </td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td><a href="#" data-id="{$_popup.id}" data-flag="inaktiv" class="js-flagcheckbox{if ($_popup.inaktiv)} ui-state-hover{/if}">{at('Inaktív')}</a>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>