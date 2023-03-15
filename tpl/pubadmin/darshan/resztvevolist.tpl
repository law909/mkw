<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-refresh">Frissít <i class="fas fa-sync-alt"></i></button>
        {if ($lemondhato)}
        <button class="btn btn-darshan js-lemond" data-oraid="{$oraid}" data-oradatum="{$oradatum}">Lemond <i class="fas fa-archive"></i></button>{/if}
    </div>
</div>
{$sorszam = 1}
{foreach $resztvevolist as $resztvevo}
    <div class="row js-resztvevo top-margin-10 color-bkg-darshan">
        {if ($future)}
            <div class="col-1">&nbsp;</div>
            <div class="col">{$sorszam}. {$resztvevo.nev} ({$resztvevo.email})</div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        {if ($resztvevo.tipus == 'berlet')}
                            Bérlet: {$resztvevo.alkalom}/{$resztvevo.elfogyottalkalom}
                        {elseif ($resztvevo.tipus == 'orajegy')}
                            Órajegyet vett
                        {else}
                            NINCS bérlete
                        {/if}
                    </div>
                </div>
            </div>
            <div class="col">{if ($resztvevo.lemondva)}LEMONDTA{/if}</div>
        {else}
            {if ($resztvevo.megjelent)}
                <button class="col-md-1 btn btn-success js-megjegyzes" data-id="{$resztvevo.id}">{if ($resztvevo.megjegyzes)}*{/if}
                    M{if ($resztvevo.megjegyzes)}*{/if}</button>
            {else}
                <button class="col-md-1 btn btn-danger js-megjegyzes" data-id="{$resztvevo.id}">{if ($resztvevo.megjegyzes)}*{/if}
                    M{if ($resztvevo.megjegyzes)}*{/if}</button>
            {/if}
            <button class="col-md-4{if ($resztvevo.new)} text-danger{/if} btn js-partneredit" data-id="{$resztvevo.id}">
                {$sorszam}. {$resztvevo.nev} ({$resztvevo.email})
            </button>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                        {if ($resztvevo.tipus == 'berlet')}
                            Bérlet: {if ($resztvevo.alkalom == 0)}99{else}{$resztvevo.alkalom}{/if}/{$resztvevo.elfogyottalkalom}
                        {elseif ($resztvevo.tipus == 'orajegy')}
                            Órajegyet vett
                        {else}
                            NINCS bérlete
                        {/if}
                    </div>
                </div>
                <div class="row">
                    {if ($resztvevo.type1price > 0)}
                        <div class="col">
                            <button class="btn btn-darshan js-buy" data-type="1" data-id="{$resztvevo.id}" data-price="{$resztvevo.type1price}">
                                {$resztvevo.type1name}
                            </button>
                        </div>
                    {/if}
                    {if ($resztvevo.type2price > 0)}
                        <div class="col">
                            <button class="btn btn-darshan js-buy" data-type="2" data-id="{$resztvevo.id}" data-price="{$resztvevo.type2price}">
                                {$resztvevo.type2name}
                            </button>
                        </div>
                    {/if}
                    {if ($resztvevo.type3price > 0)}
                        <div class="col">
                            <button class="btn btn-darshan js-buy" data-type="3" data-id="{$resztvevo.id}" data-price="{$resztvevo.type3price}">
                                {$resztvevo.type3name}
                            </button>
                        </div>
                    {/if}
                </div>
            </div>
            <div class="col-md-1">
                <div class="row top-margin-10">
                    <div class="col">
                        <input id="OnlineOnlineEdit{$resztvevo.id}" type="radio" name="online-{$resztvevo.id}"
                               value="1"{if ($resztvevo.online == "1")} checked="checked"{/if}><label for="OnlineOnlineEdit{$resztvevo.id}">Online</label>
                    </div>
                    <div class="col">
                        <input id="OnlineEloEdit{$resztvevo.id}" type="radio" name="online-{$resztvevo.id}"
                               value="2"{if ($resztvevo.online == "2")} checked="checked"{/if}><label for="OnlineEloEdit{$resztvevo.id}">Élő</label>
                    </div>
                </div>
            </div>
            <button class="col-md-2 btn btn-darshan top-bottom-margin-5 js-setmegjelent"
                    data-id="{$resztvevo.id}"
                    {if (!$resztvevo.megjelent)}data-mustbuy="{$resztvevo.mustbuy}"{/if}>
                {if (!$resztvevo.megjelent)}Megérkezett{else}Nem érkezett meg{/if}{if ($resztvevo.lemondva)} (LEMONDTA){/if}
            </button>
        {/if}
    </div>
    {$sorszam = $sorszam + 1}
{/foreach}
<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-newpartner">Új gyakorló</button>
    </div>
</div>