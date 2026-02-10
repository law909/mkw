<tr id="mattable-row_{$_szin.id}" data-egyedid="{$_szin.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
            <tr>
                <td>{if ($_szin.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_szin.kepurl}" target="_blank"><img
                            src="{$mainurl}{$_szin.kepurlsmall}"/></a>{/if}</td>
                <td>
                    <a class="mattable-editlink" href="#" data-szinid="{$_szin.id}" data-oper="edit"
                       title="{at('Szerkeszt')}">{$_szin.nev}</a>
                    <a class="mattable-dellink" href="#" data-szinid="{$_szin.id}" data-oper="del" title="{at('Töröl')}"><span
                            class="ui-icon ui-icon-circle-minus"></span></a>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="cell">
        {$_szin.sorrend}
    </td>
</tr>