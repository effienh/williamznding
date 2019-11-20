<?php

//functie voor het verzenden van een mail
//hiervoor is de php.ini aangepast bij de smtp server en de sendmail.ini bij de smtp configuratie
function sendmail($to){

    if(sqlexists("SELECT count(*) from customers where Email=?",array($to))){
      $resetcode = generateRandomString(20);
      mail("$to","Uw authenticatie key","Uw authenticatie key \r\n\r\n $resetcode \r\n\r\n Als u dit niet herkend dan hoeft u niks te doen","From:apegohn3oghiadgnlas@gmail.com");
      return $resetcode;
    }else{
      sleep(3);
      return "nan";
    }

}
//functie voor het genereren voor een willekeurige authenticatie string om in te kunnen loggen als de gebruiker zijn/haar wachtwoord vergeten is
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
