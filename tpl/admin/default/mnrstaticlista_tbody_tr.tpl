<tr id="mattable-row_{$_mnrstatic.id}" data-egyedid="{$_mnrstatic.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{if ($_mnrstatic.kepurl)}<a class="js-toflyout" href="{$mainurl}{$_mnrstatic.kepurl}" target="_blank"><img src="{$mainurl}{$_mnrstatic.kepurlsmall}"/></a>{/if}</td>
                    <td>
                        <a class="mattable-editlink" href="#" data-mnrstaticid="{$_mnrstatic.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_mnrstatic.nev}</a>
                        <a class="mattable-dellink" href="#" data-mnrstaticid="{$_mnrstatic.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>