<tr id="mattable-row_{$_versenyzo.id}">
    <td class="cell">
        <a class="mattable-editlink" href="#" data-versenyzoid="{$_versenyzo.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_versenyzo.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-versenyzoid="{$_versenyzo.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_versenyzo.slug}</td>
    <td class="cell">{$_versenyzo.versenysorozat}</td>
    <td class="cell">{$_versenyzo.csapatnev}</td>
</tr>
