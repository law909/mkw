{extends "../rep_base.tpl"}

{block "inhead"}
    <style>
        .jelenletimatrix th, .jelenletimatrix td {
            padding: 2px 4px;
        }

        .jelenletimatrix .jel {
            text-align: center;
        }

        .jelenletimatrix .dolgozofej {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
        }
    </style>
{/block}

{block "body"}
    <h4>Jelenléti ív</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <table class="jelenletimatrix">
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Nap</th>
            {foreach $dolgozok as $_dolgozonev}
                <th class="dolgozofej">{$_dolgozonev}</th>
            {/foreach}
            <th class="textalignright">Összesen</th>
        </tr>
        </thead>
        <tbody>
        {foreach $napok as $_nap}
            <tr>
                <td>{$_nap.datum}</td>
                <td>{$_nap.napnev}</td>
                {foreach $_nap.jelek as $_jel}
                    <td class="jel">{$_jel}</td>
                {/foreach}
                <td class="textalignright">{$_nap.db}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}
