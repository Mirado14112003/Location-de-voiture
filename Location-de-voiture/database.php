<?php
$servername = "3306";
$username = "root";
$password = "";
$dbname = "location_voiture";

try {
  $conn = new PDO("mysql:host=localhost;dbname=location_voiture", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>