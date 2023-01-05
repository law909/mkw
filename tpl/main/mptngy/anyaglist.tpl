{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/anyaglist.js?v=1"></script>
{/block}

{block "body"}
    <div class="co-container" x-data="anyaglist" x-init="getLists">
        <div class="co-data-container">
            <div class="co-row co-flex-dir-row">
                <div class="co-login-box">
                    <h2>Anyaglist</h2>
                </div>
            </div>
        </div>
    </div>
{/block}