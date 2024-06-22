<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe";
$port =  3306;

try {
    // Include the port number in the DSN
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(); // Stop further execution if connection fails
}

?>
