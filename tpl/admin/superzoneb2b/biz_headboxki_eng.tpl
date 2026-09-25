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
        {if ($egyed.partnerorszag)}
            <p>{$egyed.partnerorszag}</p>
        {/if}
        {if ($egyed.partneradoszam)}
            <p>Adószám / Tax number: {$egyed.partneradoszam}</p>
        {/if}
        {if ($egyed.partnereuadoszam)}
            <p>EU adószám / EU tax number: {$egyed.partnereuadoszam}</p>
        {/if}
        {if ($showszallitasicim|default)}
            {if ($egyed.telephelynev || $egyed.telephelyirszam)}
                <p class="bold">Telephely / Site:</p>
                <p>{$egyed.telephelynev}</p>
                <p>{$egyed.telephelyirszam} {$egyed.telephelyvaros}, {$egyed.telephelyutca}{if ($egyed.telephelyorszag)}, {$egyed.telephelyorszag}{/if}</p>
            {/if}
            {if ($egyed.szallnev || $egyed.szallirszam)}
                <p class="bold">Szállítási cím / Delivery address:</p>
                <p>{$egyed.szallnev}</p>
                <p>{$egyed.szallirszam} {$egyed.szallvaros}, {$egyed.szallutca} {$egyed.szallhazszam}{if ($egyed.partnerszallorszag)}, {$egyed.partnerszallorszag}{/if}</p>
            {/if}
        {/if}
    </div>
</div>
