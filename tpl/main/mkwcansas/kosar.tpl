{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1 js-cart">
			{if (count($tetellista)>0)}
			<div class="textalignright megrendelemcontainer">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
			<table class="table table-bordered">
				{include 'kosartetellist.tpl'}
			</table>
			<div class="textalignright megrendelemcontainer">
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
			{else}
				Az Ön kosara üres
			{/if}
		</div>
	</div>
</div>
{/block}