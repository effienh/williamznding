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

        <?php// Custom styles for this template ?>
        <link href="css/shop-homepage.css" rel="stylesheet">

        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
</head>
<body>
    <?php// Include header en check.php en filtert wachtwoord op special chars ?>
    <?php include __DIR__ . '/includes/header.php';
    $_SESSION['email'] = sanitize_post('email','email');
    // filter_input(INPUT_TYPE, 'formObject', FILTER_SANITIZE_FORMOBJECTTYPE)
    ?>
<?php// classes voor de opmaak ?>
<div class="container-fluid bg-wwi">
    <div class='container bg-wwi'>
        <font color="white">
        <div class="container">

          <?php// form redirect naar check.php ?>
        <form class="form" action="includes/check.php" method= "POST">

            <h1><b>Account registratie</b></h1>
            <br>

            <div class="form-row">
    <div class="form-group col-md-6">
      <label for="ac-voornm">Voornaam:</label><?php //zodra gegevens fout worden mee gegeven word alles weer terug gestuurd. ?>
      <input type="text" class="form-control" id="ac-voornm" placeholder="Voornaam" name="ac-voornm" <?php if(isset($_POST['ac-voornm'])){echo "value='".sanitize_post('ac-voornm', 'string')."'";}?> required>
    </div>
    <div class="form-group col-md-6">
      <label for="ac-achternm">Achternaam:</label>
      <input type="text" class="form-control" id="ac-achternm" placeholder="Achternaam" name="ac-achternm" <?php if(isset($_POST['ac-achternm'])){echo "value='".sanitize_post('ac-achternm', 'string')."'";}?> required>
    </div>
  </div>

  <div class="form-group">
    <label for="ac-Email">E-mailadres:</label>
    <input type="Email" class="form-control" id="ac-Email" placeholder="Email" name="ac-Email" <?php if(isset($_POST['ac-Email'])){echo "value='".sanitize_post('ac-Email', 'email')."'";}?> required>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="ac-ww">Wachtwoord:</label>
      <?php
      if(isset($_POST['pwshort'])){//als het wachtwoord te kort is of ze komen niet overeen word dat weer gegeven
        ?>
        <input class="form-control" aria-describedby="passhelp" id="ac-ww" type="password" placeholder="Wachtwoord" name="ac-ww" required>
        <small id="passhelp" class="form-text" style="color: #dc3545;">
        Uw wachtwoorden moeten gelijk zijn en minstens 8 characters bevatten. &#10094;-- niet voldaan
        </small>
        <?php
      }else{
        ?>
        <input class="form-control" aria-describedby="passhelp" id="ac-ww" type="password" placeholder="Wachtwoord" name="ac-ww" required>
        <small id="passhelp" class="form-text" style='color: #f8f9fa;'>
        Uw wachtwoord moet minstens 8 characters bevatten.
        </small>
        <?php
      }
       ?>

    </div>
      <div class="form-group col-md-6">
        <label for="ac-wwc">Wachtwoord Bevestigen:</label>
        <input class="form-control" id="ac-wwc" type="password" placeholder="Wachtwoord" name="ac-wwc" required>
      </div>
    </div>
<?php if(isset($_POST['exists'])){//zodra een van de gegevens onjuist is of de gebruiker bestaat al dan word deze melding weergegeven.
    ?>
      <div class='form-text container' style='color: #dc3545;'>
        <center>Uw gegevens zijn onjuist.</center>
      </div>
<?php
} ?>

  <h1><b>NAW Gegevens</b></h1>
  <br>

  <div class="form-row">
    <div class="form-group col-md-12">
      <label for="naw-address">Straatnaam en Huisnummer:</label>
      <input class="form-control" id="naw-address" type="text" placeholder="Straatnaam en huisnummer" name="naw-address" <?php if(isset($_POST['naw-address'])){echo "value='".sanitize_post('naw-address', 'string')."'";}?> required>
    </div>
</div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="naw-postcd">Postcode:</label>
        <input class="form-control" id="naw-postcd" type="text" placeholder="Postcode" name="naw-postcd" <?php if(isset($_POST['naw-postcd'])){echo "value='".sanitize_post('naw-postcd','string')."'";}?> required>
      </div>
        <div class="form-group col-md-6">
          <label for="naw-telnr">Telefoonnummer:</label>
          <input class="form-control" id="naw-telnr" type="number" placeholder="Telefoonnummer" name="naw-telnr" <?php if(isset($_POST['naw-telnr'])){echo "value='".sanitize_post('naw-telnr','number')."'";}?> required>
        </div>
        <div class="form-group col-md-6">
          <label for="naw-plaatsnm">Plaatsnaam:</label>
          <input class="form-control" id="naw-plaatsnm" type="text" placeholder="Plaatsnaam" name="naw-plaatsnm" <?php if(isset($_POST['naw-plaatsnm'])){echo "value='".sanitize_post('naw-plaatsnm', 'string')."'";}?> required>
        </div>
        <div class="form-group col-md-6">
          <label for="naw-province">Provincie:</label>
          <select class="form-control" id= "naw-province" name="naw-province" required>
          <?php if(isset($_POST['naw-province'])){
            echo "<option value='" . sanitize_post('naw-province','number') . "'>" . sqlselect("SELECT StateProvinceName FROM stateprovinces WHERE StateProvinceID = ?",array(sanitize_post('naw-province','number')))[0]['StateProvinceName'] . "</option>";
            foreach (sqlselect("SELECT StateProvinceName, StateProvinceID FROM stateprovinces WHERE CountryID = 153", array()) as $province) {
                if (sanitize_post('naw-province','number') !== $province['StateProvinceID']) {
                    echo "<option value='" . $province['StateProvinceID'] . "'>" . $province['StateProvinceName'] . "</option>";
                }
            }
          }else{ // alle provincies van nederland uit de database halen, CountryID = 153.
            foreach(sqlselect("SELECT StateProvinceName, StateProvinceID FROM stateprovinces WHERE CountryID = 153",array()) as $province){
              echo "<option value='". $province['StateProvinceID']. "'>".$province['StateProvinceName']."</option>";
            }
          }
             ?>
          </select>
      </div>

        <div class="form-group col-md-6">
          <div class="g-recaptcha" data-sitekey="6Lfsl30UAAAAABXKYLLc4KZp6jKGyhYDsiaN7gl9" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>
          <input type="hidden" id="captcha-response" name="captcha-response" />
        </div>
        <div class="form-group col-md-6">
            <input class="btn btn-success" type="submit" value="Registreer" name="register">
        </div>

            </form>
          </div>
        </font>
        </div>
    </div>
</div>

    <?php// Footer ?>
    <footer>
    <?php include __DIR__ . '/includes/footer.php'; ?>
    </footer>
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
