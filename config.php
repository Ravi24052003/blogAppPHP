<?php

$host = "127.0.0.1";
$port = "3307"; // Port number
$dbName = "mind_space";
$username = "root";
$password = null;

try {
    // Create a new PDO instance with the port specified in the DSN string
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die("Connection failed: " . $err->getMessage());
}
?>