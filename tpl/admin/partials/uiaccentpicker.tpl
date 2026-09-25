{* Kiemelő szín választó: színminták + szabad keverés. $accentname üresen: nem űrlapmező, azonnal ment (appinit.js) *}
<div class="uiaccentpicker{if (empty($accentname))} js-uiaccentlive{/if}">
    {foreach $uiaccentpresets as $_preset}
        <a href="#" class="js-uiaccentpreset uiaccentpicker-minta{if ($_preset.color == $accentvalue)} uiaccentpicker-minta-aktiv{/if}"
           data-color="{$_preset.color}" style="background:{$_preset.color};" title="{t($_preset.nev)}"></a>
    {/foreach}
    <input type="color" class="js-uiaccentinput uiaccentpicker-kevert"{if (!empty($accentname))} name="{$accentname}"{/if}
           value="{$accentvalue}" title="{t('Saját szín keverése')}">
</div>
