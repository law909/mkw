{*
    Szállító / Vevő doboz a lapozott sablonokhoz. Float helyett kétcellás tábla, <p> helyett <br>:
    az mPDF a táblacellába tett blokkelem box-CSS-ét (margó, padding, keret) eldobja, a <td> sajátját
    viszont megtartja – ezért van a felirat alatti köz a második sor <td>-jének paddingjében.
*}
<table class="fullwidth" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="50%" style="padding: 5px 5px 0 5px;">Szállító</td>
        <td width="50%" style="padding: 5px 5px 0 5px;">Vevő</td>
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
            {$egyed.szamlautca} {$egyed.szamlahazszam}
            {if ($egyed.partnerorszag)}<br />{$egyed.partnerorszag}{/if}
            {if ($egyed.partneradoszam)}<br />Adószám: {$egyed.partneradoszam}{/if}
            {if ($egyed.partnereuadoszam)}<br />EU adószám: {$egyed.partnereuadoszam}{/if}
        </td>
    </tr>
</table>
