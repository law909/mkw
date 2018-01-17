<div class="headbox pull-left">
    <div class="headboxborder border">
        <div class="headboxinner">
            <p class="bold">Szállító:</p>
            <p class="nev bold">{$egyed.tulajnev}</p>
            <p>{$egyed.tulajirszam} {$egyed.tulajvaros}</p>
            <p>{$egyed.tulajutca}</p>
            <p>Adószám: {$egyed.tulajadoszam}</p>
            <p>Bankszámla: {$egyed.tulajbankszamlaszam}</p>
        </div>
    </div>
</div>
<div class="headbox pull-left">
    <div class="headboxborder border">
        <div class="headboxinner">
            <p class="bold">Vevő:</p>
            <p class="nev bold">{$egyed.szamlanev}</p>
            <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
            <p>{$egyed.szamlautca} {$egyed.szamlahazszam}</p>
            {if ($egyed.partneradoszam)}
                <p>Adószám: {$egyed.partneradoszam}</p>
            {/if}
            {if ($egyed.partnereuadoszam)}
                <p>EU adószám: {$egyed.partnereuadoszam}</p>
            {/if}
        </div>
    </div>
</div>
