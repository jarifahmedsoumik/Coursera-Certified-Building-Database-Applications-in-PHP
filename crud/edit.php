<!-- Model -->
<?php
require_once('pdo.php');
session_start();

if(!isset($_SESSION['name'])){
    die('ACCESS DENIED');
}

// If the user requested cancel, go to index.php
if(isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

// If the user clicked the save btn
if(isset($_POST['save']) && isset($_POST['autos_id'])){
    // Validate the input
    if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])){
        if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1){
            $_SESSION['error'] = "All fields are required";
            header('Location: edit.php?autos_id='.$_POST['autos_id']);
            return;
        }else{  
            if(!is_numeric($_POST['year'])){
                $_SESSION['error'] = "Year must be an integer";
                header('Location: edit.php?autos_id='.$_POST['autos_id']);
                return;
            }
            if(!is_numeric($_POST['mileage'])){
                $_SESSION['error'] = "Mileage must be an integer";
                header('Location: edit.php?autos_id='.$_POST['autos_id']);
                return;
            }
            // **Success**
            $stmt = $pdo->prepare('UPDATE autos SET make=:mk, model=:md, year=:yr, mileage=:mi WHERE autos_id=:a_id');
            $stmt->execute(array(
                ':mk' => $_POST['make'],
                ':md' => $_POST['model'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage'],
                ':a_id' => $_POST['autos_id']
            ));
            $_SESSION['success'] = "Record edited";
            // When D is successfully edited to DB
            header('Location: index.php');
            return;
        }
    }   
}
?>

<!-- View -->
<!DOCTYPE html>
<html>
 <head>
  <title>Jarif Ahmed Soumik</title>
 </head>
<body>
    <h1>Tracking Autos for <?php
    if(isset($_SESSION['name'])){
      echo htmlentities($_SESSION['name']);
    }?></h1>

  <?php
  if(isset($_SESSION["error"])){
    echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
    unset($_SESSION["error"]);
  }

  $stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id=:a_id");
  $stmt->execute(array(":a_id" => $_GET['autos_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // Declare variables
  $make = htmlentities($row['make']);
  $model = htmlentities($row['model']);
  $year = htmlentities($row['year']);
  $mileage = htmlentities($row['mileage']);
  $autos_id = $row['autos_id'];

  ?>     
    <form method="post">
        <p>Make:
        <input type="text" name="make" value="<?= $make; ?>" size="60"/></p>
        <p>Model:
        <input type="text" name="model" value="<?= $model; ?>" size="60"/></p>
        <p>Year:
        <input type="text" name="year" value="<?= $year; ?>" /></p>
        <p>Mileage:
        <input type="text" name="mileage" value="<?= $mileage; ?>" /></p>
        <input type="hidden" name="autos_id" value="<?= $autos_id; ?>">
        <input type="submit" name="save" value="Save">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</body>
</html>