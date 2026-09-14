{extends "../base.tpl"}

{block "content"}
    <div class="row">
        <h4 class="col">{$pagetitle} - {$tanarnev}</h4>
        <a class="btn btn-darshan" href="/pubadmin/logout">Kijelentkezés</a>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="datumselect">Dátum</label>
                <input id="datumselect" type="date" name="datum" class="form-control">
            </div>
        </div>
    </div>
    <div class="row bottom-margin-10">
        <div class="col">
            <a class="color-darshan" data-toggle="collapse" href="#partnersugo" role="button" aria-expanded="false"
               aria-controls="partnersugo"><i class="fas fa-question-circle"></i> Hogyan működik a gyakorló és a partner?</a>
            <div class="collapse" id="partnersugo">
                <ol class="top-margin-10 mb-0">
                    <li><strong>Jelentkezés:</strong> egy sor ezen az alkalmon, saját névvel és emaillel – csak itt látszik.</li>
                    <li><strong>Partner:</strong> a partnertörzs rekordja, emailcím alapján. A bérlet, az órajegy és a számla a
                        partnerhez tartozik, a számla a partner nevére és címére készül.</li>
                    <li>Az <strong>Új gyakorló</strong> gomb azonnal partnert köt vagy létrehoz; az órarendből jelentkezőnek a
                        Megérkezett vagy a vásárlás gombra jön létre.</li>
                    <li>A weboldal meglévő partner adatait nem írja át, csak az üres mezőket tölti ki. Átírni a nevére kattintva,
                        a „Partnertörzs” blokkban lehet.</li>
                </ol>
            </div>
        </div>
    </div>
    <div id="oralist"></div>
    <div id="resztvevolist"></div>
    <div id="idopontlist"></div>
    <div id="idopontfoglalaslist"></div>
    <div class="modal fade" id="buyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel"></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="aredit">Ár</label>
                        <input id="aredit" name="ar" type="text" class="form-control">
                    </div>
                    <div class="form-group form-check">
                        <input id="lateredit" name="later" type="checkbox" class="form-check-input">
                        <label for="lateredit" class="form-check-label">Később fizet</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-buyok">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="megjegyzesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Megjegyzés</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="megjegyzesedit">Megjegyzés</label>
                        <textarea id="megjegyzesedit" name="megjegyzes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-megjegyzesok">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mustbuyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nincs bérlet!</h5>
                </div>
                <div class="modal-body">
                    A gyakorlónak órajegyet vagy bérletet kell vásárolnia. Nyomd meg az "1 alkalmas", "5 alkalmas" vagy a "korlátlan" gombot a vásárláshoz.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Bezár</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="idopontLemondModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alkalom lemondása</h5>
                </div>
                <div class="modal-body">
                    Biztosan lemondod ezt az alkalmat? A foglalások lemondásra kerülnek, és a
                    gyakorlók értesítő levelet kapnak.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-idopontlemondok">Lemondom</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lemondokModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kész!</h5>
                </div>
                <div class="modal-body">
                    Az óra lemondása kész. A bejelentkezett gyakorlók kaptak egy emailt, az órarendben ki van írva, hogy az óra elmarad.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Bezár</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mustsetOnlineModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Élő vagy online?</h5>
                </div>
                <div class="modal-body">
                    Állítsd be, hogy a gyakorló élőben vagy online vett részt!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Bezár</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="partnerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title js-partnermodalcim">Új gyakorló</h5>
                </div>
                <div class="modal-body">
                    <div class="btn-group d-flex bottom-margin-10" role="group">
                        <button type="button" class="btn btn-outline-secondary w-50 js-partnermod" data-mod="kereses">Már járt nálunk</button>
                        <button type="button" class="btn btn-outline-secondary w-50 js-partnermod" data-mod="uj">Először jön</button>
                    </div>
                    <div class="js-modkereses">
                        <div class="form-group">
                            <label for="keresoedit">Keresés névre vagy emailre</label>
                            <select id="keresoedit" name="kereso" class="form-control" autocomplete="off"></select>
                            <small class="form-text text-muted">Legalább 3 betű.</small>
                        </div>
                        <div class="card js-partnerkartya" hidden>
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Partnertörzs – ebből készül a számla</h6>
                                <div class="font-weight-bold js-kartyanev"></div>
                                <div class="js-kartyaemail"></div>
                                <div class="js-kartyacim"></div>
                                <div class="text-danger js-kartyaakadaly"></div>
                            </div>
                        </div>
                    </div>
                    <div class="js-moduj" hidden>
                        <div class="form-group">
                            <label for="nevedit">Teljes név (vezeték- és keresztnév)</label>
                            <input id="nevedit" name="nev" type="text" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="emailedit">Email</label>
                            <input id="emailedit" name="email" type="email" class="form-control" autocomplete="off">
                        </div>
                        <div class="alert js-emailinfo" hidden></div>
                        <p class="mb-2">A cím a számlához kell.</p>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="irszamedit">Irányítószám</label>
                                    <input id="irszamedit" name="irszam" type="text" class="form-control" maxlength="10"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="varosedit">Város</label>
                                    <input id="varosedit" name="varos" type="text" class="form-control" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="utcaedit">Utca, házszám</label>
                            <input id="utcaedit" name="utca" type="text" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <p class="top-margin-10 mb-0 font-italic js-mentesosszegzes"></p>
                    <div class="alert alert-danger top-margin-10 mb-0 js-partnerhiba" hidden></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-partnerok">Bejelentkeztetem</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="partnerEditModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gyakorló módosítás</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="nev2edit">Név</label>
                                <input id="nev2edit" name="nev" type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="email2edit">Email</label>
                                <input id="email2edit" name="email" type="email" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="top-margin-10">A cím a számlához kell. Ha van már partnere, az ő
                                címét látod itt, és a mentés arra is rákerül.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="irszam2edit">Irányítószám</label>
                                <input id="irszam2edit" name="irszam" type="text" class="form-control" maxlength="10"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="varos2edit">Város</label>
                                <input id="varos2edit" name="varos" type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="utca2edit">Utca, házszám</label>
                                <input id="utca2edit" name="utca" type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-partnereditok">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
{/block}