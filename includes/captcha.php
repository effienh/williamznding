<?php
// check via google of de geposte string klopt met de nodige string in van google
$secretKey  = "6Lfsl30UAAAAALogniDa3do3ibz5qt9R9LPsTDFx";

$human = false;
if(isset($_POST)){
    if(isset($_POST['captcha-response']) && !empty($_POST['captcha-response'])){
        // Get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['captcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success){
            //Contact form submission code goes here ...
            $human = true;
        }
    }
}


?>
