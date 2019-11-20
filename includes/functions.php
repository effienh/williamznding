<?php
//zet de globale variabelen voor de queries
if (!isset($GLOBALS['ValidFrom'])) {
    $GLOBALS['ValidFrom'] = date("Y-m-d H:i:s");
}
if (!isset($GLOBALS['ValidTo'])) {
    $GLOBALS['ValidTo'] = "9999-12-31 23:59:59";
}
if (!isset($CustomerName)) {//zet customername voor queries
    $CustomerName = sanitize_post('ac-voornm', 'string') . " " . sanitize_post('ac-achternm', 'string');
}

//geven van waarschuwing wanneer er fouten voorkomen
function warning($string) {
    if ($string == "bunny") {
        return "
    <link rel='stylesheet' href='bunny/css/style.css'>
    <div id='messenger'></div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/zepto/1.2.0/zepto.min.js'></script>
    <script  src='bunny/js/index.js'></script>";
    } else {
        echo "<div class='alert alert-danger' role='alert'> $string</div>";
    }
    echo "</body>";
}

function customercheck($valarr) {//controleren of de customernaam al bestaat
    if (sqlexists("SELECT count(*) FROM customers WHERE CustomerName = ?", array($valarr['CustomerName']))) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function Logout($location) {//klant uitloggen zodra de functie is aangeroepen.
    if ($location == 'log-fail') {
        $_SESSION['idvalid'] = FALSE;
        header('location: ../login.php?login=fail');
    } elseif ($location == 'index') {
        $_SESSION['idvalid'] = FALSE;
        session_destroy();
        echo "<script language='JavaScript' type='text/javascript'>
        setTimeout('window.history.go(-1)',5);
        </script>"; // terug gaan naar de vorige pagina zodra de gebruiker is uitgelogd
    }
}

function citycheck($valarr) {//controleren of de stad al in de database staat, krijgt plaatsnaam om te inserten mee en returnt het nieuwe cityID
    foreach (sqlselect("SELECT CityName, CityID FROM cities WHERE CityName = ?", array($valarr['CityName'])) as $row) {
        if ($row['CityName'] == $valarr['CityName']) {
            return $row['CityID'];
        }
    }
    // nieuwe cityid aanmaken als de stad nog niet bestaat in de database
    $newcityID = sqlselect("SELECT MAX(CityID) as max FROM cities", array())[0]['max'] + 1;

    sqlinsert("INSERT INTO cities (CityID, CityName, StateProvinceID, LastEditedBy, ValidFrom, ValidTo) VALUES(?,?,?,?,?,?)", array($newcityID, $valarr['CityName'],
        $valarr['StateProvinceID'], 1, $GLOBALS['ValidFrom'], $GLOBALS['ValidTo']));
    return $newcityID;
}

function customerinsert($valarr) {//alle gegevens inserten zodra de gebruiker de gegevens doorgeeft
    $cityID = citycheck($valarr); // eerst kijken of de stad al in de database staat en de Cityid terug geven
    $GLOBALS['newcustomerID'] = 1 + sqlselect("SELECT MAX(CustomerID) as max FROM customers", array())[0]['max'];
//alle gegevens uit de functie aanroep halen + dingen standaard toevoegen
    sqlinsert("INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID,
    AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL,
    DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy, ValidFrom, ValidTo, Email, HashedPassword)
   VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array($GLOBALS['newcustomerID'], $valarr['CustomerName'], $GLOBALS['newcustomerID'], 10, 1, 1, $cityID, $cityID, $GLOBALS['ValidFrom'], 0, 0, 0, 7, $valarr['PhoneNumber'], $valarr['PhoneNumber'],
        "WideWorldImporters.shop", $valarr['Adress'], $valarr['PostalCode'], "NO BOX", $valarr['PostalCode'], 1, $GLOBALS['ValidFrom'], $GLOBALS['ValidTo'], $valarr['Email'], $valarr['HashPassword']));
}

function sanitize_post($input, $type) {//bij fucntie aanroep checken wat het type is en daarop de input sanitizen
    if ($type == "email") {
        return trim(filter_input(INPUT_POST, $input, FILTER_SANITIZE_EMAIL));
    }
    if ($type == "chars") {
        return trim(filter_input(INPUT_POST, $input, FILTER_SANITIZE_SPECIAL_CHARS));
    }
    if ($type == "string") {
        return trim(filter_input(INPUT_POST, $input, FILTER_SANITIZE_STRING));
    }
    if ($type == "number") {
        return trim(filter_input(INPUT_POST, $input, FILTER_SANITIZE_NUMBER_INT));
    }
    return "";
}

function sanitize_get($input, $type) {//bij fucntie aanroep checken wat het type is en daarop de input sanitizen
    if ($type == "email") {
        return trim(filter_input(INPUT_GET, $input, FILTER_SANITIZE_EMAIL));
    }
    if ($type == "chars") {
        return trim(filter_input(INPUT_GET, $input, FILTER_SANITIZE_SPECIAL_CHARS));
    }
    if ($type == "string") {
        return trim(filter_input(INPUT_GET, $input, FILTER_SANITIZE_STRING));
    }
    if ($type == "number") {
        return trim(filter_input(INPUT_GET, $input, FILTER_SANITIZE_NUMBER_INT));
    }
    return "";
}

function printform($type) {//zodra gegevens fout worden ingevoerd in registration of gegevens aanpassen alles terug geven
    if ($type !== "change") {
        ?>
        <form name='reg' action='../Registration.php' method='POST'>

            <?php if ($type == "mail") { ?>
                <input type="hidden" name="exists" value="TRUE">
                <?php
            }
            if ($type == "password") {
                ?>
                <input type="hidden" name="pwshort" value="TRUE">
            <?php } ?>

            <input type="hidden" name="ac-voornm" value="<?php echo sanitize_post('ac-voornm', 'string'); ?>">
            <input type="hidden" name="ac-achternm" value="<?php echo sanitize_post('ac-achternm', 'string'); ?>">
            <input type="hidden" name="ac-Email" value="<?php echo sanitize_post('ac-Email', 'email'); ?>">
            <input type="hidden" name="naw-address" value="<?php echo sanitize_post('naw-address', 'string'); ?>">
            <input type="hidden" name="naw-postcd" value="<?php echo sanitize_post('naw-postcd', 'string'); ?>">
            <input type="hidden" name="naw-telnr" value="<?php echo sanitize_post('naw-telnr', 'number'); ?>">
            <input type="hidden" name="naw-plaatsnm" value="<?php echo sanitize_post('naw-plaatsnm', 'string'); ?>">
        </form>
    <?php } else { ?>
        <form name='reg' action='../customerdata.php' method='POST'>
            <input type="hidden" name="exists" value="TRUE">
        </form>
    <?php } ?>
    <script type='text/javascript'>
        document.reg.submit();//het formulier mee geven en dus terug naar de pagina
    </script>
    <?php
}

function pre_r($array) {//een array netjes uitprinten
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function orderinsert($valarr) { // winkelwagentje word mee gegeven
    if ($_SESSION['idvalid'] == 1) {
        $id = $_SESSION['CustomerID'];
    } else {
        $id = $_SESSION['tempid'];
        unset($_SESSION['tempuser']);
        unset($_SESSION['tempid']);
    }

    $deliverydate = new DateTime('tomorrow');
    $time = $_SESSION['deliverytime'] - 1;
    if ($time != 0) {
        $deliverydate->add(new DateInterval('P' . $time . 'D'));
    } elseif (date('G') > 17) { // als het na 5 uur is word de volgene dag er na aangehouden voor levertijd
        $deliverydate->add(new DateInterval('P1D'));
    }
    $deliverydate = $deliverydate->format("Y-m-d H:i:s"); //formatteren van Date naar string
    $neworderid = 1 + sqlselect("SELECT OrderID as max FROM orders ORDER BY OrderID DESC LIMIT 1", array())[0]['max'];
    $neworderlineid = 1 + sqlselect("SELECT OrderLineID  as max FROM orderlines ORDER BY OrderLineID DESC LIMIT 1", array())[0]['max'];

    sqlinsert("INSERT INTO orders (OrderID, CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen)
  VALUES(?,?,?,?,?,?,?,?,?)", array($neworderid, $id, 1, 1, $GLOBALS['ValidFrom'], $deliverydate, 1, 1, $GLOBALS['ValidFrom']));
    foreach ($valarr['shopping_cart'] as $key => $value) {//alle producten uit de shopping cart halen en checken of er wel genoeg in stock is
        if ($key != 'totalcost') {
            $quantityinfo = sqlselect("SELECT QuantityOnHand FROM stockitemholdings WHERE StockItemID = ?", array($value['id']))[0];
            if ($value['quantity'] <= $quantityinfo['QuantityOnHand']) {
                $pickedquantity = $value['quantity'];
            } else {
                $pickedquantity = $quantityinfo['QuantityOnHand'];
            }
            $value['name'] = "'" . $value['name'] . "'"; //pruductnaam klaarmaken om in de query te doen met quotes
            $iteminfo = sqlselect('SELECT * FROM stockitems WHERE stockitemid = ?', array($value['id']))[0];
            sqlinsert("INSERT INTO orderlines VALUES(?,?,?,?,?,?,?,?,?,?,?,?)", array($neworderlineid, $neworderid, $value['id'], $value['name'], 7, $value['quantity'], $iteminfo['UnitPrice'],
                $iteminfo['TaxRate'], $pickedquantity, $deliverydate, 1, $GLOBALS['ValidFrom']));
            $neworderlineid++;
        }
    }
    return $neworderid;
}

function customerlogin($email, $password) {
    $mailindatabase = FALSE;
    $maildb = sqlselect("SELECT Email FROM customers WHERE Email = ?", array($email));
    foreach ($maildb as $row) {
        if ($row['Email'] == $email) {
            $mailindatabase = TRUE;
        }
    }

    if ($mailindatabase) {
        $dbpassword = sqlselect("SELECT CONVERT(HashedPassword USING utf8) AS HashPassword FROM customers WHERE Email = ?", array($email));
        if (password_verify($password, $dbpassword[0]['HashPassword'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}
?>
