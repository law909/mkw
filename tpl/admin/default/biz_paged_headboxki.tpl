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
            <span class="nev bold">{$egyed.tulajnev}{if ($egyed.tulajegyenivallalkozo)} ({$egyed.tulajevnyilvszam}){/if}</span><br/>
            {if ($egyed.tulajkisadozo)}
                Kisadózó
                <br/>
            {/if}
            {$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}<br/>
            Adószám: {$egyed.tulajadoszam}<br/>
            {if ($egyed.tulajeuadoszam)}
                <p>EU adószám: {$egyed.tulajeuadoszam}</p>
            {/if}
            {if ($setup.theme === 'superzoneb2b')}
                Bank: {$egyed.tulajbanknev}
                <br/>
                Swift: {$egyed.tulajswift}
                <br/>
                IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}
            {else}
                Bankszámla: {$egyed.tulajbankszamlaszam}
            {/if}
        </td>
        <td class="topalign" style="padding: 5px;">
            <span class="nev bold">{$egyed.szamlanev}</span><br/>
            {$egyed.szamlairszam} {$egyed.szamlavaros}<br/>
            {$egyed.szamlautca} {$egyed.szamlahazszam}
            {if ($egyed.partnerorszag)}<br/>{$egyed.partnerorszag}{/if}
            {if ($egyed.partneradoszam)}<br/>Adószám: {$egyed.partneradoszam}{/if}
            {if ($egyed.partnereuadoszam)}<br/>EU adószám: {$egyed.partnereuadoszam}{/if}
            {if ($egyed.partnerszamlaegyeb)}
                <br/>
                {$egyed.partnerszamlaegyeb}
            {/if}
        </td>
    </tr>
</table>
