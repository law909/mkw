<h2>{$msg}</h2>
<div class="form-group">
    <label class="form-label">Vezetéknév</label>
    <input class="form-control" type="text" name="vezeteknev" value="{$vezeteknev}">
</div>
<div class="form-group">
    <label class="form-label">Keresztnév</label>
    <input class="form-control" type="text" name="keresztnev" value="{$keresztnev}">
</div>
<div class="form-group">
    <label class="form-label">Irányítószám</label>
    <input class="form-control" type="text" name="irszam" value="{$irszam}">
</div>
<div class="form-group">
    <label class="form-label">Város</label>
    <input class="form-control" type="text" name="varos" value="{$varos}">
</div>
<div class="form-group">
    <label class="form-label">Utca</label>
    <input class="form-control" type="text" name="utca" value="{$utca}">
</div>
<div class="form-group">
    <label class="form-label">Házszám</label>
    <input class="form-control" type="text" name="hazszam" value="{$hazszam}">
</div>
<div>
    <input type="checkbox" name="hirlevelkell" {if ($hirlevelkell)}checked="checked"{/if}>
    <label class="form-label">Kérek hírlevelet (ha fel vagy iratkozva, itt nem tudsz leíratkozni csak hírlevélből)</label>
</div>
<button class="js-save adategyeztetobtn">Mentés</button>