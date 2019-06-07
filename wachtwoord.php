<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.0/css/all.css" integrity="sha384-Mmxa0mLqhmOeaE8vgOSbKacftZcsNYDjQzuCOm6D02luYSzBG8vpaOykv9lFQ51Y" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Razer || Profiel</title>
  </head>
  <body>
    <?php
        session_start();
    $Msg = "";
    //updating van profiel
    if(isset($_POST['submit'])) {
      include 'DBconnect.php';
      $oud = $_POST['oud'];
      $nieuw = $_POST['nieuw'];
      $nieuwcon = $_POST['nieuwc'];
    try {
        $stmt = $db->prepare("SELECT * FROM klant WHERE IDklant = :IDklant");
        $stmt->bindValue(':IDklant', $_SESSION['IDklant']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if(empty($oud) || empty($nieuw) || empty($nieuwcon)) {
        $Msg = "Vul alle velden in!";
      } else {
        if(!password_verify($oud, $result['SaveCodeUser'])) {
          $Msg = "Het ingevulde wachtwoord komt niet overeen met het oude wachtwoord!";
        } else {
          if($nieuw != $nieuwcon) {
            $Msg = "De nieuwe wachtwoorden zijn niet gelijk";
          } else {
            $hashedpwd = password_hash($nieuw, PASSWORD_DEFAULT);
            $query = "UPDATE klant SET SaveCodeUser = :password WHERE IDklant = :IDklant";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':IDklant', $_SESSION['IDklant']);
            $stmt->bindValue(':password', $hashedpwd);
            $stmt->execute();
            $Msg = "wachtwoord is gewijzigd!";
            header('Refresh: 3; ../gast/login.php');
            session_destroy();
          }
        }
      }
    } catch (\Exception $e) {
      die("error!: " . $e->getMessage());
    }
    }
      include 'navbar2.php';
      include 'DBconnect.php';

      //data ophalen
          try {
            $stmt = $db->prepare("SELECT * FROM klant WHERE IDklant = :IDklant");
            $stmt->bindValue(':IDklant', $_SESSION['IDklant']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
          } catch (\Exception $e) {
            die("error!: " . $e->getMessage());
          }
          if ($Msg != "") {
            echo '<div class="bg-dark text-danger container" id="errormes">';
            echo $Msg . "<br><br>";
            echo '</div>';
          }
    ?>
      <main class="container">
        <section class="head1">
        <h2>Profiel</h2>
      </section>
      <section class="article2">
        <form action="wachtwoord.php" method="post">
          <input class="form-control" type="text" value="<?php echo $result['IDklant']; ?>" name="IDklant" hidden>
          <label>bestaand wachtwoord</label>
          <input class="form-control" type="password" name="oud" required><br>
          <label>nieuw wachtwoord</label>
          <input class="form-control" type="password" name="nieuw" required><br>
          <label>herhaal niew wachtwoord</label>
          <input class="form-control" type="password" name="nieuwc" required><br>
          <button class="btn btn-block btn-primary" type="submit" name="submit">submit</button><br>
        </form>
      </section>
      </main>
      <div id="global">

      </div>
    <?php
      include 'footer.html';
     ?>
  </body>
</html>
