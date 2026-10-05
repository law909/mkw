<tr id="mattable-row_{$_egyed.id|escape:'html':'UTF-8':false}" data-egyedid="{$_egyed.id|escape:'html':'UTF-8':false}"{if ($_egyed.rontott)} class="rontott"{/if}>
    <td class="cell">
        <strong>{$_egyed.id|escape:'html':'UTF-8':false}</strong>
        <a class="js-pdfbizonylat" href="/admin/konyvelo/pdf?id={$_egyed.id|escape:'url'}"
           title="{at('PDF')}" target="_blank"><span class="ui-icon ui-icon-print"></span></a>
        {if ($_egyed.rontott)}<div class="redtext">{at('rontott')}</div>{/if}
        {if ($_egyed.storno)}<div class="redtext">{at('stornó')}</div>{/if}
        {if ($_egyed.stornozott)}<div class="redtext">{at('stornózott')}</div>{/if}
        <div>{$_egyed.fizmodnev|escape:'html':'UTF-8':false}</div>
    </td>
    <td class="cell">
        {$_egyed.partnernev|escape:'html':'UTF-8':false}
        {if ($_egyed.partneradoszam)}<div>{at('Adószám')}: {$_egyed.partneradoszam|escape:'html':'UTF-8':false}</div>{/if}
    </td>
    <td class="cell">
        <div>{at('Kelt')}: {$_egyed.keltstr|escape:'html':'UTF-8':false}</div>
        <div>{at('Teljesítés')}: {$_egyed.teljesitesstr|escape:'html':'UTF-8':false}</div>
        <div>{at('Esedékesség')}: {$_egyed.esedekessegstr|escape:'html':'UTF-8':false}</div>
    </td>
    <td class="cell textalignright">
        <div>{at('Nettó')}: {bizformat($_egyed.netto)} {$_egyed.valutanemnev|escape:'html':'UTF-8':false}</div>
        <div>{at('ÁFA')}: {bizformat($_egyed.afa)} {$_egyed.valutanemnev|escape:'html':'UTF-8':false}</div>
        <div class="bold">{at('Bruttó')}: {bizformat($_egyed.brutto)} {$_egyed.valutanemnev|escape:'html':'UTF-8':false}</div>
        {if ($_egyed.valutanemnev != 'HUF')}
            <div>{bizformat($_egyed.bruttohuf)} HUF</div>
        {/if}
    </td>
    <td class="cell">
        {foreach $_egyed.penzmozgasok as $_pm}
            <div>
                <a href="{$_pm.printurl|escape:'html':'UTF-8':false}" target="_blank" title="{at('Nyomtat')}">{$_pm.id|escape:'html':'UTF-8':false}</a>
                {$_pm.tipus|escape:'html':'UTF-8':false} · {$_pm.keltstr|escape:'html':'UTF-8':false} · {bizformat($_pm.brutto)}
            </div>
        {foreachelse}
            <span class="bizlista-halvany">{at('nincs')}</span>
        {/foreach}
    </td>
</tr>
