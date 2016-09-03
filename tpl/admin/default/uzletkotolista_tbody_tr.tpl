<tr id="mattable-row_{$_uzletkoto.id}">
    <td class="cell">
        <a class="mattable-editlink" href="#" data-uzletkotoid="{$_uzletkoto.id}" data-oper="edit" title="{t('Szerkeszt')}">{$_uzletkoto.nev}</a>
        <span class="jobbra"><a class="mattable-dellink" href="#" data-uzletkotoid="{$_uzletkoto.id}" data-oper="del" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></span>
        {if ($_uzletkoto.fo)}<div>Vezető</div>{/if}
        {if ($_uzletkoto.belso)}<div>Belső</div>{/if}
        {if ($_uzletkoto.fouzletkotonev)}<div>Vezető üzletkötője: {$_uzletkoto.fouzletkotonev}</div>{/if}
    </td>
    <td class="cell">{$_uzletkoto.cim}</td>
    <td class="cell">
        <table>
            <tbody>
                <tr><td>{$_uzletkoto.telefon}</td></tr>
                <tr><td>{$_uzletkoto.mobil}</td></tr>
                {if ($_uzletkoto.fax!=='')}<tr><td>{t('Fax')}: {$_uzletkoto.fax}</td></tr>{/if}
                {if ($_uzletkoto.email!=='')}<tr><td><a href="mailto:{$_uzletkoto.email}" title="{t('Levélküldés')}">{$_uzletkoto.email}</a></td></tr>{/if}
                {if ($_uzletkoto.honlap!=='')}<tr><td><a href="{$_uzletkoto.honlap}" title="{t('Ugrás a honlapra')}" target="_blank">{$_uzletkoto.honlap}</a></td></tr>{/if}
            </tbody>
        </table>
    </td>
</tr>