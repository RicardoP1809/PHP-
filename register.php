<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Razer || Register</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <?php
      include "navbar1.php";
    ?>
    <?php

      //error msg valueble aan gemaakt
        $msg = "";

        if(isset($_POST['submit'])) {
          $con = new PDO("mysql:hostname=localhost;dbname=razer v.2","root","");

        //all data op halen uit de input bladen
        $passw = ($_POST['password']);
        $cfpassw = ($_POST['cfpassword']);
        $naam = ($_POST['voornaam']);
        $username = ($_POST['username']);
        $Achternaam = ($_POST['achternaam']);
        $telefoon = ($_POST['telefoonnummer']);
        $email = ($_POST['email']);
        $Straat = ($_POST['straat']);
        $Postcode = ($_POST['postcode']);
        $stad = ($_POST['stad']);
        $huisnu = ($_POST['huisnummer']);
        $gebd = ($_POST['gebdatum']);
        $nieuwsbrief =  ($_POST['nieuwsbrief']);

        //Error
        if  (empty($passw) ||empty($cfpassw) ||empty($passw) ||empty($naam) ||empty($username) ||empty($Achternaam) ||empty($huisnu) ||empty($gebd) ||empty($nieuwsbrief) ||empty($telefoon) ||empty($email) ||empty($Straat) ||empty($Postcode)||empty($stad)) {
          $msg = "vul alles in";
        } else {
          //email Invalid
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "email is niet een goed email adres!";
          } else {
            //kijken of de email al in de data staat
            $sql = $con->prepare("SELECT IDklant FROM klant WHERE email='$email'");
            $sql->execute();
            if($sql->rowCount() >= 1) {
              $msg = "Dit email adres is al in gebruik bij ons";
            } else {
              //kijken of de username al in de data staat
              $sql = $con->prepare("SELECT IDklant FROM klant WHERE username='$username'");
              $sql->execute();
              if($sql->rowCount() >= 1) {
                $msg = "deze username is al in gebruik";
              } else {
                    if(strlen($username) > 20 || strlen($username) < 8 ) {
                      $msg = "uw username moet tussen 20 en 4 tekens zijn";
                    } else {
                      if(!is_numeric($telefoon)) {
                        $msg = "uw telefoonnummer is zijn geen nummers";
                      } else {
                        if($passw != $cfpassw) {
                          $msg = "de wachtwoorden zijn niet gelijk aan elkaar";
                        } else {
                          //password hashen
                            $hashedpwd = password_hash($passw, PASSWORD_DEFAULT);
                            //controleren of iemand een nieuwsbrief wil
                            if($nieuwsbrief == 1) {
                              $nieuwsbrief = "Ja";
                            } else {
                              $nieuwsbrief = "Nee";
                        }
                        //data in de database inserten
                          $query = $con->prepare(("INSERT INTO klant (voornaam, achternaam, username, SaveCodeUser, telefoonnummer, email, straat, postcode, huisnummer, geboortedatum, Stad, nieuwsbrief)
                          VALUES (:voornaam, :achternaam, :username, :password, :telefoonnummer, :email, :straat, :postcode, :huisnummer, :gebdatum, :stad, '$nieuwsbrief')"));
                          $query->bindValue(':password', $hashedpwd);
                          $query->bindValue(':voornaam', $naam);
                          $query->bindValue(':username', $username);
                          $query->bindValue(':achternaam', $Achternaam);
                          $query->bindValue(':email', $email);
                          $query->bindValue(':telefoonnummer', $telefoon);
                          $query->bindValue(':straat', $Straat);
                          $query->bindValue(':postcode', $Postcode);
                          $query->bindValue(':stad', $stad);
                          $query->bindValue(':huisnummer', $huisnu);
                          $query->bindValue(':gebdatum', $gebd);
                          $query->execute();

                          $msg = "u bent succesvol toegevoegd!";
                          header('Refresh: 3; index.php');
                      }
                    }
                  }
                }
              }
            }
          }
        }

      if ($msg != "") {
        echo '<div class="bg-dark text-danger container" id="errormes">';
        echo $msg . "<br><br>";
        echo '</div>';
      }
/*
    $squery= "update product set productnaam = :productnaam, :productprijs
    where productid = :productid";
    $query = $db->prepare($squery);
    $query->bindParam('')
*/
    ?>


    <main class="container">
      <div class="register">
        <section class="head1">
          <h2>Register</h2>
        </section>
        <section class="article2">
          <div class="container">
          <form action="register.php" method="post">
              <br>
              <label> voornaam </label>
              <input class="form-control" type="text" placeholder="Voornaam..."  name="voornaam" required><br>
              <label> achternaam </label>
              <input class="form-control" type="text" placeholder="Achternaam..." name="achternaam" required><br>
              <label> username </label>
              <input class="form-control" type="text" placeholder="username..." name="username" required><br>
              <label> password </label>
              <input class="form-control" type="password" placeholder="password..." name="password" required><br>
              <label> confirm password</label>
              <input class="form-control" type="password" placeholder="confirm password..." name="cfpassword" required><br>
              <label> telefoonnummer </label>
              <input class="form-control" type="text" placeholder="Telefoonnummer..." name="telefoonnummer" maxlength="10" required ><br>
              <label> E-mail</label>
              <input class="form-control" type="email" placeholder="E-mail..." name="email" required><br>
              <label> straat </label>
              <input class="form-control" type="text" placeholder="Straat..." name="straat" required><br>
              <label> Postcode </label>
              <input class="form-control " type="text" placeholder="Postcode..." name="postcode" required maxlength="6"><br>
              <label> Stad </label>
              <input class="form-control" type="text" placeholder="Stad..." name="stad"  required><br>
              <label> Huisnummer </label>
              <input class="form-control" type="text" placeholder="Huisnummer..." name="huisnummer"  required maxlength="4"><br>
              <label> geboortedatum </label>
              <input class="form-control" type="date" name="gebdatum" required><br>
              <label>Nieuwsbrief</label>
              <div class="input-group mb-3">
                <select class="custom-select" id="inputGroupSelect03" aria-label="Example select with button addon" name="nieuwsbrief">
                  <option selected>Kies...</option>
                  <option value="1">Ja ik wil de nieuwsbrief wekelijks ontvangen.</option>
                  <option value="2">Nee ik hoef geen wekelijkse nieuwsbrief.</option>
                </select>
              </div>
              <br>
            </div>
          </section>
          <br>

          <button class="btn btn-block btn-primary" type="submit" name="submit">submit</button>
        </form>
      </div>
    </main>

    <?php
      include 'footer.html';
     ?>
  </body>
</html>
