<div class="ui-widget ui-widget-content ui-corner-all boltieladaskarb">
    <div class="ui-widget-header ui-corner-top">
        <div class="mainboxinner ui-corner-top">{t('Bolti eladás')}</div>
    </div>
    <div class="mainboxinner js-boltieladas">
        {if $boltivevohiba}
            <div class="js-boltieladas-hiba boltieladas-hiba">{$boltivevohiba}</div>
        {/if}

        <div class="boltieladas-fej">
            <div class="boltieladas-fejsor">
                <span class="setuplabel">{t('Vevő')}:</span>
                <strong class="js-boltivevonev">{$boltivevonev}</strong>
            </div>
            <div class="matt-hseparator"></div>
            <div class="boltieladas-fejsor">
                <label for="BoltieladasFizmodEdit">{t('Fizetési mód')}:</label>
                <select id="BoltieladasFizmodEdit" class="js-boltieladas-fizmod" name="fizmod">
                    {foreach $fizmodlist as $_fm}
                        <option value="{$_fm.id}"{if ($_fm.selected)} selected="selected"{/if}>{$_fm.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="matt-hseparator"></div>
        <table class="boltieladas-tetelek ui-widget ui-widget-content ui-corner-all mattable-repeatable">
            <thead>
            <tr>
                <th>{t('Cikkszám')}</th>
                <th>{t('Termék')}</th>
                <th>{t('Raktáron')}</th>
                <th>{t('Mennyiség')}</th>
                <th>{t('Kedvezmény')} %</th>
                <th>{t('Bruttó egységár')}</th>
                <th>{t('Bruttó')}</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="js-boltieladas-tetelek"></tbody>
            <tfoot>
            <tr>
                <td colspan="6" class="boltieladas-osszesenlabel">{t('Összesen')}:</td>
                <td class="js-boltieladas-bruttosum boltieladas-num">0</td>
                <td></td>
            </tr>
            </tfoot>
        </table>

        <div class="boltieladas-vonalkodsor">
            <label for="BoltieladasVonalkodEdit">{t('Vonalkód / keresés')}:</label>
            <input id="BoltieladasVonalkodEdit" class="js-boltieladas-vonalkod" type="text" autocomplete="off">
            <span class="js-boltieladas-kereshiba boltieladas-hiba"></span>
        </div>
        <div class="js-boltieladas-valtozatvalaszto boltieladas-valtozatvalaszto"></div>
        <div class="matt-hseparator"></div>
        <div class="boltieladas-muvelet">
            <button class="js-boltieladas-rogzit ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only">
                <span class="ui-button-text">{t('Rögzít')}</span>
            </button>
            <span class="js-boltieladas-uzenet boltieladas-uzenet"></span>
        </div>
    </div>
</div>
