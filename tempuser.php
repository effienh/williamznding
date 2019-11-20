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


        <?php
        include 'includes/header.php';
        if ($_SESSION['idvalid'] != 1) {
            $errormessage = 0;
            if (isset($_POST)) {
                //pre_r($_POST);
            }
            #als de gebruiker alle velden geldig heeft ingevuld komt hij in de if
            #statement terecht.
            if (isset($_POST['confirm']) && isset($_POST['name']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['postalcode']) && isset($_POST['email']) && isset($_POST['deliverytime'])) {
                #hier worden alle meegegeven gegevens eerst gecontroleerd.
                $name = "'" . sanitize_post('name', 'string') . "'";
                $address = sanitize_post('address', 'string');
                $postalcode = sanitize_post('postalcode', 'string');
                $city = sanitize_post('city', 'string');
                $province = sanitize_post('province', 'number');
                #als een klant een value probeert meetegeven die niet in de vaste
                #dropdown menu zit krijgt hij een leuk filmpje te zien.
                if (($province < 55 and $province > 64) or $province == "") {
                    header("Location: http://goo.gl/7ZH1DP");
                }
                $city = citycheck(array("CityName" => $city, "StateProvinceID" => $province));
                $email = sanitize_post('email', 'email');
                #als een klant een value probeert meetegeven die niet in de vaste
                #dropdown menu zit krijgt hij een leuk filmpje te zien.
                if (sanitize_post('deliverytime', 'number') == "" or sanitize_post('deliverytime', 'number') < 1 or sanitize_post('deliverytime', 'number') > 7) {
                    header("Location: http://goo.gl/7ZH1DP");
                }
                $_SESSION['deliverytime'] = sanitize_post('deliverytime', 'number');
                $newcustomerid = sqlselect("SELECT customerid from customers order by customerid desc", ARRAY())[0];
                $namecheck = sqlselect("SELECT customername from customers where customername = ?", array($name));
                $mailcheck = sqlselect("SELECT CustomerID, CONVERT(HashedPassword using utf8) as HashedPassword from customers where email = ?", array($email));
                $newcustomerid['customerid'] += 1;
                //print($newcustomerid['customerid']);
                if (isset($mailcheck[0]['CustomerID'])) {
                    if (strlen($mailcheck[0]['HashedPassword']) > 1) {
                        $_SESSION['account exists'] = 1;
                        header('Location: login.php');
                    } else {
                        $_SESSION['tempuser'] = true;
                        $_SESSION['tempid'] = $mailcheck[0]['CustomerID'];
                        header("Location: paypage.php");
                    }
                    #als alles goedgekeurd is word er een tijdelijke gebruiker aangemaakt.
                } elseif ($name != "" and $address != "" and $postalcode != "" and $email != "" and ! isset($namecheck[0]['customername']) and $province > 54 and $province < 65) {
                    sqlinsert("INSERT into customers values (?,?,?,10,null,1,null,1,?, ?, null, current_date(),0,0,0,7,1,1,null,null,'WideWorldImporters.shop',?,null,?,null,'NO BOX',NULL,?,1,current_date(),'9999-12-31 23:59:59',?,null)", array($newcustomerid['customerid'], $name, $newcustomerid['customerid'], $city, $city, $address, $postalcode, $postalcode, $email));
                    $_SESSION['tempuser'] = true;
                    $_SESSION['tempid'] = $newcustomerid['customerid'];
                    header("Location: paypage.php");
                } elseif (isset($namecheck[0]['customername'])) {
                    $errormessage = 2;
                } else {
                    $errormessage = 1;
                }
            }
            ?>
            <div class="container rounded bg-light">
                <h1>NAW gegevens invullen</h1>
                <br>
                <?php
                if ($errormessage == 1) {
                    print('geen van de velden leeg laten en een geldige waarde invoeren AUB');
                }
                if ($errormessage == 2) {
                    print('gelieve een andere naam in te vullen AUB.');
                }
                ?>
                <div class="row">
                    <form class="col-12" method="post" action="tempuser.php">
                        <div class="col-6 offset-3 text-center">
                            naam:
                            <br>
                            <input class="form-control" type="text" name="name" value="">
                        </div>
                        <br>
                        <div class="col-6 offset-3 text-center">
                            adres:
                            <input class="form-control" type="text" name="address" value="">
                        </div>
                        <br>
                        <div class="col-6 offset-3 text-center">
                            postcode:
                            <input class="form-control" type="text" name="postalcode" value="">
                        </div>
                        <br>
                        <div class="col-6 offset-3 text-center">
                            stad:
                            <input class="form-control" type="text" name="city" value="">
                        </div>
                        <br>
                        <div class="col-6 offset-3 text-center">Provincie:
                            <select class="form-control" name="province">
                                <?php
                                // alle provincies van nederland uit de database halen CountryID = 153.
                                foreach (sqlselect("SELECT StateProvinceName, StateProvinceID FROM stateprovinces WHERE CountryID = 153", array()) as $province) {
                                    echo "<option value='" . $province['StateProvinceID'] . "'>" . $province['StateProvinceName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="col-6 offset-3 text-center">
                            E-mail:
                            <input class="form-control" type="email" name="email"  value="">
                        </div>
                        <br>
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
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-info col-12">bevestigen</button>
                    </form>
                </div>
                <br>
            </div>
            <?php
            include 'includes/footer.php';
        } else {
            header("Location: cart.php");
        }
        ?>
    </body>
</html>
