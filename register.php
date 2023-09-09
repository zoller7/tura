<?php
// Include config file
require_once "core/config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "A felhasználónév csak betűket, számokat és aláhúzásjelet tartalmazhat.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Ez a felhasználónév már foglalt.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Hoppá! Valami elromlott. Kérlek, próbáld újra később.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Adjon meg egy jelszót.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "A jelszónak legalább 6 karakterből kell állnia.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Kérjük, erősítse meg a jelszót.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "A jelszó nem egyezik.";
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
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
    <title>Regisztráció</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 480px; padding: 50px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Túra®</a>
        </div>
    </nav>
    <div class="container">
        <div class="wrapper">
            <h2>Regisztráció</h2>
            <p>Kérjük, töltse ki ezt az űrlapot fiók létrehozásához.</p>
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
                        class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-1">Jelszó megerősítése:</label>
                    <input type="password" 
                        name="confirm_password" 
                        class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary" value="Beküldés">
                    <input type="reset" class="btn btn-secondary ml-2" value="Visszaállítás">
                </div>
                <p>Már van fiókja? <a href="login.php">Bejelentkezzen be</a>.</p>
            </form>
        </div>
    </div>   
</body>
</html>