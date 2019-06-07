<?php
  session_start();
  $Msg = "";
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    //nav include
      include 'navbar2.php';
    ?>
    <main class="container">
      <section class="head1">
      <h2>bestellen</h2>
    </section>
    <section class="article2">
      <?php
        include 'DBconnect.php';
        //kijken of er op bestellen is gedrukt
        if(isset($_POST['bestellen'])) {
          $aantal = $_POST['aantal'];
          $stmt = $db->prepare("SELECT * FROM klant WHERE IDklant = :IDklant");
          $stmt->bindValue(':IDklant', $_SESSION['IDklant']);
          if($aantal <= 0) {
            $Msg = "u moet wel een aantal ingevoerd worden om te willen bestellen";
          } else {
            if($stmt->execute()) {
              $result = $stmt->fetch(PDO::FETCH_ASSOC);

              $date = date('Y-m-d');

              $bestelling = "INSERT INTO bestelling (IDklant, datum) VALUES (:IDklant, :datum)";
              $sstmt = $db->prepare($bestelling);
              $sstmt->bindValue(':IDklant', $_SESSION['IDklant']);
              $sstmt->bindValue(':datum', $date);
              $sstmt->execute();

              $idbestelling = $db->lastInsertId();
              $IDproduct = $_POST['IDproduct'];

              $bestelling = "INSERT INTO besteldetail (IDproduct, IDbestelling, aantal) VALUES (:IDproduct, :IDbestelling, :aantal)";
                $sstmt = $db->prepare($bestelling);
                $sstmt->bindValue(':IDproduct', $IDproduct);
                $sstmt->bindValue(':IDbestelling', $idbestelling);
                $sstmt->bindValue(':aantal', $aantal);
                if($sstmt->execute()) {
                  $Msg = "uw bestelling is successvol geplaatst!";
                  header('Refresh: 3; mijnbestellingen.php');
                }
            }


          }
        }
       ?>
        <?php
        //error message laten zien
        if ($Msg != "") {
          echo '<div class="bg-dark text-danger container" id="errormes">';
          echo $Msg . "<br><br>";
          echo '</div>';
        }
         ?>
       <?php
         try {
           include 'DBconnect.php';
           $query = "SELECT * FROM product";
           $stmt = $db->prepare($query);
           $stmt->execute();

           echo "<table border='1'>";
           echo '<thead>
                   <td>Product naam</td>
                   <td>Prijs</td>
                   <td>Aantal</td>
                   <td>IDproduct</td>
                 </thead>';
           while($rij = $stmt->fetch(PDO::FETCH_ASSOC)) {
             ?>
               <form action="bestellen.php" method="post">
                 <tr>
                   <td><input type="text" name="naam" value="<?php echo($rij['productnaam'])?>" class="form-control"></td>
                   <td><input type="text" name="prijs" value="<?php echo($rij['prijs'])?>" disabled></td>
                   <td><input type="number" name="aantal" class="form-control"></td>
                   <td><input type="hidden" name="IDproduct" value="<?php echo($rij['IDproduct'])?>" class="form-control"></td>
                   <td><button class="btn btn-outline-success mr-sm-2" type="submit" name="bestellen">bestel</button></td>
                 </tr>
               </form>
             <?php
           }
         } catch (\Exception $e) {
           die("Error!: " . $e->getMessage());
         }

        ?>
     </section>
     </main>
  </body>
</html>
