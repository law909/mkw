{extends "basestone.tpl"}

{block "script"}
    <!--script src="/js/main/darshan/rendezvenyreg.js"></script-->
{/block}

{block "stonebody"}
    <div class="container">
        <div class="row">
            <div class="col">
                <h4 class="color-darshan">SIKERES JELENTKEZÉS</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Küldtünk neked egy emailt a részletekkel.
                {if ($jelentkezes.rendezvenyar > 0)}
                Nézd meg az email fiókod és utald el nekünk a részvételi díjat, hogy le tudjuk foglalni a helyed!
                {/if}
            </div>
        </div>
    </div>
{/block}