{if ((!$comptype) || ($comptype=='szamla'))}
    <div>
        <label for="DatumTipusEdit">Időszak:</label>
        <select id="DatumTipusEdit" name="datumtipus">
            <option value="kelt" {if ($datumtipus == 'kelt')}selected="selected"{/if}>{t('kelt')}</option>
            <option value="teljesites" {if ($datumtipus == 'teljesites')}selected="selected"{/if}>{t('teljesítés')}</option>
            <option value="esedekesseg" {if ($datumtipus == 'esedekesseg')}selected="selected"{/if}>{t('esedékesség')}</option>
        </select>
        <input id="TolEdit" name="tol" data-datum="{$toldatum}">
        <input id="IgEdit" name="ig" data-datum="{$igdatum}">
    </div>
{elseif ($comptype=='datum')}
    <div>
        <label for="TolEdit">Időszak:</label>
        <input id="TolEdit" name="tol" data-datum="{$toldatum}">
        <input id="IgEdit" name="ig" data-datum="{$igdatum}">
    </div>
{/if}