<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WWI de internationale groothandel</title>

        <?php // Bootstrap core CSS ?>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <?php // Custom styles for this template ?>
        <link href="css/shop-homepage.css" rel="stylesheet">
    </head>
    <body>
        <?php
        include __DIR__ . '/includes/header.php';
        #zolang de klant ingelogd is mag hij op deze pagina komen,
        #anders moet hij inloggen.
        if ($_SESSION['idvalid'] !== TRUE) {
            header('Location: login.php');
        }
        ?>
        <!-- hieronder worden alle factures en orderstatussen van bestellingen
        van de ingelogde klant getoond.-->
        <div class="container-fluid bg-wwi" style="min-height: 665px">
            <div class="container rounded bg-light">
                <br>
                <h1>Mijn Bestellingen</h1>
                <br>
                <HR>
                <div class="row">
                    <?php
                    $facturen = sqlselect("SELECT OrderID, OrderDate FROM orders WHERE CustomerID = ? ORDER BY OrderDate DESC", array($_SESSION['CustomerID'])); //array($_SESSION['CustomerID']));
                    $amountarray = count($facturen);
                    if ($amountarray < 4) {
                        $amountside = 1;
                    } elseif ($amountarray < 20) {
                        $amountside = 2;
                    } else {
                        $amountside = 4;
                    }
                    $bal = 1;

                    $orderamount = 0;
                    //$collength = 12 / $amountside;
                    foreach ($facturen as $factuur) {
                        ?>
                        <div class='row col-12'>
                            <div class='col-8'><?php echo $factuur['OrderDate'] . ": #" . $factuur['OrderID']; ?>
                            </div>
                            <form class='col-2' action=orderstate.php method='post'>
                                <input type="hidden" name="OrderID" value="<?php echo $factuur['OrderID'] ?>">
                                <button type='submit' class='btn btn-info'>orderstatus bekijken</button>
                            </form>
                            <form class='col-2' action='invoicepage.php' method='post'>
                                <input type="hidden" name="OrderID" value="<?php echo $factuur['OrderID'] ?>">
                                <button type='submit' class='btn btn-info'>factuur bekijken</button>
                            </form>
                            <div class="col-12"><hr></div>
                        </div>
                        <?php
                        $orderamount++;
                    }
                    if ($orderamount == 0) {
                        ?>
                        <div class='row col-12'>
                            <div class='col-12'>
                                <p class="text-center"><h1>Er is nog niets besteld</h1></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <br>
            </div>
        </div>

        <?php // Footer ?>
        <footer>
            <?php include __DIR__ . '/includes/footer.php'; ?>
        </footer>
    </body>

</html>
