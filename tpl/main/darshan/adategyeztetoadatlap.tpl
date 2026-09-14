<h2>{$msg|escape}</h2>
<input type="hidden" name="e" value="{$link.e|escape}">
<input type="hidden" name="l" value="{$link.l|escape}">
<input type="hidden" name="h" value="{$link.h|escape}">
<div class="form-group">
    <label class="form-label">Email</label>
    <input class="form-control" type="email" value="{$link.e|escape}" disabled>
</div>
<div class="form-group">
    <label class="form-label">Vezetéknév</label>
    <input class="form-control" type="text" name="vezeteknev" value="{$adat.vezeteknev|escape}">
</div>
<div class="form-group">
    <label class="form-label">Keresztnév</label>
    <input class="form-control" type="text" name="keresztnev" value="{$adat.keresztnev|escape}">
</div>
<div class="form-group">
    <label class="form-label">Irányítószám</label>
    <input class="form-control" type="text" name="irszam" maxlength="10" value="{$adat.irszam|escape}">
</div>
<div class="form-group">
    <label class="form-label">Város</label>
    <input class="form-control" type="text" name="varos" value="{$adat.varos|escape}">
</div>
<div class="form-group">
    <label class="form-label">Utca</label>
    <input class="form-control" type="text" name="utca" value="{$adat.utca|escape}">
</div>
<div class="form-group">
    <label class="form-label">Házszám</label>
    <input class="form-control" type="text" name="hazszam" value="{$adat.hazszam|escape}">
</div>
<div>
    <input type="checkbox" id="hirlevelkelledit" name="hirlevelkell"{if ($adat.hirlevelkell)} checked="checked"{/if}>
    <label class="form-label" for="hirlevelkelledit">Kérek hírlevelet (ha fel vagy iratkozva, itt nem tudsz leiratkozni, csak a hírlevélből)</label>
</div>
<p class="hiba js-hiba" style="display: none"></p>
<button class="js-save adategyeztetobtn">Mentés</button>
