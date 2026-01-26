{if isset($blokk.lathato) && $blokk.lathato}
<section id="{$blokk.id}"
  class="
    {if (isset($blokk.videourl) && $blokk.videourl)} video {/if}
    {if (isset($blokk.hatterkepurl) && $blokk.hatterkepurl)} full-banner inverse {/if}
    {if (isset($blokk.szovegigazitas)}
     {if $blokk.szovegigazitas==1} left
    {elseif $blokk.szovegigazitas==2} center
    {elseif $blokk.szovegigazitas==3} right
    {/if}
    {/if}

    {if (isset($blokk.blokkmagassag) && $blokk.blokkmagassag==1)} {/if}
    {if (isset($blokk.blokkmagassag) && $blokk.blokkmagassag==2)} full-banner inverse {/if}
    {if (isset($blokk.blokkmagassag) && $blokk.blokkmagassag==3)} hero-section {/if}

    {$blokk.cssclass}
  " {if (isset($blokk.cssstyle) && $blokk.cssstyle)} style="{$blokk.cssstyle}" {/if}>
    {if (isset($blokk.videourl) && $blokk.videourl)}
      <video autoplay muted loop playsinline webkit-playsinline class="hero-video">
          <source src="{$blokk.videourl}" type="video/mp4">
          Your browser does not support the video tag.
      </video>
    {/if}
    {if (isset($blokk.hatterkepurl) && $blokk.hatterkepurl)}
      <img src="{$blokk.hatterkepurl}" alt="{$blokk.cim}">
    {/if}
    <div
      class="
        {if (isset($blokk.blokkmagassag) && $blokk.blokkmagassag==2)}  banner-content {/if}
        {if (isset($blokk.blokkmagassag) && $blokk.blokkmagassag==3)}  hero-content hero-content__inverse {/if}
        flex-col flex-cc
      ">
      {if (isset($blokk.cim) && $blokk.cim)}
        <h1>{$blokk.cim}</h1>
      {/if}
      {if (isset($blokk.leiras) && $blokk.leiras)}
        <p>{$blokk.leiras}</p>
      {/if}
      {if (isset($blokk.gomburl) && $blokk.gomburl && isset($blokk.gombfelirat) && $blokk.gombfelirat)}
        <a href="{$blokk.gomburl}" class="button bordered inverse">{$blokk.gombfelirat}</a>
      {/if}
    </div>
</section>
{/if}