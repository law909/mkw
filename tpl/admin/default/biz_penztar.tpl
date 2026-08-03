{extends "biz_base.tpl"}

{block "inhead"}
    <style type="text/css">
        .iranycimke { font-size: 14px; font-weight: bold;}
        .rontottjelzo { color: red;}
        .alairassor { width: 33%; float: left; padding-top: 40px;}
        .alairasvonal { border-top: solid 1px black; margin: 0 10px;}
        .alairascimke { font-size: 10px; text-align: center; padding-top: 3px;}
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
        <div class="row pull-left row-inner">
            <span class="iranycimke">
                {if ($egyed.irany > 0)}Bevételi pénztárbizonylat{else}Kiadási pénztárbizonylat{/if}
            </span>
        </div>
        <div class="headbox pull-left">
            <div class="headboxborder border">
                <div class="headboxinner">
                    <p class="bold">Kiállító:</p>
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
                    <p class="bold">{if ($egyed.irany > 0)}Befizető:{else}Átvevő:{/if}</p>
                    <p class="nev bold">{$egyed.partnernev}</p>
                    <p>{$egyed.partnerirszam} {$egyed.partnervaros}</p>
                    <p>{$egyed.partnerutca} {$egyed.partnerhazszam}</p>
                    {if ($egyed.partneradoszam)}
                        <p>Adószám: {$egyed.partneradoszam}</p>
                    {/if}
                    {if ($egyed.partnereuadoszam)}
                        <p>EU adószám: {$egyed.partnereuadoszam}</p>
                    {/if}
                </div>
            </div>
        </div>
        <div class="row pull-left row-inner">
            <p class="head2label pull-left">Pénztár: {$egyed.penztarnev|default:"&nbsp;"}</p>
            <p class="head2label pull-left">Kelt: {$egyed.keltstr|default:"&nbsp;"}</p>
            <p class="head2label pull-left">Valutanem: {$egyed.valutanemnev|default:"&nbsp;"}</p>
            {if ($egyed.erbizonylatszam)}
                <p class="head2label pull-left">Er.biz.szám: {$egyed.erbizonylatszam}</p>
            {/if}
        </div>
        {if ($egyed.megjegyzes|default)}
            <div class="row pull-left">
                <div class="border">
                    <div class="row-inner">
                        Közlemény: {$egyed.megjegyzes}
                    </div>
                </div>
            </div>
        {/if}
        <table class="teteltable pull-left">
            <thead>
            <th>Jogcím</th>
            <th>Szöveg</th>
            <th>Hivatkozott bizonylat</th>
            <th class="textalignright">Esedékesség</th>
            <th class="textalignright">Összeg</th>
            </thead>
            <tbody>
            {foreach $egyed.tetellista as $tetel}
                <tr class="tetelsor">
                    <td>{$tetel.jogcimnev}</td>
                    <td>{$tetel.szoveg}</td>
                    <td>{$tetel.hivatkozottbizonylat}</td>
                    <td class="textalignright">{$tetel.hivatkozottdatumstr}</td>
                    <td class="textalignright">{number_format($tetel.brutto,0,'',' ')}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="lablec pull-left">
        <table class="osszesitotable pull-right">
            <tbody>
            <tr>
                <td class="bold">{if ($egyed.irany > 0)}Befizetve:{else}Kifizetve:{/if}</td>
                <td class="textalignright bold">{number_format($egyed.brutto,0,'',' ')} {$egyed.valutanemnev}</td>
            </tr>
            <tr>
                <td colspan="2" class="fizetendokiirva">azaz {$egyed.bruttokiirva}</td>
            </tr>
            </tbody>
        </table>
        <div class="row pull-left">
            <div class="alairassor">
                <div class="alairasvonal"></div>
                <div class="alairascimke">Pénztáros</div>
            </div>
            <div class="alairassor">
                <div class="alairasvonal"></div>
                <div class="alairascimke">{if ($egyed.irany > 0)}Befizető{else}Átvevő{/if}</div>
            </div>
            <div class="alairassor">
                <div class="alairasvonal"></div>
                <div class="alairascimke">Utalványozó</div>
            </div>
        </div>
    </div>
{/block}
