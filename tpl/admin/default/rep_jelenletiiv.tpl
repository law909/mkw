{extends "../rep_base.tpl"}

{block "inhead"}
    <style>
        .jelenletiiv-alairas {
            width: 25%;
        }

        .jelenletiiv-alairassor {
            margin-top: 40px;
        }

        .jelenletiiv-alairassor td {
            border-bottom: none;
            border-top: 1px solid #000;
            text-align: center;
        }
    </style>
{/block}

{block "body"}
    {foreach $ivek as $_iv}
        <div{if (!$_iv@first)} class="pagebreakbefore"{/if}>
            <h4>Jelenléti ív</h4>
            <h5>{$_iv.dolgozonev}{if ($_iv.munkakornev)} ({$_iv.munkakornev}){/if}</h5>
            <h5>{$tolstr} - {$igstr}{if ($_iv.munkaido)} &nbsp;&nbsp; Munkaidő: {$_iv.munkaido}{/if}</h5>
            <table style="width:100%;">
                <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Nap</th>
                    <th>Munkakezdés</th>
                    <th>Munka vége</th>
                    <th class="textalignright">Óra</th>
                    <th>Távollét</th>
                    <th class="jelenletiiv-alairas">Aláírás</th>
                </tr>
                </thead>
                <tbody>
                {foreach $_iv.napok as $_nap}
                    <tr>
                        <td>{$_nap.datum}</td>
                        <td>{$_nap.napnev}</td>
                        <td>{$_nap.kezdes}</td>
                        <td>{$_nap.vege}</td>
                        <td class="textalignright">{$_nap.orastr}</td>
                        <td class="redtext">{$_nap.tavollet}</td>
                        <td class="jelenletiiv-alairas">&nbsp;</td>
                    </tr>
                {/foreach}
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="7">Munkanap: {$_iv.napok|@count} &nbsp;&nbsp; Ledolgozott: {$_iv.ledolgozott} &nbsp;&nbsp;
                        Távollét: {$_iv.tavollet} &nbsp;&nbsp; Ledolgozott óra: {$_iv.oraosszesenstr}
                    </th>
                </tr>
                </tfoot>
            </table>
            <table class="jelenletiiv-alairassor" style="width:100%;">
                <tr>
                    <td style="width:40%;">dolgozó aláírása</td>
                    <td style="width:20%;border:none;">&nbsp;</td>
                    <td style="width:40%;">munkáltató aláírása</td>
                </tr>
            </table>
        </div>
    {/foreach}
{/block}
