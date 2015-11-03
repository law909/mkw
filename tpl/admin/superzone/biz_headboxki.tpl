<div class="halfwidth pull-left">
    <div class="headboxinner">
        <p class="bottommargin">Szállító</p>
        <p class="nev bold">{$egyed.tulajnev}</p>
        <p>{$egyed.tulajirszam} {$egyed.tulajvaros}, {$egyed.tulajutca}</p>
        <p>Adószám: {$egyed.tulajadoszam}</p>
        <p>Bank: {$egyed.tulajbanknev}</p>
        <p>Swift: {$egyed.tulajswift}</p>
        <p>IBAN: {$egyed.tulajiban} {$egyed.tulajbankszamlaszam}</p>
    </div>
</div>
<div class="halfwidth pull-left">
    <div class="headboxinner">
        <p class="bottommargin">Vevő</p>
        <p class="nev bold">{$egyed.szamlanev}</p>
        <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
        <p>{$egyed.szamlautca}</p>
        {if ($egyed.partneradoszam)}
            <p>Adószám: {$egyed.partneradoszam}</p>
        {/if}
        {if ($egyed.partnereuadoszam)}
            <p>EU adószám: {$egyed.partnereuadoszam}</p>
        {/if}
    </div>
</div>
