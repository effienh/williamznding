<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WWI de internationale groothandel</title>

    <?php// Bootstrap core CSS ?>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <?php// font awesome ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">
    <?php// Custom styles for this template ?>
    <link href="css/shop-homepage.css" rel="stylesheet">

    <?php
    include __DIR__ . '/includes/DB.php';
    include __DIR__ . "/includes/sql.php";
    ?>

</head>

<body class="bg-wwi">
<?php// Navigation ?>
<?php include __DIR__ . '/includes/header.php'; ?>

<?php// Page Content ?>
<div class="container-fluid bg-wwi">

    <div class="container bg-light rounded">
        <div class="row">
                <?php// login form ?>
                <?php
                if(!($_SESSION['idvalid'])){
                    ?>
                    <div class="col-lg-12 bg-light rounded">
                        <br>
                        <h1>Inloggen</h1>
                        <br>
                        <center><form action= "includes/check.php" method="POST">
                          <div class="from-row">
                              <label>  Email:</label>
                                <input type="email" placeholder= "email" class="form-control col-4" name= "log-email" required><br>
                              <label>  Wachtwoord:</label>
                                <?php
                                if(sanitize_get('login', 'string') == 'fail'){
                                    ?>
                                    <input type="password" aria-describedby="log-passhelp" placeholder= "password" name="log-password" class="form-control col-4" required>
                                    <small id="log-passhelp" class="form-text" >
                                        Uw wachtwoord en/of emailadres is onjuist.
                                    </small>
                                    <br><br>
                                    <?php
                                }else{
                                    ?>
                                    <input type="password" placeholder= "password" name="log-password" class="form-control col-4" required> <br><br>
                                    <?php
                                }
                                ?>
                                <input type="submit" value="Login" class= "btn btn-success"><br><br>
                            </div></form></center>
                            <center><a href="forgot.php">Wachtwoord vergeten?</a></center>
                    </div>
                    <?php
                }else{
                ?>
                <div class='col-lg-12 bg-light rounded'>
                    <br>
                    <h1>U bent ingelogd</h1>
                    <br>
                    <center><a href='includes/check.php?Logout=TRUE' class="btn btn-success" ><h3>Uitloggen?</h3></a></center>
                    <br><br>
                    <?php } ?>
                </div>
                <?php// /.row ?>
            </div>
        <?php// /.container ?>
      </div>
      <?php// /.container fluid ?>
</div>
<?php// Footer ?>
<?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
