{extends "basestone.tpl"}

{block "bodyclass"}class="signin"{/block}
{block "stonebody"}
    <div class="container">
        <form class="form-signin" method="post" action="/login/ment">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="jelszo" id="inputPassword" class="form-control" placeholder="Password" required="">
            <button class="btn btn-lg btn-red btn-block" type="submit">Sign in</button>
        </form>
    </div>
{/block}