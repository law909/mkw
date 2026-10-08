{extends "biz_base.tpl"}

{block "inhead"}
    <style type="text/css">
        .rontottjelzo { color: red;}
    </style>
{/block}

{block "body"}
    <div class="teto">
        <div>
            <div class="biznev">
                {if ($teszt|default)}<span style="color:red">TESZT MÓD</span> {/if}{$egyed.bizonylatnev}
                {if ($egyed.rontott)}<span class="rontottjelzo">(rontott)</span>{/if}
            </div>
            <div class="bizszam textalignright">{$egyed.id}</div>
        </div>
        <div class="headbox pull-left">
            <div class="headboxborder border">
                <div class="headboxinner">
                    <p class="bold">Számlatulajdonos:</p>
                    <p class="nev bold">{$egyed.tulajnev}</p>
                    <p>{$egyed.tulajirszam} {$egyed.tulajvaros}</p>
                    <p>{$egyed.tulajutca}</p>
                    <p>Adószám: {$egyed.tulajadoszam}</p>
                </div>
            </div>
        </div>
        <div class="headbox pull-left">
            <div class="headboxborder border">
                <div class="headboxinner">
                    <p class="bold">Bankszámla:</p>
                    <p class="nev bold">{$egyed.tulajbanknev}</p>
                    <p>{$egyed.bankszamlaszam}</p>
                    {if ($egyed.iban)}<p>IBAN: {$egyed.iban}</p>{/if}
                    {if ($egyed.swift)}<p>SWIFT: {$egyed.swift}</p>{/if}
                </div>
            </div>
        </div>
        <div class="row pull-left row-inner">
            <p class="head2label pull-left">Kelt: {$egyed.keltstr|default:"&nbsp;"}</p>
            <p class="head2label pull-left">Valutanem: {$egyed.valutanemnev|default:"&nbsp;"}</p>
            {if ($egyed.erbizonylatszam)}
                <p class="head2label pull-left">Er.biz.szám: {$egyed.erbizonylatszam}</p>
            {/if}
        </div>
        {if ($egyed.megjegyzes|default && ($egyed.megjegyzesnyomtatasban|default:true))}
            <div class="row pull-left">
                <div class="border">
                    <div class="row-inner">
                        Megjegyzés: {$egyed.megjegyzes}
                    </div>
                </div>
            </div>
        {/if}
        <table class="teteltable pull-left">
            <thead>
            <th>Dátum</th>
            <th>Partner</th>
            <th>Jogcím</th>
            <th>Hivatkozott bizonylat</th>
            <th class="textalignright">Esedékesség</th>
            <th class="textalignright">Jóváírás</th>
            <th class="textalignright">Terhelés</th>
            </thead>
            <tbody>
            {foreach $egyed.tetellista as $tetel}
                <tr class="tetelsor">
                    <td>{$tetel.datumstr}</td>
                    <td>{$tetel.partnernev}</td>
                    <td>{$tetel.jogcimnev}</td>
                    <td>{$tetel.hivatkozottbizonylat}{if ($tetel.rontott)} <span class="rontottjelzo">(rontott)</span>{/if}</td>
                    <td class="textalignright">{$tetel.hivatkozottdatumstr}</td>
                    <td class="textalignright">{if ($tetel.irany > 0)}{number_format($tetel.brutto,0,'',' ')}{/if}</td>
                    <td class="textalignright">{if ($tetel.irany < 0)}{number_format($tetel.brutto,0,'',' ')}{/if}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="lablec pull-left">
        <table class="osszesitotable pull-right">
            <tbody>
            <tr>
                <td class="bold">Összesen:</td>
                <td class="textalignright bold">{number_format($egyed.brutto,0,'',' ')} {$egyed.valutanemnev}</td>
            </tr>
            </tbody>
        </table>
    </div>
{/block}
