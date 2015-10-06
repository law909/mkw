{extends "base.tpl"}

{block "script"}
    <script src="/js/main/superzone/fiok.js"></script>
{/block}

{block "body"}
    <div class="row">
        <ul class="fioknav nav nav-pills nav-stacked col-md-3">
            <li class="active"><a href="#adataim" data-toggle="pill">Contact details</a></li>
            <li><a href="#szamlaadatok" data-toggle="pill">Billing address</a></li>
            <li><a href="#szallitasiadatok" data-toggle="pill">Delivery address</a></li>
            {if ($uzletkoto.loggedin)}
            <li><a href="#discounts" data-toggle="pill">My discounts</a></li>
            {/if}
            <li><a href="#megrend" data-toggle="pill">My orders</a></li>
            {if ($myownaccount)}
            <li><a href="#jelszo" data-toggle="pill">Change password</a></li>
            {/if}
        </ul>
        <div class="fioknav tab-content col-md-9">
            <div class="tab-pane active" id="adataim">
                <form id="FiokAdataim" class="form-horizontal" action="/fiok/ment/adataim" method="post">
                    <div>
                        <label class="control-label" for="VezeteknevEdit">{t('First name')}*:</label>
                        <div class="controls">
                            <input id="KeresztnevEdit" name="keresztnev" type="text" class="form-control" value="{$user.keresztnev}" required>
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="VezeteknevEdit">{t('Last name')}*:</label>
                        <div class="controls">
                            <input id="VezeteknevEdit" name="vezeteknev" type="text" class="form-control" value="{$user.vezeteknev}" required>
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="EmailEdit">{t('Email')}*:</label>
                        <div class="controls">
                            <input id="EmailEdit" name="email" type="email" class="form-control" value="{$user.email}" required>
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="TelefonEdit">{t('Phone')}:</label>
                        <div class="controls">
                            <input id="TelefonEdit" name="telefon" type="text" class="form-control" value="{$user.telefon}">
                        </div>
                    </div>
                    <button type="submit" class="btn okbtn">Save</button>
                </form>
            </div>
            <div class="tab-pane" id="szamlaadatok">
                <form id="FiokSzamlaAdatok" class="form-horizontal" action="/fiok/ment/szamlaadatok" method="post">
                    <div>
                        <label class="control-label" for="SzamlazasiNevEdit">{t('Name')}:</label>
                        <div class="controls">
                            <input id="SzamlazasiNevEdit" name="nev" type="text" class="form-control" value="{$user.nev}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzamlazasiAdoszamEdit">{t('VAT ID')}:</label>
                        <div class="controls">
                            <input id="SzamlazasiAdoszamEdit" name="adoszam" type="text" class="form-control" value="{$user.adoszam}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzamlazasiIrszamEdit">{t('Postal code')}:</label>
                        <div class="controls">
                            <input id="SzamlazasiIrszamEdit" name="irszam" type="text" class="form-control" value="{$user.irszam}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzamlazasiVarosEdit">{t('City')}:</label>
                        <div class="controls">
                            <input id="SzamlazasiVarosEdit" name="varos" type="text" class="form-control" value="{$user.varos}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzamlazasiUtcaEdit">{t('Street')}:</label>
                        <div class="controls">
                            <input id="SzamlazasiUtcaEdit" name="utca" type="text" class="form-control" value="{$user.utca}">
                        </div>
                    </div>
                    <button type="submit" class="btn okbtn">Save</button>
                </form>
            </div>
            <div class="tab-pane" id="szallitasiadatok">
                <div class="acc-copyszamlaadat">
                    <a class="js-copyszamlaadat">Copy billing address </a>
                </div>
                <form id="FiokSzallitasiAdatok" class="form-horizontal" action="/fiok/ment/szallitasiadatok" method="post">
                    <div>
                        <label class="control-label" for="SzallitasiNevEdit">{t('Name')}:</label>
                        <div class="controls">
                            <input id="SzallitasiNevEdit" name="szallnev" type="text" class="form-control" value="{$user.szallnev}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzallitasiIrszamEdit">{t('Postal code')}:</label>
                        <div class="controls">
                            <input id="SzallitasiIrszamEdit" name="szallirszam" type="text" class="form-control" value="{$user.szallirszam}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzallitasiVarosEdit">{t('City')}:</label>
                        <div class="controls">
                            <input id="SzallitasiVarosEdit" name="szallvaros" type="text" class="form-control" value="{$user.szallvaros}">
                        </div>
                    </div>
                    <div>
                        <label class="control-label" for="SzallitasiUtcaEdit">{t('Street')}:</label>
                        <div class="controls">
                            <input id="SzallitasiUtcaEdit" name="szallutca" type="text" class="form-control" value="{$user.szallutca}">
                        </div>
                    </div>
                    <button type="submit" class="btn okbtn">Save</button>
                </form>
            </div>
            {if ($uzletkoto.loggedin)}
            <div class="tab-pane" id="discounts">
                <form id="FiokDiscounts" class="form-horizontal" action="/fiok/ment/discounts" method="post">
                    {foreach $discountlist as $discount}
                        <div>
                            <label class="control-label" for="KedvEdit_{$discount.id}">{$discount.nev}:</label>
                            <div class="controls">
                                <input id="KedvEdit_{$discount.id}" name="kedvezmeny_{$discount.id}" type="number" class="form-control" value="{$discount.kedvezmeny}">
                                <input type="hidden" name="kedvezmenyid[]" value="{$discount.id}">
                                <input type="hidden" name="kedvezmenyoper_{$discount.id}" value="{$discount.oper}">
                                <input type="hidden" name="kedvezmenytermekcsoport_{$discount.id}" value="{$discount.tcsid}">
                            </div>
                        </div>
                    {/foreach}
                    <button type="submit" class="btn okbtn">Save</button>
                </form>
            </div>
            {/if}
            <div class="tab-pane" id="megrend">
                {if (count($megrendeleslist)>0)}
                    <table class="acc-megrendeles">
                        <thead class="acc-megrendeles">
                        <td>Order no.</td>
                        <td>Date</td>
                        <td>Status</td>
                        <td class="textalignright">Price</td>
                        <td>Delivery note no.</td>
                        <td></td>
                        </thead>
                        <tbody class="acc-megrendeles">
                        {foreach $megrendeleslist as $megr}
                            <tr class="acc-megrendelesbordertop acc-megrendelestablerow js-accmegrendelesopen">
                                <td>{$megr.id}</td>
                                <td>{$megr.kelt}</td>
                                <td>{$megr.allapotnev|default:"ismeretlen"}</td>
                                <td class="textalignright">{number_format($megr.brutto, 2, '.', ' ')} {$megr.valutanemnev}</td>
                                <td></td>
                                <td><a href="#" class=""><img src="/themes/main/mkwcansas/img/i_down.png"></a></td>
                            </tr>
                            <tr class="notvisible acc-megrendelesborderbottom">
                                <td colspan="6">
                                    <table>
                                        <tr>
                                            <td><span class="acc-megrendelescaption">Billing address:</span></td>
                                            <td>{$megr.szamlanev|default} {$megr.szamlairszam|default} {$megr.szamlavaros|default} {$megr.szamlautca}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="acc-megrendelescaption">VAT ID:</span></td>
                                            <td>{$megr.adoszam|default}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="acc-megrendelescaption">Delivery address:</span></td>
                                            <td>{$megr.szallnev|default} {$megr.szallirszam|default} {$megr.szallvaros|default} {$megr.szallutca}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="acc-megrendelescaption">Shipping method:</span></td>
                                            <td>{$megr.szallitasimodnev|default}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="acc-megrendelescaption">Payment:</span></td>
                                            <td>{$megr.fizmodnev|default}</td>
                                        </tr>
                                    </table>
                                    <table class="acc-megrendelestetellist">
                                        <thead class="acc-megrendelestetellist">
                                        <td></td>
                                        <td>{t('Item')}</td>
                                        <td><div class="textalignright">{t('Unit price')}</div></td>
                                        <td><div class="textaligncenter">{t('Qty')}</div></td>
                                        <td><div class="textalignright">{t('Price')}</div></td>
                                        </thead>
                                        <tbody>
                                        {foreach $megr.tetellista as $tetel}
                                            <tr class="clickable" data-href="{$tetel.link}">
                                                <td><div class="textaligncenter"><a href="{$tetel.link}"><img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a></div></td>
                                                <td><div><a href="{$tetel.link}">{$tetel.caption}</a></div>
                                                    <div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
                                                    {$tetel.cikkszam}</td>
                                                <td><div class="textalignright">{number_format($tetel.bruttoegysar, 2, ',', ' ')} {$tetel.valutanemnev}</div></td>
                                                <td>
                                                    <div class="textaligncenter">
                                                        <div>{number_format($tetel.mennyiseg,0,',','')}</div>
                                                    </div>
                                                </td>
                                                <td><div class="textalignright">{number_format($tetel.brutto, 2, ',', ' ')} {$tetel.valutanemnev}</div></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                    <div class="textalignright bold"><b>Summary: {number_format($megr.fizetendo, 2, ',', ' ')} {$tetel.valutanemnev}</b></div>
                                    {if ($megr.megjegyzes|default)}
                                        <div class="acc-megrendelescaption">Comment from the shop:</div>
                                        <div>{$megr.megjegyzes}</div>
                                    {/if}
                                    {if ($megr.webshopmessage|default)}
                                        <div class="acc-megrendelescaption">Comment for the shop:</div>
                                        <div>{$megr.webshopmessage}</div>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    You don't have any orders yet.
                {/if}
            </div>
            {if ($myownaccount)}
            <div class="tab-pane" id="jelszo">
                <form id="JelszoChangeForm" class="form-horizontal" action="/fiok/ment/jelszo" method="post">
                    <fieldset>
                        <div>
                            <label class="control-label" for="RegijelszoEdit">{t('Old password')}:</label>
                            <div class="controls">
                                <input id="RegijelszoEdit" name="regijelszo" type="password" class="form-control">
                                <input name="checkregijelszo" type="hidden"  value="1" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label class="control-label">New password:</label>
                            <div class="controls">
                                <input id="Jelszo1Edit" name="jelszo1" type="password" class="form-control" required>
                            </div>
                        </div>
                        <div>
                            <label class="control-label">New password again:</label>
                            <div class="controls">
                                <input id="Jelszo2Edit" name="jelszo2" type="password" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn okbtn">Save</button>
                    </fieldset>
                </form>
            </div>
            {/if}
        </div>
    </div>
{/block}