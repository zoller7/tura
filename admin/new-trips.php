<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to admin-login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: admin-login.php");
    exit;
}

// Include config file
require_once "../core/config.php";
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the POST contains data
if (isset($_POST['save_trip'])) {
    $trip_active = $_POST['trip_active'];
    $trip_name = $_POST['trip_name'];
    $trip_checkpoints = $_POST['trip_checkpoints'];
    $trip_pic = $_POST['trip_pic'];
    $trip_shortdesc = $_POST['trip_shortdesc'];
    $trip_longdesc = $_POST['trip_longdesc'];

    // Set the SQL query
    $query = "INSERT INTO trips (trip_active,trip_name,trip_checkpoints,trip_pic,trip_shortdesc,trip_longdesc)
              VALUES ('$trip_active','$trip_name','$trip_checkpoints','$trip_pic','$trip_shortdesc','$trip_longdesc')";
    
    $query_insert = mysqli_query($connection, $query);

    // Feedback on the result
    if ($query_insert) {
        $_SESSION['status'] = "Sikeres módosítás";
        header("edit-trip.php");
    } else {
        $_SESSION['status'] = "Hoppá! Valami elromlott. Kérlek, próbáld újra később.";
        header("edit-trip.php");
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Welcome</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
         integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <style>
         body{ font: 14px sans-serif;}
         h1,h2 { text-align: center;}
      </style>
   </head>
   <body>
      <?php include('../templates/admin-menu.php') ?>
      <div class="container">
        <h1 class="my-5">Helló, 
            <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Üdvözöljük a Túra® oldal adminisztrációs oldalán.
        </h1>
        <h2 class="my-5">Itt szerkesztheti a túrát</h2>
        <hr>

        <form action="" method="POST">
            <!-- 2 column grid layout with text inputs for the first and last names -->
            <div class="row mb-4">
                <div class="col">
                <div class="form-group">
                    <label class="form-label" for="trip_id">Túra azonosító</label>
                    <input type="text" name="trip_id" id="trip_id" placeholder="Nem szerkeszthető" class="form-control" value="" disabled />
                </div>
                </div>
                <div class="col">
                <div class="form-group">
                    <label class="form-label" for="trip_active">Aktív/Inaktív</label>
                    <select class="form-control" name="trip_active" id="trip_active">
                        <option value="1" id="trip_active-0">Aktív</option>
                        <option value="0" id="trip_active-0">Inaktív</option>
                    </select>
                </div>
                </div>
            </div>

            <!-- Text input -->
            <div class="form-group mb-4">
                <label class="form-label" for="trip_name">Túra neve</label>
                <input type="text" name="trip_name" id="trip_name" class="form-control" value="" />
            </div>

            <!-- Number input -->
                <div class="form-group mb-4">
                <label class="form-label" for="trip_checkpoints">Túra checkpointok száma</label>
                <input type="number" name="trip_checkpoints" id="trip_checkpoints" min="1" max="10" step="1" class="form-control" value="" />
            </div>

            <!-- Text input -->
            <div class="form-group mb-4">
                <label class="form-label" for="trip_pic">Túrakép URL</label>
                <input type="text" name="trip_pic" id="trip_pic" class="form-control" value="" />
            </div>

            <!-- Message input -->
            <div class="form-group mb-4">
            <label class="form-label" for="trip_shortdesc">Rövid leírás</label>
                <textarea class="form-control" name="trip_shortdesc" id="trip_shortdesc" rows="2"></textarea>
            </div>

            <!-- Message input -->
            <div class="form-group mb-4">
            <label class="form-label" for="trip_shortdesc">Hosszú leírás</label>
                <textarea class="form-control" name="trip_longdesc" id="trip_longdesc" rows="4"></textarea>
            </div>

            <!-- Submit button -->
            <button type="submit" name="save_trip" class="btn btn-primary btn-block mb-4">Mentés</button>
        </form>

        <?php 
            if (isset($_SESSION['status'])) {
                echo "<h5>" . $_SESSION['status']. "</h5>";
                unset($_SESSION['status']);
            }
        ?>

      </div>
   </body>
</html>