<?php

include_once __DIR__ . '/DB.php';
include_once __DIR__ . '/sql.php';
include_once __DIR__ . '/captcha.php';
include __DIR__ . '/functions.php';

if (!isset($CustomerName)) {//customername klaarzetten voor de rest van het document
    $CustomerName = sanitize_post('ac-voornm', 'string') . " " . sanitize_post('ac-achternm', 'string');
}
//------
//controleren van de inloggegevens die doorgestuurd worden uit login.php
//------
if (isset($_POST['log-email']) && isset($_POST['log-password'])) {
    if (sanitize_post('log-email', 'email') == "" || sanitize_post('log-password', 'chars') == "") {
        Logout('log-fail');
    }
    /* boolean */ $valid = customerlogin(sanitize_post('log-email', 'email'), sanitize_post('log-password', 'chars'));
    if ($valid) {
        $_SESSION['idvalid'] = TRUE;
        $_SESSION['email'] = sanitize_post('log-email', 'email');
        $_SESSION['CustomerID'] = sqlselect('SELECT CustomerID FROM customers WHERE Email = ?', array(sanitize_post('log-email', 'email')))[0]['CustomerID'];
        header('Location: ../login.php');
    } else {//als het password niet een correcte match is met de email dan word dit terug gestuurd.
        Logout('log-fail');
    }
} elseif (!isset($_POST['log-email']) && isset($_POST['log-password'])) {
    Logout('log-fail');
} elseif (isset($_POST['log-email']) && !isset($_POST['log-password'])) {
    Logout('log-fail');
}

//------
//kijken waar de gevevens vandaan komen, als het van registration komt dan pas dit gebruiken.
//------
//hierbij ook controleren of de email wel er in staat en of de wachtwoorden wel gelijk zijn
if (isset($_POST['ac-Email']) && isset($_POST['register']) && !isset($_POST['change'])) {
    if (!isset($_POST['mail-exists']) && !isset($_POST['pwshort']) && isset($_POST['register'])) {
        if (sqlexists("SELECT count(*) FROM customers WHERE Email = ?", array(sanitize_post('ac-Email', 'email'))) ||
                customercheck(array('CustomerName' => $CustomerName)) || !$human ||
                sanitize_post('ac-ww', 'chars') === sanitize_post('ac-wwc', 'chars') ||
                !(sanitize_post('naw-province', 'number') > 54 && sanitize_post('naw-province', 'number') < 65)) {
            printform("mail");
        }
        if (strlen(sanitize_post('ac-ww', 'chars')) < 7) {
            printform("password");
        }
    }

// zet de sessie email naar de gegeven email uit de form (login.php)
    $_SESSION['email'] = sanitize_post('ac-Email', 'email');
    // roep functie customer insert aan met gegevens uit de from (Registration.php)

    customerinsert(array('CustomerName' => $CustomerName,
        'PhoneNumber' => sanitize_post('naw-telnr', 'number'), 'Adress' => sanitize_post('naw-address', 'string'),
        'PostalCode' => sanitize_post('naw-postcd', 'string'), 'CityName' => sanitize_post('naw-plaatsnm', 'string'), 'StateProvinceID' => sanitize_post('naw-province', 'number'),
        'Email' => sanitize_post('ac-Email', 'email'), 'HashPassword' => password_hash(sanitize_post('ac-ww', 'chars'), PASSWORD_DEFAULT)));

// zodra alle gegevens in de databasse staan word de sessie goedgekeurd en de gebruker ingelogd

    $_SESSION['idvalid'] = TRUE;
    $_SESSION['CustomerID'] = $GLOBALS['newcustomerID'];
    header('Location: ../login.php');
}
//------
//kijken waar de gevevens vandaan komen, als het van customerdata komt dan pas dit gebruiken.
//------
if (isset($_POST['change'])) {
    //in de if controleren of wachtwoord niet leeg is en bij de gebruiker een match is, of de wachtwoorden die veranderd moeten worden wel gelijk zijn,
    //kijken of er geen gekke waardes zijn ingevuld bij de province id, kijken of het email adres al in de database staat en of dat email adres niet de zefde is als die er bij deze gebruiker stond.
    if (sanitize_post('ac-ww', 'chars') == "" &&
            sanitize_post('chg-ww', 'chars') != "" &&
            (!sqlexists('SELECT COUNT(*) FROM customers WHERE Email = ?', array(sanitize_post('ac-Email', 'email'))) ||
            sqlexists('SELECT COUNT(*) FROM customers WHERE Email = ? and CustomerID = ?', array(sanitize_post('ac-Email', 'email'), $_SESSION['CustomerID']))) &&
            customerlogin($_SESSION['email'], sanitize_post('chg-ww', 'chars')) &&
            (sanitize_post('naw-province', 'number') > 53 && sanitize_post('naw-province', 'number') < 66)
    ) {
        //de nieuwe stad gegevens uit de database halen en anders veranderen waar nodig
        $deliverypostalid = citycheck(array('CityName' => sanitize_post('naw-plaatsnm', 'string'), 'StateProvinceID' => sanitize_post('naw-province', 'number')));
        //gegevens invoeren uit het mee gegevens formulier.
        sqlupdate('UPDATE customers SET CustomerName = ?, DeliveryCityID = ?, PostalCityID = ?, PhoneNumber = ?, DeliveryAddressLine1 = ?,
      DeliveryPostalCode = ?, Email = ? WHERE CustomerID = ?', array($CustomerName, $deliverypostalid, $deliverypostalid, sanitize_post('naw-telnr', 'number'), sanitize_post('naw-address', 'string'),
            sanitize_post('naw-postcd', 'string'), sanitize_post('ac-Email', 'email'), $_SESSION['CustomerID']));

        header('Location: ../customerdata.php');
    }
    //in de if controleren of wachtwoord niet leeg is en bij de gebruiker een match is, of de wachtwoorden die veranderd moeten worden wel gelijk zijn,
    //kijken of er geen gekke waardes zijn ingevuld bij de province id, kijken of het email adres al in de database staat en of dat email adres niet de zefde is als die er bij deze gebruiker stond.
    if (sanitize_post('ac-ww', 'chars') != "" && sanitize_post('chg-ww', 'chars') != "" &&
            sanitize_post('ac-ww', 'chars') == sanitize_post('ac-wwc', 'chars') &&
            customerlogin($_SESSION['email'], sanitize_post('chg-ww', 'chars')) &&
            (sanitize_post('naw-province', 'number') > 53 && sanitize_post('naw-province', 'number') < 66) &&
            (!sqlexists('SELECT COUNT(*) FROM customers WHERE Email = ?', array(sanitize_post('ac-Email', 'email'))) ||
            sqlexists('SELECT COUNT(*) FROM customers WHERE Email = ? and CustomerID = ?', array(sanitize_post('ac-Email', 'email'), $_SESSION['CustomerID'])))
    ) {
        //alle gegevens updaten die zijn mee gegeven
        sqlupdate('UPDATE customers SET CustomerName = ?, PhoneNumber = ?, DeliveryAddressLine1 = ?, DeliveryPostalCode = ?, Email = ?, HashedPassword = ? WHERE CustomerID = ?', array($CustomerName, sanitize_post('naw-telnr', 'number'), sanitize_post('naw-address', 'string'), sanitize_post('naw-postcd', 'string'),
            sanitize_post('ac-Email', 'email'), password_hash(sanitize_post('ac-ww', 'chars'), PASSWORD_DEFAULT), $_SESSION['CustomerID']));
        //de nieuwe stad gegevens uit de database halen en anders veranderen waar nodig
        $deliverypostalid = citycheck(array('CityName' => sanitize_post('naw-plaatsnm', 'string'), 'StateProvinceID' => sanitize_post('naw-province', 'number')));
        $_SESSION['email'] = sanitize_post('ac-Email', 'email'); // email in de sessie opnieuw zetten voor als deze word mee gegeven
        header('Location: ../customerdata.php'); // terug sturen naar customerdata als alles in de database is ingevoerd
    } else {
        printform('change');
    }
}
//------
//kijken waar de gegevens vandaan komen, als het van paypage komt dan pas dit gebruiken
//------
if (isset($_POST['payprocessing']) && sanitize_post('payed', 'string') == 'TRUE') {
    foreach ($_SESSION['shopping_cart'] as $value) {
        $quant = sqlselect("SELECT QuantityOnHand FROM stockitems WHERE StockItemID = ?", array($value['id']))[0];
        if ($value['quantity'] > $quant) {
            $quantoverflow['id'] = $value['id'];
            $quantoverflow['value'] = $quant['QuantityOnHand'];
            $_SESSION['shopping_cart'][$quantoverflow['id']]['quantity'] = $quant['QuantityOnHand'];
        }
    }
    orderinsert(array('shopping_cart' => $_SESSION['shopping_cart']));
}
//------
//als er ergens een logout word mee gegeven aan check.php dan word de gebruiker uitgelogd
//------
if (sanitize_get('Logout', 'string') == 'TRUE') {
    Logout('index');
}

if ($_SESSION['idvalid'] == TRUE) {//alleen ingaan als er een gebruiker is ingelogd
    //controleren of de gebruiker niet door administrators is verwijderd, zo wel dan meteen uitloggen bij het inladen van een nieuwe pagina
    if (!sqlexists("SELECT count(*) FROM customers WHERE customerID = ? AND Email IS NOT NULL", array($_SESSION['CustomerID']))) {
        Logout('index');
    }
}
?>
