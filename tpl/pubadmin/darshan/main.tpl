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
                    <label for="aredit">Ár</label>
                    <input id="aredit" name="ar" type="text" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-buyok">OK</button>
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
                    A látogatónak órajegyet vagy bérletet kell vásárolnia. Nyomd meg az "Órajegy" vagy a "4-es bérlet" gombot a vásárláshoz.
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
{/block}