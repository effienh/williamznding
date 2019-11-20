<?php
session_start();
if(!isset($_SESSION['idvalid'])){
  $_SESSION['idvalid'] = FALSE;
}
//functie voor het verbinden van een database
function db_connect() {
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, // emulatie uit voor prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //erros tonen in de vorm van uitzonderingen
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //standaard feth naar een array
    ];
    $db = new PDO('mysql:host=localhost;dbname=wideworldimporters;charset=utf8mb4', 'root', '', $options);
    return $db;
}
if (!isset($_SESSION['perpage'])){
  $_SESSION['perpage']=30;
}
?>
