<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seodescription|default}">
    <meta name="robots" content="{$robots|default:'index,follow'}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {if ($canonical|default)}<link rel="canonical" href="{$canonical|escape}">{/if}
    <meta property="og:site_name" content="{$cegadatok.markanev|default:'Mindentkapni.hu'|escape}">
    <meta property="og:locale" content="{$oglocale|default:'hu_HU'}">
    <meta property="og:type" content="{$ogtype|default:'website'}">
    <meta property="og:title" content="{$ogtitle|default:$pagetitle|default|escape}">
    <meta property="og:description" content="{$ogdesc|default:$seodescription|default|strip_tags|strip|trim|escape}">
    {if ($canonical|default)}<meta property="og:url" content="{$canonical|escape}">{/if}
    {if ($ogimage|default)}
        <meta property="og:image" content="{$ogimage|escape}">
        {if (!$ogimagesajat)}
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        {/if}
    {/if}
    <meta name="twitter:card" content="summary_large_image">
    {block "meta"}{/block}
    <title>{$pagetitle|default}</title>
    <link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
    <link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
    {* külső könyvtárak stíluslapjai: a téma saját CSS-e írja őket felül, ezért előbb jönnek *}
    {block "vendorcss"}{/block}
    <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/mkw.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/style.css">
    {block "css"}{/block}
    {if ($dev)}
        <script defer src="/js/main/mkwcansas/jquery-1.11.1.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery-migrate-1.2.1.js"></script>
        <script defer src="/js/main/mkwcansas/mkwerrorlog.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.magnific-popup.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.slider.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.debounce.min.js"></script>
        <script defer src="/js/main/mkwcansas/jquery.inputmask.min.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-transition.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-modal.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-tab.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-typeahead.js"></script>
        <script defer src="/js/main/mkwcansas/bootstrap-tooltip.js"></script>
        <script defer src="/js/main/mkwcansas/h5f.js"></script>
        <script defer src="/js/main/mkwcansas/matt-accordion.js"></script>
    {else}
        <script defer src="/js/main/mkwcansas/mkwbootstrap.js?v={$bootstrapjsversion}"></script>
    {/if}
    {* Ide csak önálló, jQuery-független kód való: a fenti csomagok defer-esek, tehát később futnak. *}
    {block "script"}{/block}
    {if ($dev)}
        <script defer src="/js/main/mkwcansas/mkwmsg.js"></script>
        <script defer src="/js/main/mkwcansas/mkw.js"></script>
        <script defer src="/js/main/mkwcansas/checks.js"></script>
        <script defer src="/js/main/mkwcansas/checkout.js"></script>
        <script defer src="/js/main/mkwcansas/cart.js"></script>
        <script defer src="/js/main/mkwcansas/fiok.js"></script>
        <script defer src="/js/main/mkwcansas/termekertekeles.js"></script>
        <script defer src="/js/main/mkwcansas/mkwcansas.js"></script>
    {else}
        <script defer src="/js/main/mkwcansas/mkwapp.js?v={$jsversion}"></script>
    {/if}
    {if ($GAFollow)}
        <script async src="https://www.googletagmanager.com/gtag/js?id={$GAFollow}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', '{$GAFollow}');
        </script>
    {/if}
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '522099121505135');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=522099121505135&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->
    <script id="barat_hud_sr_script">
        var hst = document.createElement("script");
        hst.src = "//admin.fogyasztobarat.hu/h-api.js";
        hst.async = true;
        hst.type = "text/javascript";
        hst.setAttribute("data-id", "M6CJIN2L");
        hst.setAttribute("id", "fbarat");
        var hs = document.getElementById("barat_hud_sr_script");
        hs.parentNode.insertBefore(hst, hs);
    </script>
    {$orgjsonld|default}
</head>
<body class="bgimg">
{block "body"}
{/block}
{block "stonebody"}
{/block}
<div id="dialogcenter" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer"></div>
</div>
<div id="messagecenter" class="mfp-hide"></div>
</body>
</html>