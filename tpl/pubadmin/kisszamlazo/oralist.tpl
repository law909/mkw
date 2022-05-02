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
