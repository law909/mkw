{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
		<div class="span10 offset1 js-cart">
			{if (count($tetellista)>0)}
			<div class="megrendelemcontainer">
				<a href="{$prevuri}" class="btn okbtn">Folytatom a vásárlást</a>
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn pull-right">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
			<table class="table table-bordered">
				{include 'kosartetellist.tpl'}
			</table>
			<div class="megrendelemcontainer">
				<a href="{$prevuri}" class="btn okbtn">Folytatom a vásárlást</a>
				<a href="{$showcheckoutlink}" rel="nofollow" class="btn cartbtn pull-right">
					<i class="icon-ok icon-white"></i>
					{t('Megrendelem')}
				</a>
			</div>
			{else}
				<h3>Az Ön kosara üres, és ezért ő egy kicsit szomorú...</h3>
                                A kosár egyedüli célja, hogy tele legyen szép ruházati cikkekkel, kempingfelszereléssel, sportszerekkel és sok egyéb más termékkel, amelyek elérhetők itt, a Mindent Kapni Webáruházban.<br>
                                Tegye hát boldoggá a kosarat, adjon értelmet az ő életének!<br><br>
                                <a href="{$prevuri}" class="btn okbtn">Folytatom a vásárlást</a>
			{/if}
		</div>
	</div>
</div>
{/block}
