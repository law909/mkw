{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<article itemtype="http://schema.org/Article" itemscope="">
			<div class="row">
				<div class="span12">
					{$statlap.szoveg}
                                        <!-- AddThis Button BEGIN -->
                                        <div class="addthis_toolbox addthis_default_style ">
                                        <a class="addthis_button_facebook_like" style="cursor:pointer"></a>
                                        <a class="addthis_button_facebook" style="cursor:pointer"></a>
                                        <g:plusone size="small"></g:plusone>
                                        <a class="addthis_button_iwiw" style="cursor:pointer"></a>
                                        <a class="addthis_button_twitter" style="cursor:pointer"></a>
                                        <a class="addthis_button_email" style="cursor:pointer"></a>
                                        <a class="addthis_button_pinterest_pinit" style="cursor:pointer"></a>
                                        </div>
                                        <script type="text/javascript">var addthis_config = { "data_track_clickback":true };</script>
                                        <script type="text/javascript">var addthis_config = { "data_track_addressbar":true };</script>
                                        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=quixoft"></script>
                                        <!-- AddThis Button END -->

				</div>
			</div>
	</article>
</div>
{/block}