{extends "basestone.tpl"}

{block "css"}
    <link href="/themes/main/superzone/style.css" rel="stylesheet">
{/block}

{block "bodyclass"}class="signin"{/block}
{block "stonebody"}
    <div class="container">
        <form class="form-signin">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
            <button class="btn btn-lg btn-red btn-block" type="submit">Sign in</button>
        </form>
    </div>
{/block}