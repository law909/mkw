{extends "basestone.tpl"}

{block "script"}
    <!--script src="/js/main/darshan/rendezvenyreg.js"></script-->
{/block}

{block "stonebody"}
    <div class="container">
        <div class="row">
            <div class="col">
                {if (!$lemondas)}
                    <h4 class="color-darshan">SIKERES JELENTKEZÉS</h4>
                {else}
                    <h4 class="color-darshan">SIKERES LEMONDÁS</h4>
                {/if}
            </div>
        </div>
        <div class="row">
            <div class="col">
                {if (!$lemondas)}
                    Küldtünk neked egy emailt a részletekkel.
                    {if ($jelentkezes.rendezvenyar > 0)}
                        Nézd meg az email fiókod és utald el nekünk a részvételi díjat, hogy le tudjuk foglalni a helyed!
                    {/if}
                {else}
                    Sajnáljuk, hogy lemondtad a részvételedet. Várunk vissza egy másik alkalommal!
                {/if}
            </div>
        </div>
    </div>
{/block}