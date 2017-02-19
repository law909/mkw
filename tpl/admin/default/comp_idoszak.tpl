{if ((!$comptype) || ($comptype=='szamla'))}
    <div>
        <label for="DatumTipusEdit">{at('Időszak')}:</label>
        <select id="DatumTipusEdit" name="datumtipus">
            <option value="kelt" {if ($datumtipus == 'kelt')}selected="selected"{/if}>{at('kelt')}</option>
            <option value="teljesites" {if ($datumtipus == 'teljesites')}selected="selected"{/if}>{at('teljesítés')}</option>
            <option value="esedekesseg" {if ($datumtipus == 'esedekesseg')}selected="selected"{/if}>{at('esedékesség')}</option>
        </select>
        <input id="TolEdit" name="tol" data-datum="{$toldatum}">
        <input id="IgEdit" name="ig" data-datum="{$igdatum}">
    </div>
{elseif ($comptype=='datum')}
    <div>
        <label for="TolEdit">{at('Időszak')}:</label>
        <input id="TolEdit" name="tol" data-datum="{$toldatum}">
        <input id="IgEdit" name="ig" data-datum="{$igdatum}">
    </div>
{elseif ($comptype=='hataridos')}
    <div>
        <label for="DatumTipusEdit">{at('Időszak')}:</label>
        <select id="DatumTipusEdit" name="datumtipus">
            <option value="kelt" {if ($datumtipus == 'kelt')}selected="selected"{/if}>{at('kelt')}</option>
            <option value="teljesites" {if ($datumtipus == 'teljesites')}selected="selected"{/if}>{at('teljesítés')}</option>
            <option value="esedekesseg" {if ($datumtipus == 'esedekesseg')}selected="selected"{/if}>{at('esedékesség')}</option>
            <option value="hatarido" {if ($datumtipus == 'hatarido')}selected="selected"{/if}>{at('határidő')}</option>
        </select>
        <input id="TolEdit" name="tol" data-datum="{$toldatum}">
        <input id="IgEdit" name="ig" data-datum="{$igdatum}">
    </div>
{/if}