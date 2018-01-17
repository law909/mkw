<div class="halfwidth pull-left">
    <div class="headboxinner">
        <p class="bottommargin">Szállító / Supplier</p>
        <p class="nev bold">{$egyed.tulajnev}</p>
        <p>{$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}</p>
        <p>EU adószám / EU tax number: {$egyed.tulajeuadoszam}</p>
        <p>Bank: {$egyed.tulajbanknev}</p>
        <p>Swift: {$egyed.tulajswift}</p>
        <p>IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}</p>
        <p>EORI NR: {$egyed.tulajeorinr}</p>
    </div>
</div>
<div class="halfwidth pull-left">
    <div class="headboxinner">
        <p class="bottommargin">Vevő / Customer</p>
        <p class="nev bold">{$egyed.szamlanev}</p>
        <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
        <p>{$egyed.szamlautca} {$egyed.szamlahazszam}</p>
        {if ($egyed.partneradoszam)}
            <p>Adószám / Tax number: {$egyed.partneradoszam}</p>
        {/if}
        {if ($egyed.partnereuadoszam)}
            <p>EU adószám / EU tax number: {$egyed.partnereuadoszam}</p>
        {/if}
    </div>
</div>
