<tr id="mattable-row_{$_mnrlanding.id}" data-egyedid="{$_mnrlanding.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{if ($_mnrlanding.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_mnrlanding.kepurl}" target="_blank"><img src="{$mainurl}{$_mnrlanding.kepurlsmall}"/></a>{/if}</td>
                    <td>
                        <a class="mattable-editlink" href="#" data-mnrlandingid="{$_mnrlanding.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_mnrlanding.nev}</a>
                        <a class="mattable-dellink" href="#" data-mnrlandingid="{$_mnrlanding.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>