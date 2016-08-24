{extends "../rep_base.tpl"}

{block "body"}
    <h2>Vásárlási utalvány</h2>
    <h2>{bizformat($egyed.osszeg)} Forint értékben</h2>
    <div>Azonosító: {$egyed.id}</div>
    {if ($egyed.ervenyes)}
        <div>Felhasználás dátuma:</div>
        <div>Felhasználó neve:</div>
        <div>Felhasználó aláírása:</div>
    {else}
        <div>Érvénytelen</div>
    {/if}
{/block}