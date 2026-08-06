{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/dolgozo.js"></script>
{/block}

{block "kozep"}
    <form id="loginform" method="POST" action="{$loginurl}" class="ui-widget">
        <div>
            <label>{at('Email')}:</label>
            <input name="email" type="text" required>
        </div>
        <div>
            <label>{at('Jelszó')}:</label>
            <input name="jelszo" type="password" required>
        </div>
        <div>
            <input name="ok" type="submit" value="{at('OK')}" class="ui-button">
        </div>
    </form>
{/block}