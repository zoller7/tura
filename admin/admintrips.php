<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: admin-login.php");
    exit;
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
         body{ font: 14px sans-serif; text-align: center; }
         h1,h2 { text-align: center;}
      </style>
   </head>
   <body>
      <?php include('../templates/admin-menu.php') ?>
      <div class="container">
        <h1 class="my-5">Helló, 
            <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Üdvözöljük a Túra® oldal adminisztrációs oldalán.
        </h1>
        <h2 class="my-5">Instant túrák listája</h2>
        <hr>
         <!-- cards -->
         <div class="row">

            <table class="table">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Aktív</th>
                <th scope="col">Név</th>
                <th scope="col">Checkpointok száma</th>
                <th scope="col">Kép URL</th>
                <th scope="col">Rövid leírás</th>
                <th scope="col">Szerkesztés</th>
                </tr>
            </thead>
            <tbody>
                <?php include('edit-cards.php')?>
            </tbody>
            </table>
         </div>
      </div>
   </body>
</html>