{extends "../base.tpl"}

{block "content"}
    <div class="row">
        <h4 class="col">{$pagetitle}</h4>
        <a class="btn btn-darshan" href="/pubadmin/logout">Kijelentkezés</a>
    </div>

    <div class="row">
        <form class="col">
            <div class="form-group">
                <label for="oraselect">Óra</label>
                <select id="oraselect" class="form-control" name="ora">
                    <option value="">válassz</option>
                    {foreach $oralista as $ora}
                        <option value="{$ora.id}">{$ora.nev}</option>
                    {/foreach}
                </select>
            </div>
        </form>
    </div>

    <div id="resztvevolist"></div>

    <div class="modal fade" id="buyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel"></h5>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">OK</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Mégsem</button>
                </div>
            </div>
        </div>
    </div>
{/block}