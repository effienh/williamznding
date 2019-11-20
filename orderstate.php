<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include __DIR__ . '/includes/check.php'; ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WWI de internationale groothandel</title>

         <?php//Bootstrap core CSS ?>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

         <?php//font awesome ?>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">

         <?php//Custom styles for this template ?>
        <link href="css/shop-homepage.css" rel="stylesheet">
    </head>
    <body>

         <?php//Includes Header.php en order.php ?>
        <?php
        include __DIR__ . '/includes/header.php';
        include __DIR__ . '/includes/order.php';
        ?>

        <?php
        $orderid = sanitize_post('OrderID', 'number');
        if (sqlexists("SELECT count(*) FROM orders WHERE OrderID = ? AND CustomerID = ?", array($orderid, $_SESSION['CustomerID']))&& $_SESSION['idvalid']) {
            $order = orderdetails($orderid);
            $orderdetails = $order['orderdetails'];
            ?>

             <?php//Geeft de orderstatus weer als de gebruiker ingelogd is. Returned naar index.php wanneer niet ingelogd. ?>
            <div class="container-fluid bg-wwi">
                <div class='container bg-wwi'>
                    <div class="row">
                        <?php //include __DIR__ . '/includes/sidebar.php' ?>
                        <div class="col col-lg-12 bg-light rounded">
                            <br>
                            <div class="container-fluid">
                                <div class="row">
                                    <a href="javascript:history.go(-1)" class="btn btn-info btn-sm" role="button">vorige pagina</a>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <table class="table table-borderless">

                                            <tbody>
                                                <tr>
                                                    <th scope="row">Order</th>
                                                    <td><?php echo $orderdetails['OrderID']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">status</th>
                                                    <td><?php echo orderstate($orderdetails['ExpectedDeliveryDate'], $orderdetails['PickingCompletedWhen']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">verwachte leverdatum</th>
                                                    <td><?php echo $orderdetails['ExpectedDeliveryDate']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="row justify-content-md-center">

                                    <?php
                                    orderitems($order['orderitems']);
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
              header('Location: ' . 'index.php');
        }
        include __DIR__ . '/includes/footer.php';
        ?>
    </body>

</html>
