<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "../core/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Kérjük, adja meg a felhasználónevet.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Kérem írja be a jelszavát.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            
                            // Redirect user to admin index page
                            header("location: index.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Érvénytelen felhasználónév vagy jelszó.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Érvénytelen felhasználónév vagy jelszó.";
                }
            } else {
                echo "Hoppá! Valami elromlott. Kérlek, próbáld újra később.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 480px; padding: 50px; }
    </style>
</head>
<body>

    <?php include('../templates/simple-menu.php') ?>

    <div class="container">

        <div class="wrapper">
            <h2>Belépés</h2>
            <p>Kérjük, adja meg adatait a bejelentkezéshez.</p>

            <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group mb-3">
                    <label class="mb-1">Felhasználónév:</label>
                    <input type="text" 
                        name="username" 
                        class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group mb-3">
                    <label class="mb-1">Jelszó:</label>
                    <input type="password" 
                        name="password" 
                        class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" value="Belépés">
                </div>
            </form>
        </div>
    </div>
</body>
</html>