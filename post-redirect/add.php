<?php
session_start();


if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

require_once "pdo.php";


// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
         if(strlen($_POST['make'])<1){
            $_SESSION['error'] = "Make is required";
            header("Location: add.php");
            return;
         }
         elseif(!is_numeric($_POST['year'])||!is_numeric($_POST['mileage'])){
            $_SESSION['error'] = "Mileage and year must be numeric";
            header("Location: add.php");
            return;
         }
         else{
            $sql = "INSERT INTO autos (make, year, mileage) 
              VALUES (:make, :year, :mileage)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
               ':make' => $_POST['make'],
               ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage']));
                $_SESSION['success'] = "Record inserted";
                header("Location: view.php");
                return;
    }
}

?>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
    <title>Jarif Ahmed Soumik</title>
</head><body>
<h1>Tracking Autos for <?php echo $_SESSION['name']; ?></h1>
<?php
     if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
<form method="post">
<p>Make:
<input type="text" name="make" size="40"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="mileage" name="mileage"></p>
<input type="submit" value="Add"/>
<input type="submit" name="logout" value="Logout">
</form>
</body>
</html>