<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seodescription|default}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/mgr.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace/style.css">
</head>
<body>
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>Regisztráció</h2>
			</div>
			<div id="adatmodositasTabbable">
                <form id="FiokAdataim" class="form-horizontal" action="/prsave" method="post">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="VezeteknevEdit">Név*:</label>
                            <div class="controls">
                                <input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" placeholder="vezetéknév" value="{$user.vezeteknev}" required>
                                <input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" placeholder="keresztnév" value="{$user.keresztnev}" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="EmailEdit">Email*:</label>
                            <div class="controls">
                                <input id="EmailEdit" name="email" type="email" class="input-large" value="{$user.email}" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="TelefonEdit">Telefon:</label>
                            <div class="controls">
                                <input id="TelefonEdit" name="telefon" type="text" class="input-large" value="{$user.telefon}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiNevEdit">Név:</label>
                            <div class="controls">
                                <input id="SzamlazasiNevEdit" name="nev" type="text" class="input-xlarge" placeholder="számlázási név" value="{$user.nev}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiAdoszamEdit">Adószám:</label>
                            <div class="controls">
                                <input id="SzamlazasiAdoszamEdit" name="adoszam" type="text" class="input-medium" placeholder="adószám" value="{$user.adoszam}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiCimEdit">Számlázási cím:</label>
                            <div class="controls">
                                <input id="SzamlazasiCimEdit" name="irszam" type="text" class="input-mini" placeholder="ir.szám" value="{$user.irszam}">
                                <input name="varos" type="text" class="input-medium" placeholder="város" value="{$user.varos}">
                                <input name="utca" type="text" class="input-large" placeholder="utca" value="{$user.utca}">
                                <input name="hazszam" type="text" class="input-mini" placeholder="házszám" value="{$user.hazszam}">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn okbtn">Mentés</button>
                        </div>
                    </fieldset>
                </form>
			</div>
		</div>
	</div>
</div>
</body>
</html>