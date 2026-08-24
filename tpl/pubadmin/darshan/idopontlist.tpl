{if ($idopontlista|@count)}
    <div class="row">
        <form class="col">
            <div class="form-group">
                <label for="idopontselect">Időpont</label>
                <select id="idopontselect" class="form-control" name="idopont">
                    <option value="">válassz</option>
                    {foreach $idopontlista as $idopont}
                        <option value="{$idopont.id}">{$idopont.nev}</option>
                    {/foreach}
                </select>
            </div>
        </form>
    </div>
{/if}
