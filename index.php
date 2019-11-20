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

        <?php
        include __DIR__ . '/includes/DB.php';
        include __DIR__ . '/includes/sql.php';
        include __DIR__ . '/includes/card.php';
        ?>

    </head>

    <body>
        <!-- Navigation -->
        <?php include 'includes/header.php'; ?>

        <!-- Page Content -->
        <div class="container-fluid bg-wwi">

            <div class="container bg-wwi">
                <div class="row">
                    <!-- sidebar -->
                    <?php
                    include 'includes/sidebar.php';

                    include 'includes/carousel.php';
                    ?>
                    <!-- /.col-lg-9 -->

                    <div class="row">

                        <?php Getcard("sale", 3, 0, 0); ?>

                        <?php Getcard("homepage", "", 0, 0) ?>

                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.container -->
        </div>
    </div>
    <!-- Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
