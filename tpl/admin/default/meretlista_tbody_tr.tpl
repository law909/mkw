<tr id="mattable-row_{$_meret.id}" data-egyedid="{$_meret.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{if ($_meret.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_meret.kepurl}" target="_blank"><img
                            src="{$mainurl}{$_meret.kepurlsmall}"/></a>{/if}</td>
                <td>
                    <a class="mattable-editlink" href="#" data-szinid="{$_meret.id}" data-oper="edit"
                       title="{at('Szerkeszt')}">{$_meret.nev}</a>
                    <a class="mattable-dellink" href="#" data-szinid="{$_meret.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_meret.sorrend}
    </td>
</tr>