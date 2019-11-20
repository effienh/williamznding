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


        <?php include 'includes/header.php'; ?>
        <!-- hier wordt het procesbalkje aangemaakt die laat zien hoever je in
        het bestelproces zit.-->
        <div class="col-lg-10 offset-1">
            <div class="progress" style= "height:40px">
                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                    <a class="nav-link" href="index.php" style="color:black">Stap 1<br>Producten uitkiezen</a>
                </div>
                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                    <a class="nav-link" style="color:black">Stap 2<br>Bestelling gegevens controleren</a>
                </div>
                <div class="progress-bar bg-light progress-bar-striped" role="progressbar" style="width:25%">
                    <a class="nav-link" style="color:black">Stap 3<br>Betalen</a>
                </div>
                <div class="progress-bar bg-light progress-bar-striped" role="progressbar" style="width:25%">
                    <a class="nav-link" style="color:black">Stap 4<br>Factuur bewaren</a>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="container rounded bg-light">
            <?php
            #hier wordt er gecheckt of je ingelogd bent of een tijdelijk account
            #hebt. als geen van beide waar is ga je naar de NAW gegevens pagina.
            if ($_SESSION['idvalid'] == 1 or isset($_SESSION['tempid'])) {
                #als je van de cart afkomt en een gevulde winkelwagen hebt kom je
                #verder, anders moet je naar de winkelwagen.
                if ((isset($_POST['ProcessID']) and sanitize_post('ProcessID', 'number') == 1 or isset($_SESSION['changes'])) or isset($_SESSION['tempid']) and isset($_SESSION['shopping_cart'])) {
                    $_SESSION['changes'] = null;
                    if ($_SESSION['idvalid'] == 1) {
                        $id = $_SESSION['CustomerID'];
                    } else {
                        $id = $_SESSION['tempid'];
                    }
                    ?>
                    <br>
                    <h1>Gegevens controleren</h1>
                    <br>
                    <?php
                    //hier wordt alle te controleren informatie getoond.
                    $customerinfo = sqlselect('select CustomerName,DeliveryCityID, DeliveryAddressLine1, PostalPostalCode, Email  from customers where CustomerID = ?', array($id))[0];
                    $customercity = sqlselect('select CityName from cities where cityid = ?', array($customerinfo['DeliveryCityID']))[0];
                    print('<div class="col-4">');
                    print('<p>Naam: ' . $customerinfo['CustomerName'] . '</p>');
                    print('<p>Adres: ' . $customerinfo['DeliveryAddressLine1'] . '</p>');
                    print('<p>Postcode: ' . $customerinfo['PostalPostalCode'] . ' ' . $customercity['CityName'] . '</p>');
                    print('<p>E-mail: ' . $customerinfo['Email'] . '</p>');
                    print('<p>levertijd: ' . $_SESSION['deliverytime'] . '');
                    print('<br>');
                    print('</div>');

                    $_SESSION['shopping_cart']['totalcost'] = 0;
                    foreach ($_SESSION['shopping_cart'] as $value) {
                        if ($value != 'totalcost') {
                            $totalproduct = $value['quantity'] * $value['price'];
                            print('<hr><br>');
                            print('<div class="row">');
                            print('<div class="col-6">');
                            print($value['name'] . ' x ' . $value['quantity'] . '</div>');
                            print('<div class="col-6"><p class="float-right">Prijs: € ' . $totalproduct . '</div>');
                            print('<br>');
                            print('</div>');
                            $_SESSION['shopping_cart']['totalcost'] += $totalproduct;
                        }
                    }
                    print('<hr><div><p>Totaal: € ' . $_SESSION['shopping_cart']['totalcost'] . '</p></div>');
                    //print('<pre>');
                    //print_r($customerinfo);
                    //print('</pre>');
                } else {
                    header('Location: cart.php');
                }
            } else {
                header('location: tempuser.php');
            }
            ?>
            <br>
            <div class="row">
                <Div class="col-2">
                    <a href="addressdata.php">
                        <button class="btn btn-info">levergegevens wijzigen</button>
                    </a>
                </div>
                <div class="col-2 offset-8">
                    <form action="paymentpage.php" method="post">
                        <input type="hidden" name="ProcessID" value="2">
                        <button type="submit" class="btn btn-info float-right">Bevestigen</button>
                    </form>
                </div>
            </div>
            <br>
        </div>
        <?php
        include 'includes/footer.php';
        ?>
    </body>
</html>
