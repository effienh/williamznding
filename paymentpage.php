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
        if (null !== sanitize_post('ProcessID', 'number') and sanitize_post('ProcessID', 'number') == 2) {
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
                    <div class="progress-bar bg-light progress-bar-striped" role="progressbar" style="width:25%">
                        <a class="nav-link" style="color:black">Stap 4<br>Factuur bewaren</a>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <!-- dit is de dummy betaalpagina. hier klik je een betaalmethode aan
            en geef je mee of de betaling succesvol is of niet. het meegeven gaat
            via een post.-->
            <div class="container rounded bg-light">
                <h1>Betaalpagina</h1>
                <br>
                <div class="col-4 offset-4">
                    <image src="./images/ideal-copy.jpg" class="d-block img-fluid"/>
                    <br>
                    <h3>Te betalen bedrag:<?php echo ' ' . $_SESSION['shopping_cart']['totalcost']; ?></h3>
                    <br>
                    <div class="row">
                        <form action="orderstatus.php" class="col-12" method="post">
                            <div class="col-8 offset-4">
                                <input type="radio" name="paytype" value="5" checked="checked">IDeal</input>
                                <input type="radio" name="paytype" value="6">Paypal</input>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-5">
                                    <button type="submit" name="payment" value="1" class="btn btn-info">Betaling succesvol</button>
                                </div>
                                <div class="col-5 offset-2">
                                    <button type="submit" name="payment" value="0" class="btn btn-info">Betaling mislukt</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
            </div>
            <?php
        } else {
            header('Location: cart.php');
        }
        include __DIR__ . '/includes/footer.php';
        ?>
    </body>
</html>
