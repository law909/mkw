<div class="headbox pull-left">
    <div class="height100percent border">
        <div class="padding5">
            <p class="bold">Szállító:</p>
            <p class="nev bold">{$egyed.tulajnev}{if ($egyed.tulajegyenivallalkozo)} ({$egyed.tulajevnyilvszam}){/if}</p>
            {if ($egyed.tulajkisadozo)}
                <p>Kisadózó</p>
            {/if}
            <p>{$egyed.tulajirszam} {$egyed.tulajvaros}</p>
            <p>{$egyed.tulajutca}</p>
            <p>Adószám: {$egyed.tulajadoszam}</p>
            {if ($egyed.tulajeuadoszam)}
                <p>EU adószám: {$egyed.tulajeuadoszam}</p>
            {/if}
            <p>Bankszámla: {$egyed.tulajbankszamlaszam}</p>
        </div>
    </div>
</div>
<div class="headbox pull-left">
    <div class="height100percent border">
        <div class="padding5">
            <p class="bold">Vevő:</p>
            <p class="nev bold">{$egyed.szamlanev}</p>
            <p>{$egyed.szamlairszam} {$egyed.szamlavaros}</p>
            <p>{$egyed.szamlautca} {$egyed.szamlahazszam}</p>
            {if ($egyed.partneradoszam)}
                <p>Adószám: {$egyed.partneradoszam}</p>
            {/if}
            {if ($egyed.partnereuadoszam)}
                <p>EU adószám (EU Vat No): {$egyed.partnereuadoszam}</p>
            {/if}
            {if ($egyed.partnerszamlaegyeb)}
                <p>{$egyed.partnerszamlaegyeb}</p>
            {/if}
        </div>
    </div>
</div>
