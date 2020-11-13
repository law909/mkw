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
                    <input id="aredit" name="ar" type="text" class="form-control" value="2600">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary js-buyok">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
{/block}