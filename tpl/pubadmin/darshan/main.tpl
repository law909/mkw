{extends "../base.tpl"}

{block "content"}
    <div class="row">
        <h4 class="col">{$pagetitle}</h4>
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
    <div id="oralist"></div>
    <div id="resztvevolist"></div>

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
                        <input id="lateredit" name="later" type="checkbox" class="form-check-input" checked="checked">
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
                    A gyakorlónak órajegyet vagy bérletet kell vásárolnia. Nyomd meg az "Órajegy" vagy a "4-es bérlet" gombot a vásárláshoz.
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
                    <h5 class="modal-title">Gyakorló</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <legend>Keresés</legend>
                            <div class="form-group">
                                <label for="keresoedit">Keresés</label>
                                <select id="keresoedit" name="kereso" class="form-control" autocomplete="off"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <legend>Új felvitel</legend>
                            <div class="form-group">
                                <label for="nevedit">Név</label>
                                <input id="nevedit" name="nev" type="text" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="emailedit">Email</label>
                                <input id="emailedit" name="email" type="email" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-partnerok">OK</button>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-partnereditok">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
{/block}