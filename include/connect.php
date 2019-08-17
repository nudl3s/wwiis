<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "challenge2";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Error '.$conn->connect_errno.' '.$conn->connect_error);
}
mysqli_set_charset($conn, 'utf8');
if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
} 

?>