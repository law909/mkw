<tr id="mattable-row_{$_egyed.id}" data-egyedid="{$_egyed.id}"{if ($_egyed.rontott)} class="rontott"{/if}>
    <td class="cell">
        <strong>{$_egyed.id}</strong>
        <a class="js-pdfbizonylat" href="/admin/konyvelo/pdf?id={$_egyed.id|escape:'url'}"
           title="{at('PDF')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
        {if ($_egyed.rontott)}<div class="redtext">{at('rontott')}</div>{/if}
        {if ($_egyed.storno)}<div class="redtext">{at('stornó')}</div>{/if}
        {if ($_egyed.stornozott)}<div class="redtext">{at('stornózott')}</div>{/if}
        <div>{$_egyed.fizmodnev}</div>
    </td>
    <td class="cell">
        {$_egyed.partnernev}
        {if ($_egyed.partneradoszam)}<div>{at('Adószám')}: {$_egyed.partneradoszam}</div>{/if}
    </td>
    <td class="cell">
        <div>{at('Kelt')}: {$_egyed.keltstr}</div>
        <div>{at('Teljesítés')}: {$_egyed.teljesitesstr}</div>
        <div>{at('Esedékesség')}: {$_egyed.esedekessegstr}</div>
    </td>
    <td class="cell textalignright">
        <div>{at('Nettó')}: {bizformat($_egyed.netto)} {$_egyed.valutanemnev}</div>
        <div>{at('ÁFA')}: {bizformat($_egyed.afa)} {$_egyed.valutanemnev}</div>
        <div class="bold">{at('Bruttó')}: {bizformat($_egyed.brutto)} {$_egyed.valutanemnev}</div>
        {if ($_egyed.valutanemnev != 'HUF')}
            <div>{bizformat($_egyed.bruttohuf)} HUF</div>
        {/if}
    </td>
    <td class="cell">
        {foreach $_egyed.penzmozgasok as $_pm}
            <div>
                <a href="{$_pm.printurl}" target="_blank" title="{at('Nyomtat')}">{$_pm.id}</a>
                {$_pm.tipus} · {$_pm.keltstr} · {bizformat($_pm.brutto)}
            </div>
        {foreachelse}
            <span class="bizlista-halvany">{at('nincs')}</span>
        {/foreach}
    </td>
</tr>
