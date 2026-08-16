{*
    Bejövő irányú bizonylatok fejléc-doboza: a tulajdonos a Vevő, a partner a Szállító.
    A float/blokkelem kiváltásának indoklása a biz_paged_headboxki.tpl-ben.
*}
<table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="50%" style="padding: 5px 5px 0 5px;">Vevő</td>
        <td width="50%" style="padding: 5px 5px 0 5px;">Szállító</td>
    </tr>
    <tr>
        <td class="topalign" style="padding: 5px;">
            <span class="nev bold">{$egyed.tulajnev}</span><br />
            {$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}<br />
            Adószám: {$egyed.tulajadoszam}<br />
            Bank: {$egyed.tulajbanknev}<br />
            Swift: {$egyed.tulajswift}<br />
            IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}
        </td>
        <td class="topalign" style="padding: 5px;">
            <span class="nev bold">{$egyed.szamlanev}</span><br />
            {$egyed.szamlairszam} {$egyed.szamlavaros}<br />
            {$egyed.szamlautca}
            {if ($egyed.partneradoszam)}<br />Adószám: {$egyed.partneradoszam}{/if}
            {if ($egyed.partnereuadoszam)}<br />EU adószám: {$egyed.partnereuadoszam}{/if}
        </td>
    </tr>
</table>
