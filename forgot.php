<!DOCTYPE html>
<html lang="en">

<head>
	<?php //Style voor de base bootstrap ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WWI de internationale groothandel</title>

    <?php
		//Bootstrap core CSS
		?>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <?php //Font Awesome ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" crossorigin="anonymous">
    <?php //Custom styles for this template ?>
    <link href="css/shop-homepage.css" rel="stylesheet">

    <?php

    //Include DB.php / SQL.php en leest het veld email uit $_SESSION
    include __DIR__ . '/includes/DB.php';
    include __DIR__ . "/includes/sql.php";
    $_SESSION['email'] = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

    ?>
  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
</head>

<body class="bg-wwi">
<?php //navigation
include __DIR__ . '/includes/header.php';

//als email geset is, include dan mail.php
if (isset($_POST['email'])){
  $mail = sanitize_post('email','email');
  include __DIR__ . '/includes/mail.php';


/*
	 als de email van de gebruiker overheen komt met de email die in de database staat
	 dan wordt de gebruiker geauthenticeerd en wordt er een token in de mail verstuurd

	 als de gebruiker het verkeerde wachtwoord ingevoerd heeft wordt hij niet geauthenticeerd
	 en wordt er ook geen mail verstuurd.
*/


  if($human && sqlexists("SELECT count(*) from customers where Email = ?",array($mail)) && strlen(sqlselect("SELECT CONVERT(HashedPassword USING utf8) AS HashPassword FROM customers WHERE Email = ?", array($mail))[0]['HashPassword'])>1){
			$_SESSION['auth'] = sendmail($mail);
	    $_SESSION['tokenmail'] = $mail;
  }else{

		$lit = mt_rand(250,400)/100;
    sleep($lit);
    $_SESSION['auth'] = false;
  }
/*
	Als de key geset is en komt overheen met de authenticatie
	Update de database met het nieuwe gehashte wachtwoord
*/
}
elseif (isset($_POST['key']) && isset($_SESSION['auth'])){
  $key = sanitize_post('key','string');
  $passstring = sanitize_post('password', 'chars');
  $pass = password_hash($passstring,PASSWORD_DEFAULT);
  if($key == $_SESSION['auth'] && strlen($passstring) > 7){
    sqlupdate('UPDATE customers SET HashedPassword = ? where Email = ?',array($pass,$_SESSION['tokenmail']));
  }
  unset($_SESSION['auth']);
}
?>

 <?php// Page Content ?>
<div class="container-fluid bg-wwi">

    <div class="container bg-wwi">
        <div class="row">

            <?php
            //sidebar
            //include __DIR__.'/includes/sidebar.php'; ?>

            <div class="col-lg-12">

                <?php if(isset($_POST['email'])){?>
                    <div class="col-lg-12 bg-light rounded">
                        <br>
                        <h1>Wachtwoord Vergeten</h1>
                        <br>
                        <center><form action= "forgot.php" method="POST">
                                wanneer uw mailadres bekend is bij ons krijgt u een mail met een authenticatie key<br>
                                <input type="text" placeholder= "key" name="key" class="form-control col-4" required>
                                <br>
                                	nieuw wachtwoord:
                                <br>
                                <input type='password' placeholder='wachtwoord' name='password' class="form-control col-4" required>
                                <br>
                                	(let op! het wachtwoord moet 8 of meer tekens bevatten)
                                <br>
                                <input type="submit" class= "btn btn-success"><br><br>
                            </form></center>
                    </div>
                    <?php }
                    elseif(isset($_POST['key'])){ ?>
                      <div class="col-lg-12 bg-light rounded">
                          <br>
                          <h1>Wachtwoord Vergeten</h1>
                          <br>
                          <center>
                              Uw gegevens zijn mogelijk aangepast
                          </center>
                      </div>

                  <?php  }

                     else{?>
                        <div class="col-lg-12 bg-light rounded">
                            <br>
                            <h1>Wachtwoord Vergeten</h1>
                            <br><br>
                            <center>
																<form action= "forgot.php" method="POST">

	                                    <input type="email" placeholder= "email" name="email" class="form-control col-4" required/>
																			<br>
																				<div class="g-recaptcha" data-sitekey="6Lfsl30UAAAAABXKYLLc4KZp6jKGyhYDsiaN7gl9" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>
																					<input type="hidden" id="captcha-response" name="captcha-response" />
																				<br>



	                                    <input type="submit" class= "btn btn-success"/><br><br>
	                              </form>
															</center>
                        </div>
                      <?php } ?>
                </div>
            </div>
        </div>
    </div>

</div>
 <?php//.container ?>

 <?php//Footer ?>
<?php include __DIR__ . '/includes/footer.php'; ?>

 <?php// Bootstrap core JavaScript ?>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<script>
var onloadCallback = function() { // captcha verkrijgen.
		grecaptcha.execute();
};

function setResponse(response) { // kijken of de persoon geen robot is.
		document.getElementById('captcha-response').value = response;
}
</script>
