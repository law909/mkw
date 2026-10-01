{extends "../rep_base.tpl"}

{block "inhead"}
    <style>
        .szabadsag-egyenleg td {
            border: none;
        }

        .szabadsag-dolgozo {
            margin-bottom: 25px;
        }
    </style>
{/block}

{block "body"}
    <h4>Szabadság kimutatás</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    {foreach $sorok as $_sor}
        <div class="szabadsag-dolgozo">
            <h5>{$_sor.dolgozonev}{if ($_sor.munkakornev)} ({$_sor.munkakornev}){/if}</h5>
            <table style="width:100%;">
                <thead>
                <tr>
                    <th>Ettől</th>
                    <th>Eddig</th>
                    <th class="textalignright">Nap</th>
                    <th>Megjegyzés</th>
                </tr>
                </thead>
                <tbody>
                {foreach $_sor.szabadsagok as $_t}
                    <tr>
                        <td>{$_t.datumtol}</td>
                        <td>{$_t.datumig}</td>
                        <td class="textalignright">{$_t.napok}</td>
                        <td>{$_t.megjegyzes}</td>
                    </tr>
                {foreachelse}
                    <tr>
                        <td colspan="4">Ebben az időszakban nem volt szabadságon.</td>
                    </tr>
                {/foreach}
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="2">Az időszakban kivett szabadság</th>
                    <th class="textalignright">{$_sor.idoszakiszabadsag}</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
            <table class="szabadsag-egyenleg">
                <tr>
                    <td>{$ev}. évi keret:</td>
                    <td class="textalignright">{$_sor.evesmax} nap</td>
                    <td>{$ev}-ben kivett szabadság:</td>
                    <td class="textalignright">{$_sor.evbenkivett} nap</td>
                    <td>Maradt:</td>
                    <td class="textalignright{if ($_sor.marad < 0)} red{/if}">{$_sor.marad} nap</td>
                </tr>
            </table>
        </div>
    {/foreach}
{/block}
