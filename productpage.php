<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include __DIR__ . '/includes/sql.php'; ?>
        <?php include __DIR__ . '/includes/DB.php'; ?>
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
        include __DIR__ . '/includes/card.php';
        include_once __DIR__ . '/includes/check.php';
        ?>

        <?php
        $idget = sanitize_get('productid', 'number');
        if (sqlexists("SELECT count(*) FROM stockitems WHERE StockItemID = ?", array($idget))) {
            try {
                // $stmtv haalt random 3 producten op
                $stmtv = sqlselect('SELECT StockItemName, MarketingComments, RecommendedRetailPrice, StockItemID FROM stockitems ORDER BY RAND() LIMIT 3', array());
                // $product haalt specifiek een product op uit de database met de $idget
                $product = sqlselect("SELECT S.StockItemName, S.MarketingComments, S.RecommendedRetailPrice, S.StockItemID, S.SearchDetails,S.IsChillerStock, QuantityOnHand FROM stockitems AS S JOIN stockitemholdings AS SH ON S.StockitemID = SH.StockitemID WHERE S.StockItemID = ?", array($idget))[0];
                ?>

                <div class="container-fluid bg-wwi">
                    <div class='container bg-wwi'>
                        <div class="row">
                            <?php include __DIR__ . '/includes/sidebar.php' ?>
                            <div class="row col-8 ml-2">
                                <div class="card">
                                    <div>
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <ol class="carousel-indicators">
                                                <?php
                                                //haalt een statische stockimage op die 900x400 weergeeft (grijs)
                                                if (!file_exists("./images/stockimages/" . $idget . "/")) {
                                                    print('<li data-target="#carouselExampleIndicators" data-slide-to="0" active></li>');
                                                    ?>
                                                </ol>
                                                <div class="carousel-inner">
                                                    <?php
                                                    $arrows = false;
                                                    print('<div class="carousel-item active"><img class="d-block w-100" src="http://placehold.it/900x400" alt="first slide"></div>');
                                                    $count = 1;
                                                }
                                                else {
                                                //haalt verschillende foto's op van de carousel producten.
                                                    $arrows = true;
                                                    $imagemap = "./images/stockimages/" . $product['StockItemID'] . "";
                                                    $images = array_diff(scandir($imagemap), array('..', '.'));
                                                    $count = count($images);
                                                    for ($i = 0; $i < $count; $i++) {
                                                        if ($i == 0) {
                                                            $slideactivity = 'class="active"';
                                                        } else {
                                                            $slideactivity = null;
                                                        }
                                                        print('<li data-target="#carouselExampleIndicators" data-slide-to="' . $i . '" ' . $slideactivity . '></li>');
                                                    }
                                                    ?>
                                                    </ol>
                                                    <div class="carousel-inner">
                                                        <?php
                                                        $activity = 'active';
                                                        $slide = 1;

                                                        foreach ($images as $row) {
                                                            $sourcelink = $imagemap . '/' . $row;
                                                            if ($slide == 1) {
                                                                print('<div class="carousel-item active"><img class="d-block w-100" src="' . $sourcelink . '" alt="slide ' . $slide . '"></div>');
                                                            } else {
                                                                print('<div class="carousel-item"><img class="d-block w-100" src="' . $sourcelink . '" alt="slide ' . $slide . '"></div>');
                                                            }
                                                            $slide++;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <!-- vorige knop in de carousel op de productpagina -->
                                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <!-- volgende knop in de carousel -->
                                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                          <!-- van regel 110 tot 132 toont informatie van een specifiek product. -->
                                            <h3 class="card-title"><?php echo $product['StockItemName'] . "<br>"; ?></h3>

                                            <h4> <?php echo "€" . $product["RecommendedRetailPrice"] . "<br><br>"; ?> </h4>
                                            <p class="card-text"><?php
                                            echo $product['SearchDetails'] . '.<br><br>';
                                            if ($product['IsChillerStock'] == 1) {
                                                $rows = 6;
                                            } else {
                                                $rows = 12;
                                            }
                                            print('<div class="row">');
                                            // van regel 121 tot 127 bekijkt of het product op voorraad is en geeft een passende melding.
                                            if ($product['QuantityOnHand'] <= 0) {
                                                print('<a class="col-' . $rows . '">Niet op voorraad</a>');
                                            } elseif ($product['QuantityOnHand'] < 100) {
                                                print('<a class="col-' . $rows . '">Vooraad: ' . $product["QuantityOnHand"] . '</a>');
                                            } else {
                                                print('<a class="col-' . $rows . '">Voorraad: 99+</a>');
                                            }
                                            if ($product['IsChillerStock'] == 1) {
                                                $temp = sqlselect("SELECT Temperature FROM ColdRoomTemperatures WHERE ColdRoomSensorNumber = 1", array());
                                                print('<a class="col-' . $rows . '">Temperatuur: <span id="temp">' . $temp[0]['Temperature'] . '</span> °C</a>');
                                            }
                                            print('</div>');

                                            if (!$product['QuantityOnHand'] == 0) {
                                                print("<form method = 'get' action = 'cart.php'><br><button class='btn btn-primary float-right' type = 'submit' value = '" . $idget . "' name='productid'>naar winkelwagen</button>");
                                            }
                                                    ?></p>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <?php
                                        // laat random producten zien onderaan de productpagina.
                                        foreach ($stmtv as $row) {
                                            CardTemplate(null, $row['StockItemName'], $row['RecommendedRetailPrice'], $row['StockItemID']);
                                        }
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>

                                </div>
                            </div>
                            <br>

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
<?php include __DIR__ . '/js/xhr.php'; ?>
