<?php
/*
functies voor het uitvoeren van sql $query
sqldelete: verwijderen van een rij in de db
sqlupdate: updaten gegevens in db
sqlinsert: gegvens toevoegen in de database
sqlselect: selecteren van gegevens uit de database
sqlexists: tellen van het aantal resultaten op basis van een select string met count
wanneer er wel wat voorkomt op basis van de aanvraag krijgt de gebruiker een true ociinternaldebug
 */
function sqldelete($query,$values){
  $pdo = db_connect();
  $stmt = $pdo->prepare($query);
  $stmt->execute($values);
  $deleted = $stmt->rowCount();
  $pdo = null;
}
function sqlupdate($query,$values){
  $pdo = db_connect();
  $pdo->prepare($query)->execute($values);
    $pdo = null;
}
function sqlinsert($query,$values){
  $pdo = db_connect();
  $stmt = $pdo->prepare($query);
  $stmt->execute($values);
  $pdo = null;
}
function sqlselect($query,$values){
  $pdo = db_connect();
  $stmt = $pdo->prepare($query);
  $stmt->execute($values);
  $select = array();
  while ($row = $stmt->fetch()){
    $select[] = $row;
  }
  $pdo = null;
  return $select;
}
function sqlexists($query,$values){
  $pdo = db_connect();
  $stmt = $pdo->prepare($query);
  $stmt->execute($values);
  $select = array();
  $result = $stmt->fetchColumn();
  $pdo = null;
  if($result > 0){
    return true;
  }
  else{
    return false;
  }
}




function executesql($action,$query, $values){
  if ($action == "delete"){
    sqldelete($query,$values);
  }
  if ($action == "update"){
    sqlupdate($query,$values);
  }
  if ($action == "insert"){
    sqlinsert($query,$values);
  }
  if ($action == "select"){
    return sqlselect($query,$values);
  }
}

 ?>
