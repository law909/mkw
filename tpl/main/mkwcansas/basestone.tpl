<!DOCTYPE html>
<html lang="hu">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="{$seodescription|default}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta property="og:site_name" content="Mindentkapni.hu"/>
        {block "meta"}{/block}
		<title>{$pagetitle|default}</title>
		<link type="application/rss+xml" rel="alternate" title="{$feedhirtitle|default}" href="/feed/hir">
		<link type="application/rss+xml" rel="alternate" title="{$feedtermektitle|default}" href="/feed/termek">
		<!--link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/bootstrap-responsive.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/jquery.slider.min.css">
		<link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/magnific-popup.css"-->
        <link rel="stylesheet" href="/themes/main/mkwcansas/royalslider/royalslider.css">
        <link rel="stylesheet" href="/themes/main/mkwcansas/royalslider/skins/default-inverted/rs-default-inverted.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/mkw.css">
        <link type="text/css" rel="stylesheet" href="/themes/main/mkwcansas/style.css">
		{block "css"}{/block}
        {if ($dev)}
        <script src="/js/main/mkwcansas/jquery-1.11.1.min.js"></script>

		<script src="/js/main/mkwcansas/jquery-migrate-1.2.1.js"></script>

        <script src="/js/main/mkwcansas/mkwerrorlog.js"></script>

		<script src="/js/main/mkwcansas/jquery.magnific-popup.min.js"></script>
		<script src="/js/main/mkwcansas/jquery.slider.min.js"></script>
        <script src="/js/main/mkwcansas/jquery.royalslider.min.js"></script>
        <script src="/js/main/mkwcansas/jquery.debounce.min.js"></script>
        <script src="/js/main/mkwcansas/jquery.inputmask.min.js"></script>
        <script src="/js/main/mkwcansas/bootstrap-transition.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-modal.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-tab.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-typeahead.js"></script>
		<script src="/js/main/mkwcansas/bootstrap-tooltip.js"></script>
		<script src="/js/main/mkwcansas/h5f.js"></script>
		<script src="/js/main/mkwcansas/matt-accordion.js"></script>
        {else}
        <script src="/js/main/mkwcansas/mkwbootstrap.js?v={$bootstrapjsversion}"></script>
        {/if}
		{block "script"}{/block}
        {if ($dev)}
		<script src="/js/main/mkwcansas/mkwmsg.js"></script>
		<script src="/js/main/mkwcansas/mkw.js"></script>
		<script src="/js/main/mkwcansas/checks.js"></script>
		<script src="/js/main/mkwcansas/checkout.js"></script>
		<script src="/js/main/mkwcansas/cart.js"></script>
		<script src="/js/main/mkwcansas/fiok.js"></script>
		<script src="/js/main/mkwcansas/termekertekeles.js"></script>
		<script src="/js/main/mkwcansas/mkwcansas.js"></script>
        {else}
        <script src="/js/main/mkwcansas/mkwapp.js?v={$jsversion}"></script>
        {/if}
        {if ($GAFollow)}
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '{$GAFollow}']);
            _gaq.push(['_trackPageview']);

            (function() {
              var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
              ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
              var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        {/if}
        <script>
            (function(g,r,a,v,i,t,y){
                g[a]=g[a]||[],y=r.createElement(v),
                    g=r.getElementsByTagName(v)[0];y.async=1;
                y.src='//'+i+'/js/'+t+'/gr_reco5.min.js';
                g.parentNode.insertBefore(y,g);y=r.createElement(v),y.async=1;
                y.src='//'+i+'/grrec-'+t+'-war/JSServlet4?cc=1';
                g.parentNode.insertBefore(y,g);
            })(window, document, '_gravity','script', 'mindentkapnihu.yusp.com', 'mindentkapnihu');
        </script>
        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s){
                if(f.fbq)return;n=f.fbq=function(){ n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '522099121505135');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=522099121505135&ev=PageView&noscript=1"/></noscript>
        <!-- End Facebook Pixel Code -->
        <script id="barat_hud_sr_script">
            var hst = document.createElement("script");
            hst.src = "//admin.fogyasztobarat.hu/h-api.js";
            hst.type ="text/javascript";hst.setAttribute("data-id", "M6CJIN2L");
            hst.setAttribute("id", "fbarat");
            var hs = document.getElementById("barat_hud_sr_script");
            hs.parentNode.insertBefore(hst, hs);
        </script>
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
        <!--
        <script type="text/javascript">
            (function(e,a){
                var t,r=e.getElementsByTagName("head")[0],c=e.location.protocol;
                t=e.createElement("script");t.type="text/javascript";
                t.charset="utf-8";t.async=!0;t.defer=!0;
                t.src=c+"//front.optimonk.com/public/"+a+"/js/preload.js";r.appendChild(t);
            })(document,"31897");
        </script>
        -->
	</body>
</html>