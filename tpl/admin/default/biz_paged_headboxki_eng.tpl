{* a biz_paged_headboxki.tpl kétnyelvű párja – lásd ott a float/blokkelem kiváltásának indoklását *}
<table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="50%" style="padding: 5px 5px 0 5px;">Szállító / Supplier</td>
        <td width="50%" style="padding: 5px 5px 0 5px;">Vevő / Customer</td>
    </tr>
    <tr>
        <td class="topalign" style="padding: 5px;">
            <span class="nev bold">{$egyed.tulajnev}</span><br />
            {$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}<br />
            EU adószám / EU tax number: {$egyed.tulajeuadoszam}<br />
            Bank: {$egyed.tulajbanknev}<br />
            Swift: {$egyed.tulajswift}<br />
            IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}<br />
            EORI NR: {$egyed.tulajeorinr}
        </td>
        <td class="topalign" style="padding: 5px;">
            <span class="nev bold">{$egyed.szamlanev}</span><br />
            {$egyed.szamlairszam} {$egyed.szamlavaros}<br />
            {$egyed.szamlautca} {$egyed.szamlahazszam}
            {if ($egyed.partnerorszag)}<br />{$egyed.partnerorszag}{/if}
            {if ($egyed.partneradoszam)}<br />Adószám / Tax number: {$egyed.partneradoszam}{/if}
            {if ($egyed.partnereuadoszam)}<br />EU adószám / EU tax number: {$egyed.partnereuadoszam}{/if}
        </td>
    </tr>
</table>
