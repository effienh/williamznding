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
        include __DIR__ . '/includes/header.php';
        //als er betaald is en je hebt een geregistreerd of niet geregistreed account
        #ga je de if in.
        if ((isset($_POST['payment']) AND ( sanitize_post('payment', 'number') == 1 OR sanitize_post('payment', 'number') == 0)) and $_SESSION['idvalid'] == 1 or isset($_SESSION['tempid'])) {
            ?>
            <div class="col-lg-10 offset-1">
                <div class="progress" style= "height:40px">
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                        <a class="nav-link" href="index.php" style="color:black">Stap 1<br>Producten uitkiezen</a>
                    </div>
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                        <a class="nav-link" style="color:black">Stap 2<br>Bestelling gegevens controleren</a>
                    </div>
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                        <a class="nav-link" style="color:black">Stap 3<br>Betalen</a>
                    </div>
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width:25%">
                        <a class="nav-link" style="color:black">Stap 4<br>Factuur bewaren</a>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="container rounded bg-light">
                <?php
                #als er betaald is via IDeal of PayPal dan kom je de if in. anders
                #kom je op de betaling mislukt pagina.
                if (sanitize_post('payment', 'number') == 1 and ( sanitize_post('paytype', 'number') == 5 or sanitize_post('paytype', 'number') == 6)) {
                    if ($_SESSION['idvalid'] == 1) {
                        $id = $_SESSION['CustomerID'];
                    } elseif (isset($_SESSION['tempid'])) {
                        $id = $_SESSION['tempid'];
                    }
                    #hier bereiden we wat variabelen voor om te gebruiken voor de
                    # 5 insert statements en 1 update statement.
                    $dryitems = 0;
                    $chilleritems = 0;
                    foreach ($_SESSION['shopping_cart'] as $key => $value) {
                        if ($key != 'totalcost') {
                            $drycheck = sqlselect("SELECT ischillerstock from stockitems where stockitemid = ?", array($key))[0];
                            if ($drycheck['ischillerstock'] == 0) {
                                $dryitems++;
                            } else {
                                $chilleritems++;
                            }
                        }
                    }
                    #hier gebeurt grotendeels van de inserts.
                    $orderid = orderinsert(array('shopping_cart' => $_SESSION['shopping_cart']));
                    $customerinfo = sqlselect("select * from customers where customerid = ?", array($id))[0];
                    $newinvoiceid = 1 + sqlselect("select invoiceid from invoices order by invoiceid desc limit 1", array())[0]['invoiceid'];
                    $paymentmethod = sanitize_post('paytype', 'number');
                    sqlinsert('insert into invoices values (?, ?, ?, ?,3,2007, 2001, 20, 8, current_date(), null, 0, null,null,?,null,?,?,null,null,null, current_date(),null, 1, current_date())', array($newinvoiceid, $id, $id, $orderid, $customerinfo['DeliveryAddressLine1'], $dryitems, $chilleritems));
                    foreach ($_SESSION['shopping_cart'] as $key => $value) {
                        if ($key != 'totalcost') {
                            $quantity = sqlselect("select quantityonhand from stockitemholdings where stockitemid = ?;", array($key))[0];
                            $newquantity = $quantity['quantityonhand'] - $value['quantity'];
                            sqlupdate("update stockitemholdings set quantityonhand = ? where stockitemid = ?", array($newquantity, $key));
                            $newinvoicelineid = 1 + sqlselect("select invoicelineid from invoicelines order by invoicelineid desc limit 1", array())[0]['invoicelineid'];
                            $itemcheck = sqlselect("SELECT * from stockitems where stockitemid = ?", array($key))[0];
                            $tax = $itemcheck['TaxRate'] / 100;
                            $taxamount = $value['quantity'] * $itemcheck['UnitPrice'] * $tax;
                            $lineprofit = ($itemcheck['RecommendedRetailPrice'] * $value['quantity']) - ($value['quantity'] * ($itemcheck['UnitPrice'] * $value['quantity'] + $tax));
                            sqlinsert('insert into invoicelines values (?, ?, ?, ?, 7, ?, ?, ?, ?, ?, ?, 1, current_date())', array($newinvoicelineid, $newinvoiceid, $key, $itemcheck['StockItemName'], $value['quantity'], $itemcheck['UnitPrice'], $itemcheck['TaxRate'], $taxamount, $lineprofit, $itemcheck['RecommendedRetailPrice'] * $value['quantity']));
                        }
                    }
                    $customertransactionid = 1 + sqlselect('select customertransactionid from customertransactions order by customertransactionid desc limit 1', array())[0]['customertransactionid'];
                    $total = $_SESSION['shopping_cart']['totalcost'];
                    $totalnotax = $total / 121 * 100;
                    $totaltax = $total - $totalnotax;
                    sqlinsert("insert into customertransactions values ( ?, ? , 1, ? , ?, current_date(), ?, ?, ?, 0.00, current_date(), 1 , 1, current_date())", array($customertransactionid, $id, $newinvoiceid, $paymentmethod, $totalnotax, $totaltax, $total));
                    ?>
                    <h1>Betaling succesvol!</h1>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?php if ($_SESSION['idvalid'] == 1) { ?>
                        <center><h3><a href="orders.php">
                                    klik hier
                                </a>
                                om uw orderstatus en factuur te bekijken.
                            </h3>
                        </center>
                        <br>
                        <?php
                    }
                    //$_SESSION['shopping_cart'] = null;


                    if (isset($_SESSION['tempuser'])) {
                        unset($_SESSION['tempid']);
                        unset($_SESSION['tempuser']);
                    }
                    unset($_SESSION['shopping_cart']);
                    unset($_SESSION['totalcost']);

                    //sqlupdate($query, $values);
                } elseif (sanitize_post('payment', 'number') == 0 and ( sanitize_post('paytype', 'number') != 5 or sanitize_post('paytype', 'number') != 6)) {
                    //print_r($_POST);
                    ?>
                    <h1>Betaling mislukt!</h1>
                    <?php
                }
            } else {
                header('Location: cart.php');
            }
            ?>
        </div>
        <?php include __DIR__ . '/includes/footer.php';
        ?>
    </body>
</html>
