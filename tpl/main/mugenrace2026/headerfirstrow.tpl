<div class="top-header container-full__with-padding">  
  <div class="row">
    <div class="col flex-cl"></div>
    <div class="col flex-cr">
      {if (!$user.loggedin)}
      
        <nav class="top-header__menu flex-cc">
          <ul id="" class="flex-cc">
            {* <li>
              <a rel="nofollow" href="{$showloginlink}" class="headerloginicon">{t('Jelentkezzen be')}</a>
            </li>
            <li>
              <a rel="nofollow" href="{$showregisztraciolink}">{t('Hozza létre saját fiókját')}</a>
            </li> *}
            <li>
              <a rel="nofollow" href="{$showaccountlink}" title="{t('Fiókom')}">{t('Fiókom')}</a>
            </li>
            {* <li><a href="/hirek" title="{t('Legfrissebb híreink')}">{t('Legfrissebb híreink')}</a></li>
            <li><a href="/statlap/about-us">{t('Rólunk')}</a></li>
            <li><a href="/statlap/sponsored-riders">{t('Szponzorált versenyzők')}</a></li>
            <li><a href="/statlap/teams">{t('Csapatok')}</a></li> *}
          </ul>
        </nav>
			{else}
        <nav class="top-header__menu flex-cc">
          <ul id="" class="flex-cc">
            <li>
              <a rel="nofollow" href="{$showaccountlink}" title="{t('Fiókom')}">{$user.nev}</a>
            </li>
            <li>
              <a rel="nofollow" href="{$dologoutlink}">{t('Kijelentkezés')}</a>
            </li>
          </ul>
        </nav>
			{/if}  

      <div class="header-country-wrapper">
        <select name="headerorszag" class="headerorszag">
            {foreach $orszaglist as $f}
                <option value="{$f.id}"{if ($f.selected)} selected="selected"{/if}>{$f.caption|lower|capitalize}</option>
            {/foreach}
        </select>
        <div id="countryTrigger" class="country-trigger">
          English
        </div>

        <div id="countryModal" class="country-modal">
          <div class="country-modal__content">
            {* <button class="country-modal-close">×</button> *}
            <i class="icon close country-modal__close icon__click"></i>

            <h2>{t('Válassz országot')}</h2>

            <div class="country-list">
              {foreach $orszaglist as $f}
                {if ($f.caption)}<button class="button bordered {if ($f.selected)} selected{/if}" data-value="{$f.id}">{$f.caption|lower|capitalize}</button>{/if}
              {/foreach}
              
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>
    