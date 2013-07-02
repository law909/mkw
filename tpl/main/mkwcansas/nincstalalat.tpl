{extends "base.tpl"}
{block "script"}
<script src="/js/main/mkwcansas/jquery.blockUI.js"></script>
{/block}
{block "kozep"}
<div class="container">
	<div class="row">
	<h2 class="textaligncenter">Nincs találat. Vásároljon ajánlott termékeink közül.</h2>
	</div>
	<div class="row">
		<div class="span16">
			{$ajtermekdb=count($ajanlotttermekek)}
			{if ($ajtermekdb>0)}
			{$cikl=0}
			{foreach $ajanlotttermekek as $_termek}
				{$maradek=$cikl%3}
				{if ($maradek==0)}
				<div class="itemListBlock">
				<div class="row">
				{/if}
					<div class="span4 textaligncenter">
						<div class="miniItemPicture"><a href="/termek/{$_termek.slug}"><img src="{$_termek.kiskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}"></a></div>
						<div class="miniItemCaption"><a href="/termek/{$_termek.slug}"><h4>{$_termek.caption}</h4></a></div>
						<div class="miniItemJelzok">
							{foreach $_termek.cimkelistaban as $_jelzo}
								<img src="{$_jelzo.kiskepurl}" title="{$_jelzo.caption}" alt="{$_jelzo.caption}">
							{/foreach}
						</div>
						<div class="row">
							<div class="span4">
								<h3 class="itemPrice"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h3>
							</div>
						</div>
						<!-- div class="row">
							<div class="span4">
								<a href="/kosar/add?id={$_termek.id}" rel="nofollow" class="kosarba btn btn-large btn-success">
									<i class="icon-shopping-cart icon-white"></i>
									{t('Megveszem')}
								</a>
							</div>
						</div -->
					</div>
				{$cikl=$cikl+1}
				{if ($maradek==2||$cikl==$ajtermekdb)}
				</div>
				</div>
				{/if}
			{/foreach}
			{/if}
		</div>
	</div>
</div>
{/block}