{extends "../rep_base.tpl"}

{block "body"}
    <h4>Csomagolási lista / Packing list</h4>
    <h5>{$egyed.id} &nbsp; {$egyed.partnernev|escape} &nbsp; {$egyed.keltstr}</h5>
    {if ($hianyzik > 0)}
        <h5>Figyelem: {$hianyzik} darab nincs dobozba téve.</h5>
    {/if}

    <h5>Dobozméretek / Box sizes</h5>
    <table>
        <thead>
        <tr>
            <th>Méret / Size</th>
            <th class="textalignright">Darab / Pcs</th>
        </tr>
        </thead>
        <tbody>
        {foreach $meretek as $_meret => $_db}
            <tr>
                <td>{$_meret}</td>
                <td class="textalignright">{$_db}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    <h5>Dobozok / Boxes</h5>
    <table>
        <thead>
        <tr>
            <th>Doboz / Box</th>
            <th>Méret / Size</th>
            <th class="textalignright">Nettó kg / Net kg</th>
            <th class="textalignright">Bruttó kg / Gross kg</th>
            <th class="textalignright">Térfogat m³ / Volume m³</th>
        </tr>
        </thead>
        <tbody>
        {foreach $dobozlista as $_doboz}
            <tr>
                <td>{$_doboz.dobozszam}</td>
                <td>{$_doboz.meret}</td>
                <td class="textalignright">{number_format($_doboz.nettosuly, 2, ',', ' ')}</td>
                <td class="textalignright">{number_format($_doboz.bruttosuly, 2, ',', ' ')}</td>
                <td class="textalignright">{number_format($_doboz.terfogat, 3, ',', ' ')}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td>Összesen / Total</td>
            <td>{count($dobozlista)} doboz / boxes</td>
            <td class="textalignright">{number_format($osszesen.nettosuly, 2, ',', ' ')}</td>
            <td class="textalignright">{number_format($osszesen.bruttosuly, 2, ',', ' ')}</td>
            <td class="textalignright">{number_format($osszesen.terfogat, 3, ',', ' ')}</td>
        </tr>
        </tfoot>
    </table>

    <h5>Dobozok tartalma / Box contents</h5>
    <table>
        <thead>
        <tr>
            <th>Doboz / Box</th>
            <th>Cikkszám / Item no.</th>
            <th>Termék / Product</th>
            <th>Méret / Size</th>
            <th class="textalignright">Darab / Pcs</th>
            <th class="textalignright">Termékből összesen / Product total</th>
        </tr>
        </thead>
        <tbody>
        {foreach $tartalom as $_sor}
            <tr>
                <td>{$_sor.dobozszam}</td>
                <td>{$_sor.cikkszam|escape}</td>
                <td>{$_sor.nev|escape}</td>
                <td>{$_sor.meret|escape}</td>
                <td class="textalignright">{$_sor.mennyiseg*1}</td>
                <td class="textalignright">{$_sor.termekosszesen*1}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <p>{$printdatum}</p>
{/block}
