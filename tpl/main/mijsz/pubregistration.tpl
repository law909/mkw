<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="description" content="{$seodescription|default}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--link type="text/css" rel="stylesheet" href="/themes/main/mijsz/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/bootstrap-responsive.min.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/jquery.slider.min.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/magnific-popup.css"-->
    <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/mijsz.css">
    <link type="text/css" rel="stylesheet" href="/themes/main/mijsz/style.css">
</head>
<body>
<div class="container whitebg">
	<div class="row">
		<div class="span10 offset1">
			<div class="form-header">
				<h2>{t('Regisztráció')}</h2>
			</div>
			<div id="adatmodositasTabbable">
                <form id="FiokAdataim" class="form-horizontal" action="/prsave" method="post">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="VezeteknevEdit">{t('Név')}*:</label>
                            <div class="controls">
                                <input id="VezeteknevEdit" name="vezeteknev" type="text" class="input-medium" placeholder="{t('vezetéknév')}" value="{$user.vezeteknev}" required>
                                <input id="KeresztnevEdit" name="keresztnev" type="text" class="input-medium" placeholder="{t('keresztnév')}" value="{$user.keresztnev}" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="EmailEdit">{t('Email')}*:</label>
                            <div class="controls">
                                <input id="EmailEdit" name="email" type="email" class="input-large" value="{$user.email}" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="TelefonEdit">{t('Telefon')}:</label>
                            <div class="controls">
                                <input id="TelefonEdit" name="telefon" type="text" class="input-large" value="{$user.telefon}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiNevEdit">{t('Név')}:</label>
                            <div class="controls">
                                <input id="SzamlazasiNevEdit" name="nev" type="text" class="input-xlarge" placeholder="{t('számlázási név')}" value="{$user.nev}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiAdoszamEdit">{t('Adószám')}:</label>
                            <div class="controls">
                                <input id="SzamlazasiAdoszamEdit" name="adoszam" type="text" class="input-medium" placeholder="{t('adószám')}" value="{$user.adoszam}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="SzamlazasiCimEdit">{t('Számlázási cím')}:</label>
                            <div class="controls">
                                <input id="SzamlazasiCimEdit" name="irszam" type="text" class="input-mini" placeholder="{t('ir.szám')}" value="{$user.irszam}">
                                <input name="varos" type="text" class="input-medium" placeholder="{t('város')}" value="{$user.varos}">
                                <input name="utca" type="text" class="input-large" placeholder="{t('utca')}" value="{$user.utca}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="MiotaJogazikEdit">{t('Mióta jógázol Iyengar metódus szerint (év)')}:</label>
                            <div class="controls">
                                <input id="MiotaJogazikEdit" name="mijszmiotajogazik" type="text" class="input-large" value="{$user.mijszmiotajogazik}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="MiotaTanitEdit">{t('Mióta tanítasz Iyengar metódusban (év)')}:</label>
                            <div class="controls">
                                <input id="MiotaTanitEdit" name="mijszmiotatanit" type="text" class="input-large" value="{$user.mijszmiotatanit}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="MemberBesidesHuEdit">{t('Más Iyengar Szövetségbeli tagságod')}:</label>
                            <div class="controls">
                                <input id="MemberBesidesHuEdit" name="mijszmembershipbesideshu" type="text" class="input-large" value="{$user.mijszmembershipbesideshu}" title="{t('Írd be vesszővel elválasztva a szövetségek nevét')}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="BusinessEdit">{t('Stúdiód neve')}:</label>
                            <div class="controls">
                                <input id="BusinessEdit" name="mijszbusiness" type="text" class="input-large" value="{$user.mijszbusiness}" title="{t('Írd be a stúdiód nevét, ha van')}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="WeboldalEdit">{t('Weboldal')}:</label>
                            <div class="controls">
                                <input id="WeboldalEdit" name="honlap" type="text" class="input-large" value="{$user.honlap}" title="{t('Írd be a weboldaladat vagy a stúdiód weboldalát')}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="MunkahelyneveEdit">{t('Munkahelyed')}:</label>
                            <div class="controls">
                                <input id="MunkahelyneveEdit" name="munkahelyneve" type="text" class="input-large" value="{$user.munkahelyneve}" title="{t('Írd be a munkahelyed nevét, ha van')}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="FoglalkozasEdit">{t('Foglalkozás')}:</label>
                            <div class="controls">
                                <input id="FoglalkozasEdit" name="foglalkozas" type="text" class="input-large" value="{$user.foglalkozas}" title="{t('Írd be a foglalkozásodat')}">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn okbtn">{t('Mentés')}</button>
                        </div>
                    </fieldset>
                </form>
			</div>
		</div>
	</div>
</div>
</body>
</html>