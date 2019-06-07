
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Razer || Login</title>
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
  session_start();
    $Msg ="";

  if(isset($_POST['submit'])){

    $username = $_POST["username"];
    $password = $_POST["password"];

    if(empty($username) || empty($password)) {
      $Msg = "vul alle bij de velden in";
    } else {

      try {
        include 'DBconnect.php';
        $stmt = $db->prepare("SELECT IDklant,username, SaveCodeUser, IDmedewerker FROM klant WHERE username = :username ");
        $stmt ->bindValue(':username',$username);
        $stmt->execute();
        $results = $stmt->fetch();
        if($stmt->rowCount() == 1) {

          if(!password_verify($password, $results['SaveCodeUser'])) {
            $Msg = "het wachtwoord en username komen niet overeen";

          } else {
              $_SESSION['username'] = $username;
              $_SESSION['IDklant'] = $results['IDklant'];

              if($results['IDmedewerker'] == 1) {
                $_SESSION['blogin'] = true;
                header('Refresh: 2; ../beheerder/beheerderindex.php');
                $Msg = "u bent succesvol ingelogd als beheerder " . $results['username'];
              }elseif ($results['IDmedewerker'] == 0) {
              $_SESSION['klogin'] = true;
              header('Refresh: 2; ../klant/klantindex.php');
              $Msg = "u bent succesvol ingelogd " . $results['username'];
            }
          }
        } else {
          $Msg = "er zijn geen accounts gevonden met het username dat u invoerde";
        }
      } catch(PDOException $e) {
        die("error!: " . $e->getMessage());
      }
    }
  }

      if ($Msg != "") {
        echo '<div class="bg-dark text-danger container" id="errormes">';
        echo $Msg . "<br><br>";
        echo '</div>';
      }
    ?>


    <main class="container">
      <section class="head1">
        <h2>Login</h2>
      </section>

      <section class="article1">
        <form action="login.php" method="post">
          <label> username </label>
          <input type="text" name="username" class="form-control" placeholder="username..." required><br>
          <label> password </label>
          <input type="password" name="password" class="form-control" placeholder="password..." required><br>
          <button type="submit" name="submit" class="btn btn-primary">submit</button>
        </form>
      </section>

    </main>

    <div id="global">    </div>

    <?php
    include 'footer.html';
    ?>
  </body>
  </html>
