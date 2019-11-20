<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WWI de internationale groothandel</title>

        <?php// Bootstrap core CSS ?>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <?php// Custom styles for this template ?>
        <link href="css/shop-homepage.css" rel="stylesheet">
    </head>
    <body>
        <?php
        // include header.php
        include __DIR__ . '/includes/header.php';

//kijkt naar idvalid of deze TRUE is en haalt CustomerID, CityName StateProvinceID en StateProvinceName uit de database
        if ($_SESSION['idvalid'] == TRUE)
         {
            $CustData = sqlselect("SELECT * FROM customers WHERE CustomerID = ?", array($_SESSION['CustomerID']))[0];
            $Name = explode(" ", $CustData['CustomerName']);
            $Cityname = sqlselect("SELECT CityName FROM cities WHERE CityID = (SELECT PostalCityID FROM customers WHERE CustomerID = ?)", array($_SESSION['CustomerID']))[0];
            $Province = sqlselect('SELECT StateProvinceID, StateProvinceName FROM stateprovinces WHERE StateProvinceID =
              (SELECT StateProvinceID FROM cities WHERE CityID =
              (SELECT PostalCityID FROM customers WHERE CustomerID = ?))',
              array($_SESSION['CustomerID']))[0];
            ?>

            <div class="container-fluid bg-wwi">
                <div class='container bg-wwi'>
                    <font color="white">
                    <div class="container">
                    	<?php//En vult de form automatisch in met de juiste gegevens behalve het wachtwoord.
                       		 // form redirect naar check.php ?>
                        <form class="form" action="includes/check.php" method= "POST">

                            <h1><b>Mijn Gegevens</b></h1>
                            <br>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="aanhef">Aanhef:</label>
                                    <select class="form-control">
                                        <option> Heer </option>
                                        <option> Mevrouw </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ac-voornm">Voornaam:</label>
                                    <input type="text" class="form-control" id="ac-voornm" placeholder="Voornaam" name="ac-voornm"
                                    value="<?php foreach($Name as $names){if($names != end($Name)){echo $names." ";} } ?>"  required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ac-achternm">Achternaam:</label>
                                    <input type="text" class="form-control" id="ac-achternm" placeholder="Achternaam" name="ac-achternm" value="<?php echo end($Name); ?>"  required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ac-Email">E-mailadres:</label>
                                <input type="Email" class="form-control" id="ac-Email" placeholder="Email" name="ac-Email" value="<?php echo $CustData['Email']; ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ac-ww">Nieuwe Wachtwoord:</label>
                                        <input class="form-control" aria-describedby="passhelp" id="ac-ww" type="password" placeholder="Wachtwoord" name="ac-ww">
                                        <small id="passhelp" class="form-text" style='color: #f8f9fa;'>
                                            Uw wachtwoord moet minstens 8 characters bevatten. (leeg laten voor niet wijzigen)
                                        </small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ac-wwc">Nieuwe Wachtwoord Bevestiging:</label>
                                    <input class="form-control" id="ac-wwc" type="password" placeholder="Wachtwoord" name="ac-wwc">
                                </div>
                            </div>
                            <?php if (isset($_POST['exists'])) {
                                ?>
                                <div class='form-text container' style='color: #dc3545;'>
                                    <center>Uw gegevens zijn onjuist.</center>
                                </div>
                            <?php }
                            ?>

                            <br>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="naw-street">Straatnaam + Huisnummer:</label>
                                    <input class="form-control" id="naw-street" type="text" placeholder="Straat" name="naw-address" value="<?php echo $CustData['DeliveryAddressLine1']; ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="naw-postcd">Postcode:</label>
                                    <input class="form-control" id="naw-postcd" type="text" placeholder="Postcode" name="naw-postcd" value="<?php echo $CustData['DeliveryPostalCode']; ?>"  required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="naw-telnr">Telefoonnummer:</label>
                                    <input class="form-control" id="naw-telnr" type="number" placeholder="Telefoonnummer" name="naw-telnr" value="<?php echo $CustData['PhoneNumber']; ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="naw-plaatsnm">Plaatsnaam:</label>
                                    <input class="form-control" id="naw-plaatsnm" type="text" placeholder="Plaatsnaam" name="naw-plaatsnm" value="<?php echo $Cityname['CityName']; ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="naw-province">Provincie:</label>
                                    <select class="form-control" id= "naw-province" name="naw-province" required>
                                        <?php
                                        echo "<option value='" . $Province['StateProvinceID'] . "'>" . $Province['StateProvinceName'] . "</option>";
                                        foreach (sqlselect("SELECT StateProvinceName, StateProvinceID FROM stateprovinces WHERE CountryID = 153", array()) as $province) {
                                            if ($Province['StateProvinceID'] !== $province['StateProvinceID']) {
                                                echo "<option value='" . $province['StateProvinceID'] . "'>" . $province['StateProvinceName'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="chg-ww">Bevestig wachtwoord:</label>
                                    <input class="form-control" id="chg-ww" type="password" placeholder="Bevestig wachtwoord" name="chg-ww" required>
                                </div>
                            </div>
                            <input class="btn btn-success" type="submit" value="Wijzig" name="change">


                        </form>
                    </div>
                    </font>
                </div>
            </div>
        </div>
    </body>
    <footer>
        <?php
        include __DIR__ . '/includes/footer.php';
    } else {
    	//redirect naar index
        header("Location: index.php");
    }
    ?>
</footer>
</html>
