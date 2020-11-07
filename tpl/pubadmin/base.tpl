<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/themes/pubadmin/default/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/themes/pubadmin/{$pubadmintheme}/style.css" />
    <script type="text/javascript" src="/js/pubadmin/default/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="/js/pubadmin/default/bootstrap.bundle.min.js"></script>
    {block "inhead"}
    {/block}
    <script type="text/javascript" src="/js/admin/default/appinit.js"></script>
    <title>{$pagetitle|default} - {t('Billy pubAdmin')}</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <h3>Billy pubAdmin</h3>
        </div>
    </div>
    {block "content"}
    {/block}
</div>
</body>
</html>