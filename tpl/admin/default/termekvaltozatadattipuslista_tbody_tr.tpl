<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}">
    <td class="cell"><input class="maincheckbox" type="checkbox" autocomplete="off"></td>
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-egyedid="{$_egyed.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_egyed.nev}</a>
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-egyedid="{$_egyed.id}" data-oper="del" title="{at('Töröl')}"><span
                        class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
    </td>
</tr>
