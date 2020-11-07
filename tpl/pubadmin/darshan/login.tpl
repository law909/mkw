{extends "../base.tpl"}

{block "content"}
    <div class="row">
        <h4 class="col">Bejelentkezés</h4>
    </div>
    <div class="row">
        <form class="col" method="POST" action="{$loginurl}">
            <div class="form-group">
                <label for="emailinput">Email</label>
                <input id="emailinput" type="text" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="pwinput">Jelszó</label>
                <input id="pwinput" type="password" name="jelszo" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">OK</button>
        </form>
    </div>
{/block}