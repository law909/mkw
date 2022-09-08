<tr id="mattable-row_{$_mnrnavigation.id}" data-egyedid="{$_mnrnavigation.id}">
    <td class="cell"><input class="js-egyedcheckbox" type="checkbox"></td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td></td>
                    <td>
                        <a class="mattable-editlink" href="#" data-mnrnavigationid="{$_mnrnavigation.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_mnrnavigation.nev}</a>
                        <a class="mattable-dellink" href="#" data-mnrnavigationid="{$_mnrnavigation.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>