{* Kiemelő szín választó: színminták + szabad keverés; $accentname az űrlapmező neve *}
<div class="uiaccentpicker">
    {foreach $uiaccentpresets as $_preset}
        <a href="#" class="js-uiaccentpreset uiaccentpicker-minta{if ($_preset.color == $accentvalue)} uiaccentpicker-minta-aktiv{/if}"
           data-color="{$_preset.color}" style="background:{$_preset.color};" title="{t($_preset.nev)}"></a>
    {/foreach}
    <input type="color" class="js-uiaccentinput uiaccentpicker-kevert" name="{$accentname}"
           value="{$accentvalue}" title="{t('Saját szín keverése')}">
</div>
