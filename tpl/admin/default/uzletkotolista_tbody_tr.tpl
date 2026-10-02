<tr id="mattable-row_{$_uzletkoto.id}">
    <td class="cell bizlista-fo">
        <div class="bizlista-fej">
            <a class="mattable-editlink bizlista-nev" href="#" data-uzletkotoid="{$_uzletkoto.id}" data-oper="edit" title="{at('Szerkeszt')}">{$_uzletkoto.nev}</a>
            {if ($_uzletkoto.fo)}
                <span class="bizlista-jelveny">{at('Vezető')}</span>
            {/if}
            {if ($_uzletkoto.belso)}
                <span class="bizlista-jelveny">{at('Belső')}</span>
            {/if}
        </div>
        <div class="bizlista-muveletek">
            <a class="mattable-dellink" href="#" data-uzletkotoid="{$_uzletkoto.id}" data-oper="del" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </div>
        {* az üres értékű sor rejtve (style.css): a dd-nek üresnek kell maradnia, ezért strip *}
        {strip}
        <dl class="bizlista-adatok bizlista-meta">
            <div class="bizlista-sor"><dt>{at('Vezető üzletkötője')}:</dt><dd>{$_uzletkoto.fouzletkotonev}</dd></div>
        </dl>
        {/strip}
    </td>
    <td class="cell">
        <div class="bizlista-partner">
            {if ($_uzletkoto.cim)}
                <div><span class="bizlista-cimke">{at('Cím')}:</span> {$_uzletkoto.cim}</div>
            {/if}
            {if ($_uzletkoto.telefon)}
                <div>{$_uzletkoto.telefon}</div>
            {/if}
            {if ($_uzletkoto.mobil)}
                <div>{$_uzletkoto.mobil}</div>
            {/if}
            {if ($_uzletkoto.fax!=='')}
                <div><span class="bizlista-cimke">{at('Fax')}:</span> {$_uzletkoto.fax}</div>
            {/if}
            {if ($_uzletkoto.email!=='')}
                <div><a href="mailto:{$_uzletkoto.email}" title="{at('Levélküldés')}">{$_uzletkoto.email}</a></div>
            {/if}
            {if ($_uzletkoto.honlap!=='')}
                <div><a href="{$_uzletkoto.honlap}" title="{at('Ugrás a honlapra')}" target="_blank">{$_uzletkoto.honlap}</a></div>
            {/if}
        </div>
    </td>
</tr>
