
<?php //include __DIR__ . '/check.php';  ?>

<?php include_once __DIR__ . '/check.php'; ?>

<?php //link naar de style van het winkelwagen icoontje ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid row">
        <a href="index.php" class="col-2">
            <img src="Images/wide world importers logo.png" alt="Italian Trulli">
        </a>
        <div class="col-8"><?php //zoekbalk aanmaken  ?>
            <form class="form-inline" method="get" action="search.php">
                <div class="input-group col-12">
                    <input class="form-control col-11" type="text" name="search" placeholder="Zoeken..." autocomplete="off" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">zoek</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-2">
            <ul class="navbar-nav ml-auto float-right">
                <?php
                if ($_SESSION['idvalid'] === FALSE) { // als de gebruiker niet ingelogd is kan die inloggen of een account registreren,
                    ?>
                    <li class='nav-item'>
                        <a class='nav-link' href='login.php'>Inloggen</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='Registration.php'>Registreren</a>
                    </li>
                    <?php
                } else {// als de gebruiker wel ingelogd is krijgt deze toegang tot de gebruikers omgeving door middel van een dropdown menu
                    // deze dropdown menu bevat linkjes naar een pagina om gegevens te wijzigen, de bestellingen te zien en uit te loggen.
                    ?>
                    <li class='nav-item'>
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="customerdata.php">Gegevens Wijzigen</a>
                            <a class="dropdown-item" href="orders.php">Mijn Bestellingen</a>
                            <a class="dropdown-item" href="includes/check.php?Logout=TRUE">Uitloggen</a>
                        </div>
                    </li>

                    <?php
                }
                ?>
                <li class="nav-item">
                    <a href="cart.php">
                        <div class="fas fa-shopping-cart fa-2x">
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="bg-wwi"><br></div>
<?php //pre_r($_SESSION); ?>
