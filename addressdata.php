<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WWI de internationale groothandel</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/shop-homepage.css" rel="stylesheet">

        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
    </head>
    <body>

        <!-- op deze pagina kunnen klanten hun adresgegevens nog tijdens het bestelproces aanpassen. -->
        <?php
        include 'includes/header.php';
        #als de gebruiker niet ingelogd is wordt hij geredirect naar de login pagina.
        if ($_SESSION['idvalid'] != 1) {
            header("Location: login.php");
        }
        #hier wordt na het invullen van de pagina gekeken of de meegegeven velden
        #volledig en correct zijn ingevuld.
        $check = "nothing";
        if (isset($_POST['check'])) {
            if (sanitize_post('deliverytime', 'int') < 1 or sanitize_post('deliverytime', 'int') > 7 or sanitize_post("deliverytime", 'int') == "") {
                $_POST['deliverytime'] = 1;
            }
            $name = sanitize_post('name', 'string');
            $address = sanitize_post('address', 'string');
            $postalcode = sanitize_post('postalcode', 'string');
            $email = sanitize_post('email', 'email');
            $namecheck = sqlselect("SELECT customerid from customers where customername = ? and  customerid != ?", array($name, $_SESSION['CustomerID']));
            $_SESSION['deliverytime'] = sanitize_post('deliverytime', 'number');

            if (isset($namecheck[0])) {
                print("Kies AUB een andere naam.");
            } elseif ($name != "" and $address != "" and $postalcode != "" and $email != "") {
                $check = "yes";
                //
                sqlupdate("update customers set CustomerName = ?, DeliveryAddressLine1 = ?, DeliveryPostalCode = ?, Email = ? where CustomerID = ?", array($name, $address, $postalcode, $email, $_SESSION['CustomerID']));
                $_SESSION['changes'] = true;
                header("Location: paypage.php");
            } else {
                $check = "no";
            }
        }
        #hier wordt de gebruikers informatie uit de database opgehaald en getoond.
        $customerinfo = sqlselect("SELECT * from customers where CustomerID = ?", array($_SESSION['CustomerID']))[0];
        ?>
        <div class="container bg-light rounded">
            <h1>adresgegevens wijzigen</h1>
            <BR>
            <?php
            if ($check == "no") {
                print ('<h2>fout bij het invullen van gegevens, probeer het opnieuw.</h2>');
            }
            if ($check == "yes") {
                print ('<h2>Gegevens succesvol aangepast.</h2>');
            }
            ?>
            <div class="row">
                <form class="col-12" method="post" action="addressdata.php">
                    <div class="col-6 offset-3 text-center">
                        naam:
                        <br>
                        <input class="form-control" type="text" name="name" value="<?php echo $customerinfo['CustomerName'] ?>">
                    </div>
                    <div class="col-6 offset-3 text-center">
                        adres:
                        <input class="form-control" type="text" name="address" value="<?php echo $customerinfo['DeliveryAddressLine1'] ?>">
                    </div>
                    <div class="col-6 offset-3 text-center">
                        postcode:
                        <input class="form-control" type="text" name="postalcode" value="<?php echo $customerinfo['DeliveryPostalCode'] ?>">
                    </div>
                    <div class="col-6 offset-3 text-center">
                        E-mail:
                        <input class="form-control" type="email" name="email"  value="<?php echo $customerinfo['Email'] ?>">
                    </div>
                    <div class="col-2 offset-5 text-center" >levertijd in dagen:
                        <select class="form-control" name="deliverytime">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </select>
                    </div>
                    <br>
                    <input type="hidden" name="check" value="1">
                    <input type="hidden" name="ProcessID" value="1">
                    <button type="submit" class="btn btn-info col-12">wijzigen</button>

                </form>

            </div>
            <br>
        </div>
        <?php
        include 'includes/footer.php';
        ?>
    </body>
</html>