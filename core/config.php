<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'training');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'tura');
 
// Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
