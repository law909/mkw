<tr id="mattable-row_{$_csapat.id}">
    <td class="cell">
        <a class="mattable-editlink" href="#" data-csapatid="{$_csapat.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_csapat.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-csapatid="{$_csapat.id}" data-oper="del" title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a></span>
    </td>
    <td class="cell">{$_csapat.slug}</td>
    <td class="cell">{if ($_csapat.logourlmini)}<a class="js-toflyout" href="{$mainurl}{$_csapat.logourl}" target="_blank"><img
                src="{$mainurl}{$_csapat.logourlmini}"></a>{/if}</td>
    <td class="cell">{if ($_csapat.kepurlmini)}<a class="js-toflyout" href="{$mainurl}{$_csapat.kepurl}" target="_blank"><img
                src="{$mainurl}{$_csapat.kepurlmini}"></a>{/if}</td>
</tr>
