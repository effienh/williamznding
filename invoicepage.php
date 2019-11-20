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

        <!-- font awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="css/shop-homepage.css" rel="stylesheet">
    </head>
    <body>
        <?php
        include __DIR__ . '/includes/header.php';
        #hier worden alle relevante data opgehaald uit de database.
        $deliveryinfo = sqlselect("select customername, DeliveryAddressLine1, DeliveryPostalCode from customers where customerID in (select customerID from orders where OrderID = ?)", array(sanitize_post("OrderID", "number")));
        $orderinfo = sqlselect("select orderid, orderdate, CustomerID from orders where orderID = ?", ARRAY(sanitize_post("OrderID", "number")));
        $city = sqlselect("select cityname from cities where cityID  = (select deliverycityid from customers where customerid in (select customerID from orders where OrderID = ?))", array(sanitize_post("OrderID", "number")));
        $payinfo = sqlselect("select sum(recommendedretailprice) total, quantity from orderlines o Inner join stockitems s on s.stockitemid = o.stockitemid where orderID = ?", array(sanitize_post("OrderID", "number")));
        //pre_r($payinfo);
        $shippingcost = 0.00;

        //pre_r($_SESSION);
        ?>
        <!-- hieronder wordt op een gestructuurerde wijze alle data getoond op de
        pagina,-->
        <div class="container-fluid bg-wwi" style="min-height: 665px">
            <div class="container rounded bg-light">
                <?php
                #zolang het factuurnummer bestaat en bij de meegegeven klant
                #hoort, kan de klant hem zien. anders niet.
                if (isset($_POST['OrderID']) and isset($_SESSION['CustomerID']) and $_SESSION['CustomerID'] == $orderinfo[0]['CustomerID']) {
                    $orderid = sanitize_post('OrderID', 'number');
                    ?>
                    <h1>Factuur: <?php print($orderid); ?></h1>
                    <br>
                    <div class="row">
                        <div class="col-3">
                            <h3>Bezorggegevens</h3>
                            <p>Naam: <?php echo $deliveryinfo[0]['customername']; ?></p>
                            <p>Adres: <?php echo $deliveryinfo[0]['DeliveryAddressLine1'] ?></p>
                            <p>Postcode: <?php echo $deliveryinfo[0]['DeliveryPostalCode'] . ' ' . $city[0]['cityname'] ?></p>
                            <p>Land: <?php echo "Nederland" ?></p>
                        </div>
                        <div class="col-3 offset-6">
                            <h3 >Bestelgegevens</h3>
                            <p >bestelnummer: <?php echo $orderinfo[0]['orderid'] ?></p>
                            <p >besteldatum: <?php echo $orderinfo[0]['orderdate'] ?></p>
                        </div>
                    </div>
                    <div class="rounded border border-dark">
                        <h3 class="text-center">Betaalgegevens</h3>
                        <hr>
                        <p>
                        <div class="row">
                            <a class="col-2">
                                betaalmethode</a>
                            <a class="col-2 offset-8">
                                <?php
                                $paymentmethod = sqlselect("select paymentmethodname from paymentmethods where paymentmethodID = (select paymentmethodid from customertransactions where invoiceid = (select invoiceid from invoices where orderid = ?));", array($orderid))[0];
                                echo $paymentmethod['paymentmethodname'];
                                ?></a>
                        </div>
                        </p>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>totaal productkosten</p>
                                <p>verzendkosten</p>
                                <br>
                                <p>totaal</p>
                            </div>
                            <div class="col-2 offset-4">

                                <?php
                                $amount = 0;
                                if (isset($payinfo[0]['total'])) {
                                    $amount = $payinfo[0]['total'] * $payinfo[0]['quantity'];
                                }
                                ?>
                                <p><?php echo '€' . $amount ?></p>
                                <p><?php echo '€' . $shippingcost ?></p>
                                <br>
                                <p><?php echo '€' . number_format($amount + $shippingcost, 2) ?></p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <a href="orders.php">
                                <button class="btn btn-info">Vorige pagina</button>
                            </a>
                        </div>
                        <div class="col-2 offset-8">
                            <button class="btn btn-info" onclick="window.print();return false;">print de pagina</button>
                        </div>
                    </div>
                    <br>
                    <?php
                } else {
                    header("Location: ./orders.php");
                }
                ?>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <?php include __DIR__ . '/includes/footer.php'; ?>
        </footer>
    </body>

</html>
